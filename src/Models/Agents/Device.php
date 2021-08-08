<?php

namespace Pharaonic\Laravel\Users\Models\Agents;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $type
 * @property string $brand
 * @property string $model
 * @property Agent[] $agents
 * 
 * @author Moamen Eltouny (Raggi) <raggi@raggitech.com>
 */
class Device extends Model
{
    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = ['type', 'brand', 'model'];

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function agents()
    {
        return $this->hasMany(Agent::class);
    }
}
