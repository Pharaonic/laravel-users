<?php

namespace Pharaonic\Laravel\Users\Models\Agents;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $device_id
 * @property integer $operation_system_id
 * @property integer $browser_id
 * @property integer $bot_id
 * @property string $user_agent
 * @property string $type
 * @property boolean $is_bot
 * @property Bot $bot
 * @property Browser $browser
 * @property Device $device
 * @property OperationSystem $operationSystem
 * 
 * @author Moamen Eltouny (Raggi) <raggi@raggitech.com>
 */
class Agent extends Model
{
    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'device_id', 'operation_system_id', 'browser_id', 'bot_id',
        'user_agent', 'type', 'is_bot'
    ];

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['is_bot' => 'boolean'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bot()
    {
        return $this->belongsTo(Bot::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function browser()
    {
        return $this->belongsTo(Browser::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function operationSystem()
    {
        return $this->belongsTo(OperationSystem::class);
    }

    /**
     * Finding by User-Agent
     *
     * @param string $agent
     * @return null|Agent
     */
    public static function findByAgent(string $agent)
    {
        return static::where('user_agent', $agent)->first();
    }
}
