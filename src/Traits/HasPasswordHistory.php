<?php

namespace Pharaonic\Laravel\Users\Traits;

use Illuminate\Support\Facades\Hash;
use Pharaonic\Laravel\Users\Models\Users\UserPasswordHistory;

/**
 * Logging Hashed Passwords
 * For Recovery Issues (with last password)
 *
 * @author Moamen Eltouny (Raggi) <raggi@raggitech.com>
 */
trait HasPasswordHistory
{
    /**
     * Set uuid key on creating
     *
     * @return void
     */
    public static function bootHasPasswordHistory()
    {
        self::saved(function ($model) {
            if ($model->isDirty('password') && !$model->wasRecentlyCreated) $model->passwordHistory()->create([
                'pass_from' => $model->getOriginal('password'),
                'pass_to'   => $model->password,
                'ip'        => agent()->ip,
                'agent_id'  => agent()->id
            ]);
        });
    }

    /**
     * Get all user's Hashed-Passwords.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function passwordHistory()
    {
        return $this->morphMany(UserPasswordHistory::class, 'user')
            ->with(['agent', 'agent.device', 'agent.browser',  'agent.operationSystem']);
    }
}
