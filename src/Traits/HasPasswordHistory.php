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
        self::saving(function ($model) {
            dd($model);
            // if ($model->isDirty('password')) {
            //     $model->password = Hash::make($model->password);
            // }
        });
    }

    /**
     * Get all user's Hashed-Passwords.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    private function passwordHistory()
    {
        return $this->morphMany(UserPasswordHistory::class, 'user');
    }
}
