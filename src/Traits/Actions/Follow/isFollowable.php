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
        $query = $this->follows()->make(['followable' => $this])->follower()->associate($follower);
        return $query->exists() ? true : $query->save();
    }

    /**
     * Unfollow with a Model
     *
     * @param Model follower
     * @return boolean
     */
    public function unFollowBy(Model $follower)
    {
        if ($follower = $this->follows()->make(['followable' => $this])->follower()->associate($follower)->first())
            return $follower->delete();

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
        return $this->follows()->make(['followable' => $this])->follower()->associate($follower)->exists();
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
