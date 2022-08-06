<?php

namespace Pharaonic\Laravel\Users\Traits\Actions\Like;

use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Users\Models\Actions\Like;

trait isLiker
{
    /**
     * Like Model
     *
     * @param Model $likeable
     * @return boolean
     */
    public function like(Model $likeable)
    {
        if ($this->liked($likeable))
            return true;

        return $this->likes()->make()->likeable()->associate($likeable)->save();
    }

    /**
     * Unlike Model
     *
     * @param Model $likeable
     * @return boolean
     */
    public function unlike(Model $likeable)
    {
        if ($like = $this->likes()->where(['likeable_id' => $likeable->getKey(), 'likeable_type' => get_class($likeable)])->first())
            return $like->delete();

        return false;
    }

    /**
     * Has Liked By Liker
     *
     * @param Model $likeable
     * @return boolean
     */
    public function liked(Model $likeable)
    {
        return $this->likes()->where(['likeable_id' => $likeable->getKey(), 'likeable_type' => get_class($likeable)])->exists();
    }

    /**
     * liker List
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'liker');
    }
}
