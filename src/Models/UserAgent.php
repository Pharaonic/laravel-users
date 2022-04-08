<?php

namespace Pharaonic\Laravel\Users\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Agents\Models\Agent;

/**
 * @property integer $id
 * @property integer $agent_id
 * @property string $signature
 * @property string $ip
 * @property string|null $fcm_token
 * @property Carbon $last_action_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Agent $agent
 * @property Object $user
 *
 * @author Moamen Eltouny (Raggi) <support@raggitech.com>
 */
class UserAgent extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['agent_id', 'signature', 'fcm_token', 'ip', 'user_id', 'user_type', 'last_action_at'];

    /**
     * {@inheritDoc}
     */
    protected $casts = ['last_action_at' => 'datetime'];

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

    /**
     * Refresh LAST-ACTION-AT
     *
     * @return boolean
     */
    public function refresh()
    {
        return $this->update(['last_action_at' => Carbon::now()]);
    }
}
