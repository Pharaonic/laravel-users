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
        $query = $this->subscriptions()->make(['subscriber' => $this])->subscribable()->associate($subscribable);
        return $query->exists() ? true : $query->save();
    }

    /**
     * Unsubscribe Model
     *
     * @param Model $subscribable
     * @return boolean
     */
    public function unsubscribe(Model $subscribable)
    {
        if ($subscriber = $this->subscriptions()->make(['subscriber' => $this])->subscribable()->associate($subscribable)->first())
            return $subscriber->delete();

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
        return $this->subscriptions()->make(['subscriber' => $this])->subscribable()->associate($subscribable)->exists();
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
