<?php

namespace Pharaonic\Laravel\Users\Classes;

use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Client\Browser as ClientBrowser;
use DeviceDetector\Parser\Device\AbstractDeviceParser;
use DeviceDetector\Parser\OperatingSystem;
use Pharaonic\Laravel\Users\Models\Agents\{
    Agent,
    Bot,
    Browser,
    Device,
    OperationSystem
};

/**
 * @property string $agent
 * @property string $ip
 * @property array $languages
 * @property string $language
 * @property string|null $variant
 *
 * @author Moamen Eltouny (Raggi) <raggi@raggitech.com>
 */
class AgentDetector
{
    /** @var string $agent HTTP User Agent */
    public $agent;

    /** @var string $ip User IP */
    public $ip;

    /** @var array $languages Languages List */
    public $languages;

    /** @var string $language Preferred Language */
    public $language;

    /** @var string $variant Preferred Language's Variant */
    public $variant;

    /**
     * Create new Object
     *
     * @param string|null $user_agent
     * @param string|null $ip
     * @param string|null $accept_langauge
     */
    function __construct($user_agent = null, string $ip = null,  string $accept_langauge = null)
    {
        $request = request();

        $this->agent = $user_agent ?? $request->server('HTTP_USER_AGENT');
        $this->ip = $ip ?? $request->ip();
        $this->langs($accept_langauge ?? $request->server('HTTP_ACCEPT_LANGUAGE') ?? null);

        if ($request->expectsJson()) {
            $agent = Agent::findByAgent($this->agent);
            $agent = $this->load($agent);
        } else {
            if ($request->session()->has('agent')) {
                $agent = $request->session()->get('agent');
            } else {
                $agent = Agent::findByAgent($this->agent);
                $agent = $this->load($agent);
                $request->session()->put('agent', $agent);
            }
        }

        foreach ($agent as $name => $value)
            $this->$name = $value;

        return $this;
    }

    /**
     * Get Language information
     *
     * @param string|null $accept_langauge
     * @return void
     */
    private function langs(string $accept_language = null)
    {
        if (!$accept_language) return;

        $langs = array();
        foreach (explode(',', $accept_language) as $lang) {
            if (strpos($lang, ';') === false) {
                $langs[$lang] = '1';
                continue;
            }
            $lang = explode(';q=', $lang);
            $langs[$lang[0]] = $lang[1];
        }
        arsort($langs, SORT_NUMERIC);
        $langs = array_keys($langs);
        array_walk($langs, function (&$v) {
            if (strpos($v, '-') !== false)
                $v = explode('-', $v);
        });
        $this->languages = $langs;
        if (is_array($langs[0])) {
            $this->language = $langs[0][0];
            $this->variant = $langs[0][1];
        } else {
            $this->lang = $langs[0];
            $this->variant = null;
        }
        unset($langs);
    }

    /**
     * Load info
     *
     * @param Agent $agent
     * @return array
     */
    public function load(Agent $agent = null)
    {
        $data = [
            'id'        => null,

            'isBot'     => false,
            'bot'       => null,

            'type'      => null,

            'device'    => null,
            'os'        => null,
            'browser'   => null,
        ];

        if (!$agent) {
            // Device Detection
            AbstractDeviceParser::setVersionTruncation(AbstractDeviceParser::VERSION_TRUNCATION_NONE);
            $newAgent = new DeviceDetector($this->agent);
            $newAgent->parse();

            // Collect data
            if ($newAgent->isBot()) {
                $bot = $newAgent->getBot();

                $data['isBot'] = true;
                $data['bot'] = (object)[
                    'name'      => $bot['name'],
                    'category'  => $bot['category'] ?? null,
                    'producer'  => $bot['producer']['name'] ?? null
                ];

                // Save Data
                $bot = Bot::firstOrCreate([
                    'name'      => $data['bot']->name,
                    'category'  => $data['bot']->category,
                    'producer'  => $data['bot']->producer
                ]);

                $newAgent = Agent::create([
                    'user_agent'    => $this->agent,
                    'is_bot'        => true,
                    'bot_id'        => $bot->id
                ]);

                $data['id'] = $newAgent->id;
            } else {
                $osFamily = OperatingSystem::getOsFamily($newAgent->getOs('name'));
                $browserFamily = ClientBrowser::getBrowserFamily($newAgent->getClient('name'));

                $client = $newAgent->getClient();
                $os = $newAgent->getOs();

                $device_name = $newAgent->getDeviceName();
                $brand = $newAgent->getBrandName();
                $model = $newAgent->getModel();

                $data['device'] = (object)[
                    'type'      => !empty($device_name) ? $device_name : null,
                    'brand'     => !empty($brand) ? $brand : null,
                    'model'     => !empty($model) ? $model : null
                ];

                $data['os'] = (object)[
                    'family'    => $osFamily ?? null,
                    'name'      => $os['name'] ?? null,
                    'platform'  => !empty($os['platform']) ? $os['platform'] : null
                ];

                $data['browser'] = (object)[
                    'family'    =>  $browserFamily ?? null,
                    'name'      => $client['name'],
                    'engine'    =>  !empty($client['engine']) ? $client['engine'] : null,
                ];

                $data['type'] = $client['type'];

                // Save Data
                if ($data['device']->type)
                    $device = Device::firstOrCreate([
                        'type'          => $data['device']->type,
                        'brand'         => $data['device']->brand,
                        'model'         => $data['device']->model
                    ]);

                if ($data['os']->name)
                    $os = OperationSystem::firstOrCreate([
                        'family'        => $data['os']->family,
                        'name'          => $data['os']->name,
                        'platform'      => $data['os']->platform
                    ]);

                if ($data['browser']->name)
                    $browser = Browser::firstOrCreate([
                        'family'        => $data['browser']->family,
                        'name'          => $data['browser']->name,
                        'engine'        => $data['browser']->engine
                    ]);

                $newAgent = Agent::create([
                    'user_agent'            => $this->agent,
                    'type'                  => $data['type'],
                    'device_id'             => $device->id ?? null,
                    'operation_system_id'   => $os->id ?? null,
                    'browser_id'            => $browser->id ?? null
                ]);

                $data['id'] = $newAgent->id;
            }
        } else {
            $data['id'] = $agent->id;

            if ($agent->is_bot) {
                $data['isBot'] = true;
                $data['bot'] = (object)[
                    'name'      => $agent->bot->name,
                    'category'  => $agent->bot->category,
                    'producer'  => $agent->bot->producer,
                ];
            } else {
                $data['type'] = $agent->type;

                if ($agent->device)
                    $data['device'] = (object)[
                        'type'          => $agent->device->type,
                        'brand'         => $agent->device->brand,
                        'model'         => $agent->device->model
                    ];

                if ($agent->operationSystem)
                    $data['os'] = (object)[
                        'family'        => $agent->operationSystem->family,
                        'name'          => $agent->operationSystem->name,
                        'platform'      => $agent->operationSystem->platform
                    ];

                if ($agent->browser)
                    $data['browser'] = (object)[
                        'family'        => $agent->browser->family,
                        'name'          => $agent->browser->name,
                        'engine'        => $agent->browser->engine
                    ];
            }
        }

        return $data;
    }

    /**
     * Get agent string
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->agent;
    }
}
