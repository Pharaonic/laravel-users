<?php

namespace Pharaonic\Laravel\Users\Traits\Actions\Favourite;

use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Users\Models\Actions\Favourite;

trait isFavorer
{
    /**
     * Bootstrap the trait.
     *
     * @return void
     */
    public static function bootIsFavorer()
    {
        static::deleting(function ($model) {
            $model->favourites()->delete();
        });
    }

    /**
     * Favourite Model
     *
     * @param Model $favourable
     * @return boolean
     */
    public function favourite(Model $favourable)
    {
        if ($this->favoured($favourable))
            return true;

        return $this->favourites()->make()->favourable()->associate($favourable)->save();
    }

    /**
     * Unfavourite Model
     *
     * @param Model $favourable
     * @return boolean
     */
    public function unfavourite(Model $favourable)
    {
        if ($favourite = $this->favourites()->where(['favourable_id' => $favourable->getKey(), 'favourable_type' => get_class($favourable)])->first())
            return $favourite->delete();

        return false;
    }

    /**
     * Has Favoured By Favour
     *
     * @param Model $favourable
     * @return boolean
     */
    public function favoured(Model $favourable)
    {
        return $this->favourites()->where(['favourable_id' => $favourable->getKey(), 'favourable_type' => get_class($favourable)])->exists();
    }

    /**
     * Favourites List
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function favourites()
    {
        return $this->morphMany(Favourite::class, 'favorer');
    }
}
