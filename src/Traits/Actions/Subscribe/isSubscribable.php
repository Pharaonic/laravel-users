<?php

namespace Pharaonic\Laravel\Users\Traits\Actions\Subscribe;

use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Users\Models\Actions\Subscribe;

trait isSubscribable
{
    /**
     * Subscribe with a Model
     *
     * @param Model $subscriber
     * @return boolean
     */
    public function subscribeBy(Model $subscriber)
    {
        if ($this->subscribedBy($subscriber))
            return true;

        return $this->subscriptions()->make(['subscribable' => $this])->subscriber()->associate($subscriber)->save();
    }

    /**
     * Unsubscribe with a Model
     *
     * @param Model $subscriber
     * @return boolean
     */
    public function unSubscribeBy(Model $subscriber)
    {
        if ($subscription = $this->subscriptions()->where(['subscriber_id' => $subscriber->getKey(), 'subscriber_type' => get_class($subscriber)])->first())
            return $subscription->delete();

        return false;
    }

    /**
     * Has Subscribed By Subscriber
     *
     * @param Model $subscriber
     * @return boolean
     */
    public function subscribedBy(Model $subscriber)
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
