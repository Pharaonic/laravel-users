<?php

namespace Pharaonic\Laravel\Users\Models\Actions;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'votable_type', 'votable_id',
        'voter_type', 'voter_id',
        'vote'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = ['vote' => 'boolean'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function votable()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function voter()
    {
        return $this->morphTo();
    }
}
