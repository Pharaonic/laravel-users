<?php

namespace Pharaonic\Laravel\Users\Traits;

use Pharaonic\Laravel\Users\Models\Agents\Agent;
use Pharaonic\Laravel\Users\Models\Agents\UserAgent;

/**
 * @property Agent[] $devices
 * 
 * @author Moamen Eltouny (Raggi) <raggi@raggitech.com>
 */
trait HasDevices
{
    /**
     * Get all of the post's comments.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function devices()
    {
        return $this->morphMany(UserAgent::class, 'user');
    }
}
