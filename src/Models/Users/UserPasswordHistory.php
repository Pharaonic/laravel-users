<?php

namespace Pharaonic\Laravel\Users\Models\Users;

use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Users\Models\Agents\Agent;

/**
 * @property integer $id
 * @property string $pass_from
 * @property string $pass_to
 * @property integer $agent_id
 * @property string $ip
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Agent $agent
 * @property Object $user
 * 
 * @author Moamen Eltouny (Raggi) <raggi@raggitech.com>
 */
class UserPasswordHistory extends Model
{
    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = ['pass_from', 'pass_to', 'agent_id', 'ip', 'user_id', 'user_type'];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    /**
     * Getting User Model
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function user()
    {
        return $this->morphTo();
    }
}
