<?php

namespace Pharaonic\Laravel\Users\Traits\Actions\Favourite;

use Illuminate\Contracts\Auth\Authenticatable;
use Pharaonic\Laravel\Users\Models\Actions\Favourite;

trait isFavourable
{
    /**
     * Bootstrap the trait.
     *
     * @return void
     */
    public static function bootIsFavourable()
    {
        static::deleting(function ($model) {
            $model->favourites()->delete();
        });
    }

    /**
     * Favourite with a Model
     *
     * @param Authenticatable $favorer
     * @return boolean
     */
    public function favouriteBy(Authenticatable $favorer)
    {
        if ($this->favouredBy($favorer))
            return true;

        return $this->favourites()->make()->favorer()->associate($favorer)->save();
    }

    /**
     * Unfavourite with a Model
     *
     * @param Authenticatable $favorer
     * @return boolean
     */
    public function unFavouriteBy(Authenticatable $favorer)
    {
        if ($favourite = $this->favourites()->where(['favorer_id' => $favorer->getKey(), 'favorer_type' => get_class($favorer)])->first())
            return $favourite->delete();

        return false;
    }

    /**
     * Has Favoured By favour
     *
     * @param Authenticatable $favorer
     * @return boolean
     */
    public function favouredBy(Authenticatable $favorer)
    {
        return $this->favourites()->where(['favorer_id' => $favorer->getKey(), 'favorer_type' => get_class($favorer)])->exists();
    }

    /**
     * Favourites List
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function favourites()
    {
        return $this->morphMany(Favourite::class, 'favourable');
    }
}
