<?php

namespace Pharaonic\Laravel\Users\Traits;

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

    /**
     * Check if device detected
     *
     * @return boolean
     */
    public function hasDetectedDevice()
    {
        return $this->devicesList()->where('agent_id', agent()->id)->exists();
    }

    /**
     * Add Current Agent To Current User
     *
     * @param string $fcm
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
     */
    public function detectDevice(string $fcm = null)
    {
        $this->devicesList()->updateOrCreate(['agent_id' => agent()->id], ['fcm_token' => $fcm, 'ip' => agent()->ip]);
    }

    /**
     * Remove Device by Id
     *
     * @param integer $id
     * @return bool
     */
    public function removeDevice(int $id)
    {
        return $this->devicesList()->where('id', $id)->delete() == 1;
    }

    /**
     * Remove All Device.
     *
     * @return bool
     */
    public function removeAllDevices()
    {
        return $this->devicesList()->delete() > 0;
    }

    /**
     * Getting current device
     *
     * @return UserAgent
     */
    public function getCurrentDeviceAttribute()
    {
        return $this->devicesList()->where('agent_id', agent()->id)->with(['agent.operationSystem', 'agent.browser', 'agent.device'])->first();
    }
}
