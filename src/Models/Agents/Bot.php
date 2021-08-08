<?php

namespace Pharaonic\Laravel\Users\Models\Agents;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property string $category
 * @property string $producer
 * @property Agent[] $agents
 * 
 * @author Moamen Eltouny (Raggi) <raggi@raggitech.com>
 */
class Bot extends Model
{
    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = ['name', 'category', 'producer'];

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
