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
        $query = $this->follows()->make(['follower' => $this])->followable()->associate($followable);
        return $query->exists() ? true : $query->save();
    }

    /**
     * Unfollow Model
     *
     * @param Model $followable
     * @return boolean
     */
    public function unfollow(Model $followable)
    {
        if ($followable = $this->follows()->make(['follower' => $this])->followable()->associate($followable)->first())
            return $followable->delete();

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
        return $this->follows()->make(['follower' => $this])->followable()->associate($followable)->exists();
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
