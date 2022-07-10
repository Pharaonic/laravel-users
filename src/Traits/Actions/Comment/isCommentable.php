<?php

namespace Pharaonic\Laravel\Users\Traits\Actions\Comment;

use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Users\Models\Actions\Comment;

trait isCommentable
{
    /**
     * Comment with a Model
     *
     * @param Model $commenter
     * @param string $comment
     * @return boolean
     */
    public function commentBy(Model $commenter, string $body)
    {
        return $this->comments()->make(['commentable' => $this, 'comment' => $body])->commenter()->associate($commenter)->save();
    }

    /**
     * Uncomment with a Model
     *
     * @param Model $commenter
     * @return boolean
     */
    public function unCommentBy(Model $commenter)
    {
        if ($comment = $this->comments()->where(['commenter_id' => $commenter->getKey(), 'commenter_type' => get_class($commenter)])->first())
            return $comment->delete();

        return false;
    }

    /**
     * Has Commented By Commenter
     *
     * @param Model $commenter
     * @return boolean
     */
    public function commentedBy(Model $commenter)
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
