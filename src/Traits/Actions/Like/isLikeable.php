<?php

namespace Pharaonic\Laravel\Users\Traits\Actions\Like;

use Illuminate\Contracts\Auth\Authenticatable;
use Pharaonic\Laravel\Users\Models\Actions\Like;

trait isLikeable
{
    /**
     * Like with a Model
     *
     * @param Authenticatable $liker
     * @return boolean
     */
    public function likeBy(Authenticatable $liker)
    {
        if ($this->likedBy($liker))
            return true;

        return $this->likes()->make(['likeable' => $this])->liker()->associate($liker)->save();
    }

    /**
     * Unlike with a Model
     *
     * @param Authenticatable $liker
     * @return boolean
     */
    public function unLikeBy(Authenticatable $liker)
    {
        if ($like = $this->likes()->where(['liker_id' => $liker->getKey(), 'liker_type' => get_class($liker)])->first())
            return $like->delete();

        return false;
    }

    /**
     * Has Liked By Liker
     *
     * @param Authenticatable $liker
     * @return boolean
     */
    public function likedBy(Authenticatable $liker)
    {
        return $this->likes()->where(['liker_id' => $liker->getKey(), 'liker_type' => get_class($liker)])->exists();
    }

    /**
     * Likes List
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }
}
