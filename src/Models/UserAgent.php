<?php

namespace Pharaonic\Laravel\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Agents\Models\Agent;

/**
 * @property integer $id
 * @property integer $agent_id
 * @property string|null $fcm_token
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Agent $agent
 * @property Object $user
 *
 * @author Moamen Eltouny (Raggi) <raggi@raggitech.com>
 */
class UserAgent extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['agent_id', 'fcm_token', 'user_id', 'user_type'];

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
