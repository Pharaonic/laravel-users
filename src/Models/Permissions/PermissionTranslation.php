<?php

namespace Pharaonic\Laravel\Users\Models\Permissions;

use Illuminate\Database\Eloquent\Model;

class PermissionTranslation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['locale', 'permission_id', 'title'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
