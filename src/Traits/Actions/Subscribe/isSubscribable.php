<?php

namespace Pharaonic\Laravel\Users\Traits\Actions\Subscribe;

use Illuminate\Contracts\Auth\Authenticatable;
use Pharaonic\Laravel\Users\Models\Actions\Subscribe;

trait isSubscribable
{
    /**
     * Subscribe with a Model
     *
     * @param Authenticatable $subscriber
     * @return boolean
     */
    public function subscribeBy(Authenticatable $subscriber)
    {
        if ($this->subscribedBy($subscriber))
            return true;

        return $this->subscriptions()->make()->subscriber()->associate($subscriber)->save();
    }

    /**
     * Unsubscribe with a Model
     *
     * @param Authenticatable $subscriber
     * @return boolean
     */
    public function unSubscribeBy(Authenticatable $subscriber)
    {
        if ($subscription = $this->subscriptions()->where(['subscriber_id' => $subscriber->getKey(), 'subscriber_type' => get_class($subscriber)])->first())
            return $subscription->delete();

        return false;
    }

    /**
     * Has Subscribed By Subscriber
     *
     * @param Authenticatable $subscriber
     * @return boolean
     */
    public function subscribedBy(Authenticatable $subscriber)
    {
        return $this->subscriptions()->where(['subscriber_id' => $subscriber->getKey(), 'subscriber_type' => get_class($subscriber)])->exists();
    }

    /**
     * Subscriptions List
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function subscriptions()
    {
        return $this->morphMany(Subscribe::class, 'subscribable');
    }
}
