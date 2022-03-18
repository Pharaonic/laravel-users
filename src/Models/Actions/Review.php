<?php

namespace Pharaonic\Laravel\Users\Models\Actions;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reviewable_type', 'reviewable_id',
        'reviewer_type', 'reviewer_id',
        'rate', 'comment',
        'published'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'rate'      => 'float',
        'published' => 'boolean'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function reviewable()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function reviewer()
    {
        return $this->morphTo();
    }

    /**
     * Publish This Rate
     *
     * @return boolean
     */
    public function publish()
    {
        return $this->update(['published' => true]);
    }

    /**
     * Unpublish This Rate
     *
     * @return boolean
     */
    public function unpublish()
    {
        return $this->update(['published' => false]);
    }
}
