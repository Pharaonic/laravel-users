<?php

namespace Pharaonic\Laravel\Users\Traits;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Pharaonic\Laravel\Agents\Models\Agent;
use Pharaonic\Laravel\Users\Models\UserAgent;

/**
 * Relate the user with his/her devices.
 *
 * @author Moamen Eltouny (Raggi) <raggi@raggitech.com>
 */
trait HasDevices
{
    /**
     * Get all user devices
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDevicesAttribute()
    {
        return Agent::with(['browser', 'device', 'operationSystem'])
            ->join('user_agents', 'agents.id', '=', 'user_agents.agent_id')
            ->where('user_agents.user_id', $this->getKey())
            ->where('user_agents.user_type', get_class($this))
            ->select([
                'user_agents.id',
                'user_agents.ip',
                'agents.type',
                'agents.user_agent',
                'agents.device_id',
                'agents.operation_system_id',
                'agents.browser_id',
                'user_agents.created_at'
            ])
            ->get();
    }

    /**
     * Get all deivces list.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    private function devicesList()
    {
        return $this->morphMany(UserAgent::class, 'user');
    }

    /**
     * List of FCM token.
     *
     * @return array|null
     */
    public function getFcmListArrtibute()
    {
        $list = $this->devicesList()->whereNotNull('fcm_token')->get();
        if ($list->isEmpty()) return null;

        return $list->pluck('fcm_token')->toArray();
    }

    public function getCurrentDeviceSignatureAttribute()
    {
        return session()->isStarted() ? session()->get('device-signature') : request()->headers->get('device-signature');
    }

    /**
     * Check if device detected
     *
     * @param string|null $signature
     * @return boolean
     */
    public function hasDetectedDevice(string $signature = null)
    {
        if (!$signature && !($signature = $this->currentDeviceSignature))
            return false;

        return $this->devicesList()->where([
            'agent_id'  => agent()->id,
            'signature' => $signature
        ])->exists();
    }

    /**
     * Add Current Agent To Current User
     *
     * @param string|null $fcm
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
     */
    public function detectDevice(string $fcm = null)
    {
        if (!($signature = $this->currentDeviceSignature)) {
            $signature = Str::uuid() . '-' . Str::random();

            if (session()->isStarted())
                session()->put('device-signature', $signature);
        }

        return $this->devicesList()->updateOrCreate([
            'agent_id'          => agent()->id,
            'signature'         => $signature
        ], [
            'fcm_token'         => $fcm,
            'ip'                => agent()->ip,
            'last_action_at'    => Carbon::now()
        ]);
    }

    /**
     * Remove Device by signature
     *
     * @param string $signature
     * @return bool
     */
    public function removeDevice(string $signature)
    {
        if ($this->devicesList()->where('signature', $signature)->delete() == 0)
            return false;

        if ($this->currentDeviceSignature == $signature) {
            if (session()->isStarted())
                session()->forget('device-signature');
        }

        return true;
    }

    /**
     * Remove All Device.
     *
     * @return bool
     */
    public function removeAllDevices()
    {
        if (session()->isStarted())
            session()->forget('device-signature');

        return $this->devicesList()->delete() > 0;
    }

    /**
     * Getting current device
     *
     * @return UserAgent
     */
    public function getCurrentDeviceAttribute()
    {
        return $this->devicesList()->where('signature', $this->currentDeviceSignature)
            ->with(['agent.operationSystem', 'agent.browser', 'agent.device'])->first();
    }
}
