<?php

namespace Pharaonic\Laravel\Users\Traits\Actions\Follow;

use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Users\Models\Actions\Follow;

trait isFollower
{
    /**
     * Follow Model
     *
     * @param Model $followable
     * @return boolean
     */
    public function follow(Model $followable)
    {
        if ($this->followed($followable))
            return true;

        return $this->follows()->make(['follower' => $this])->followable()->associate($followable)->save();
    }

    /**
     * Unfollow Model
     *
     * @param Model $followable
     * @return boolean
     */
    public function unfollow(Model $followable)
    {
        if ($follow = $this->follows()->where(['followable_id' => $followable->getKey(), 'followable_type' => get_class($followable)])->first())
            return $follow->delete();

        return false;
    }

    /**
     * Has Followd By follower
     *
     * @param Model $followable
     * @return boolean
     */
    public function followed(Model $followable)
    {
        return $this->follows()->where(['followable_id' => $followable->getKey(), 'followable_type' => get_class($followable)])->exists();
    }

    /**
     * Follows List
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function follows()
    {
        return $this->morphMany(Follow::class, 'follower');
    }
}
