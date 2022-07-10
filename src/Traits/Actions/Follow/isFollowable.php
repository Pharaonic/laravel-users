<?php

namespace Pharaonic\Laravel\Users\Traits\Actions\Follow;

use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Users\Models\Actions\Follow;

trait isFollowable
{
    /**
     * Follow with a Model
     *
     * @param Model follower
     * @return boolean
     */
    public function followBy(Model $follower)
    {
        if ($this->followedBy($follower))
            return true;

        return $this->follows()->make(['followable' => $this])->follower()->associate($follower)->save();
    }

    /**
     * Unfollow with a Model
     *
     * @param Model follower
     * @return boolean
     */
    public function unFollowBy(Model $follower)
    {
        if ($follow = $this->follows()->where(['follower_id' => $follower->getKey(), 'follower_type' => get_class($follower)])->first())
            return $follow->delete();

        return false;
    }

    /**
     * Has Followed By Follower
     *
     * @param Model follower
     * @return boolean
     */
    public function followedBy(Model $follower)
    {
        return $this->follows()->where(['follower_id' => $follower->getKey(), 'follower_type' => get_class($follower)])->exists();
    }

    /**
     * Follows List
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function follows()
    {
        return $this->morphMany(Follow::class, 'followable');
    }
}
