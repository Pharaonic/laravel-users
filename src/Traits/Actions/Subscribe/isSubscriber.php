<?php

namespace Pharaonic\Laravel\Users\Traits\Actions\Subscribe;

use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Users\Models\Actions\Subscribe;

trait isSubscriber
{
    /**
     * Subscribe Model
     *
     * @param Model $subscribable
     * @return boolean
     */
    public function subscribe(Model $subscribable)
    {
        if ($this->subscribed($subscribable))
            return true;

        return $this->subscriptions()->make()->subscribable()->associate($subscribable)->save();
    }

    /**
     * Unsubscribe Model
     *
     * @param Model $subscribable
     * @return boolean
     */
    public function unsubscribe(Model $subscribable)
    {
        if ($subscription = $this->subscriptions()->where(['subscribable_id' => $subscribable->getKey(), 'subscribable_type' => get_class($subscribable)])->first())
            return $subscription->delete();

        return false;
    }

    /**
     * Has Subscribed By Subscriber
     *
     * @param Model $subscribable
     * @return boolean
     */
    public function subscribed(Model $subscribable)
    {
        return $this->subscriptions()->where(['subscribable_id' => $subscribable->getKey(), 'subscribable_type' => get_class($subscribable)])->exists();
    }

    /**
     * Subscriptions List
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function subscriptions()
    {
        return $this->morphMany(Subscribe::class, 'subscriber');
    }
}
