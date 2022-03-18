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
        $query = $this->subscriptions()->make(['subscribable' => $this])->subscriber()->associate($subscriber);
        return $query->exists() ? true : $query->save();
    }

    /**
     * Unsubscribe with a Model
     *
     * @param Model $subscriber
     * @return boolean
     */
    public function unSubscribeBy(Model $subscriber)
    {
        if ($subscriber = $this->subscriptions()->make(['subscribable' => $this])->subscriber()->associate($subscriber)->first())
            return $subscriber->delete();

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
        return $this->subscriptions()->make(['subscribable' => $this])->subscriber()->associate($subscriber)->exists();
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
