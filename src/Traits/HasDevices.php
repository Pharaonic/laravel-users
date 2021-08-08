<?php

namespace Pharaonic\Laravel\Users\Traits;

use Pharaonic\Laravel\Users\Models\Agents\Agent;
use Pharaonic\Laravel\Users\Models\Users\UserAgent;

/**
 * @property Agent[] $devices
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
     */
    private function devicesList()
    {
        return $this->morphMany(UserAgent::class, 'user');
    }

    /**
     * Add Current Agent To Current User
     *
     * @return void
     */
    public function detectDevice()
    {
        $this->devicesList()->updateOrCreate([
            'agent_id'  => agent()->id
        ]);
    }

    /**
     * Remove Device by Id
     *
     * @param integer $id
     * @return void
     */
    public function removeDevice(int $id)
    {
        return $this->devicesList()->where('id', $id)->delete();
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
}
