<?php

namespace Pharaonic\Laravel\Users\Traits\Password;

use Illuminate\Support\Facades\Hash;

/**
 * Hashing User Password
 *
 * @author Moamen Eltouny (Raggi) <support@raggitech.com>
 */
trait HasHashedPassword
{
    /**
     * Set uuid key on creating
     *
     * @return void
     */
    public static function bootHasHashedPassword()
    {
        self::creating(function ($model) {
            $model->password = Hash::make($model->password);
        });

        self::updating(function ($model) {
            if ($model->isDirty('password')) {
                $model->password = Hash::make($model->password);
            }
        });
    }
}
