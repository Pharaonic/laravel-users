<?php

namespace Pharaonic\Laravel\Users\Traits\Actions\Comment;

use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Users\Models\Actions\Comment;

trait isCommenter
{
    /**
     * Bootstrap the trait.
     *
     * @return void
     */
    public static function bootIsCommenter()
    {
        static::deleting(function ($model) {
            if (in_array(SoftDeletes::class, class_uses($model))) {
                $model->comments()->delete();
            } else {
                $model->comments()->forceDelete();
            }
        });
    }

    /**
     * Comment Model
     *
     * @param Model $commentable
     * @param string $comment
     * @return boolean
     */
    public function comment(Model $commentable, string $comment)
    {
        return $this->comments()->make(['comment' => $comment])->commentable()->associate($commentable)->save();
    }

    /**
     * Has Commented By Commenter
     *
     * @param Model $commentable
     * @return boolean
     */
    public function commented(Model $commentable)
    {
        return $this->comments()->where(['commentable_id' => $commentable->getKey(), 'commentable_type' => get_class($commentable)])->exists();
    }

    /**
     * Comments List
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commenter');
    }
}
