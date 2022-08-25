<?php

namespace Pharaonic\Laravel\Users\Traits\Actions\Comment;

use Illuminate\Contracts\Auth\Authenticatable;
use Pharaonic\Laravel\Users\Models\Actions\Comment;

trait isCommentable
{
    /**
     * Bootstrap the trait.
     *
     * @return void
     */
    public static function bootIsCommentable()
    {
        static::deleting(function ($model) {
            $model->comments()->delete();
        });
    }

    /**
     * Comment with a Model
     *
     * @param Authenticatable $commenter
     * @param string $comment
     * @return boolean
     */
    public function commentBy(Authenticatable $commenter, string $comment)
    {
        return $this->comments()->make(['comment' => $comment])->commenter()->associate($commenter)->save();
    }

    /**
     * Has Commented By Commenter
     *
     * @param Authenticatable $commenter
     * @return boolean
     */
    public function commentedBy(Authenticatable $commenter)
    {
        return $this->comments()->where(['commenter_id' => $commenter->getKey(), 'commenter_type' => get_class($commenter)])->exists();
    }

    /**
     * Comments List
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
