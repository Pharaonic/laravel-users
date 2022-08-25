<?php

namespace Pharaonic\Laravel\Users\Models\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Pharaonic\Laravel\Users\Traits\Actions\Comment\isCommentable;
use Pharaonic\Laravel\Users\Traits\Actions\Like\isLikeable;

class Comment extends Model
{
    use isCommentable, isLikeable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'commentable_type', 'commentable_id',
        'commenter_type', 'commenter_id',
        'comment'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function commentable()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function commenter()
    {
        return $this->morphTo();
    }
}
