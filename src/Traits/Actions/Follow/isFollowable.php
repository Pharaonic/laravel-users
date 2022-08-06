<?php

namespace Pharaonic\Laravel\Users\Traits\Actions\Follow;

use Illuminate\Contracts\Auth\Authenticatable;
use Pharaonic\Laravel\Users\Models\Actions\Follow;

trait isFollowable
{
    /**
     * Follow with a Model
     *
     * @param Authenticatable follower
     * @return boolean
     */
    public function followBy(Authenticatable $follower)
    {
        if ($this->followedBy($follower))
            return true;

        return $this->follows()->make(['followable' => $this])->follower()->associate($follower)->save();
    }

    /**
     * Un-follow with a Model
     *
     * @param Authenticatable follower
     * @return boolean
     */
    public function unFollowBy(Authenticatable $follower)
    {
        if ($follow = $this->follows()->where(['follower_id' => $follower->getKey(), 'follower_type' => get_class($follower)])->first())
            return $follow->delete();

        return false;
    }

    /**
     * Has Followed By Follower
     *
     * @param Authenticatable follower
     * @return boolean
     */
    public function followedBy(Authenticatable $follower)
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
