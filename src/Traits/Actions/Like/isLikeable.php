<?php

namespace Pharaonic\Laravel\Users\Traits\Actions\Like;

use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Users\Models\Actions\Like;

trait isLikeable
{
    /**
     * Like with a Model
     *
     * @param Model $liker
     * @return boolean
     */
    public function likeBy(Model $liker)
    {
        $query = $this->likes()->make(['likeable' => $this])->liker()->associate($liker);
        return $query->exists() ? true : $query->save();
    }

    /**
     * Unlike with a Model
     *
     * @param Model $liker
     * @return boolean
     */
    public function unLikeBy(Model $liker)
    {
        if ($likeable = $this->likes()->make(['likeable' => $this])->liker()->associate($liker)->first())
            return $likeable->delete();

        return false;
    }

    /**
     * Has Liked By Liker
     *
     * @param Model $liker
     * @return boolean
     */
    public function likedBy(Model $liker)
    {
        return $this->likes()->make(['likeable' => $this])->liker()->associate($liker)->exists();
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
