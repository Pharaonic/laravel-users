<?php

namespace Pharaonic\Laravel\Users\Models\Actions;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bookmarkable_type', 'bookmarkable_id',
        'bookmarker_type', 'bookmarker_id',
        'data'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = ['data' => 'array'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function bookmarkable()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function bookmarker()
    {
        return $this->morphTo();
    }
}
