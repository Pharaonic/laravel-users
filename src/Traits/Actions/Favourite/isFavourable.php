<?php

namespace Pharaonic\Laravel\Users\Traits\Actions\Favourite;

use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Users\Models\Actions\Favourite;

trait isFavourable
{
    /**
     * Favourite with a Model
     *
     * @param Model $favorer
     * @return boolean
     */
    public function favouriteBy(Model $favorer)
    {
       $query = $this->favourites()->make(['favourable' => $this])->favorer()->associate($favorer);
        return $query->exists() ? true : $query->save();
    }

    /**
     * Unfavourite with a Model
     *
     * @param Model $favorer
     * @return boolean
     */
    public function unFavouriteBy(Model $favorer)
    {
        if ($favorer = $this->favourites()->make(['favourable' => $this])->favorer()->associate($favorer)->first())
            return $favorer->delete();

        return false;
    }

    /**
     * Has Favoured By favour
     *
     * @param Model $favorer
     * @return boolean
     */
    public function favouredBy(Model $favorer)
    {
        return $this->favourites()->make(['favourable' => $this])->favorer()->associate($favorer)->exists();
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
