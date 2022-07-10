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
        if ($this->favouredBy($favorer))
            return true;

        return $this->favourites()->make(['favourable' => $this])->favorer()->associate($favorer)->save();
    }

    /**
     * Unfavourite with a Model
     *
     * @param Model $favorer
     * @return boolean
     */
    public function unFavouriteBy(Model $favorer)
    {
        if ($favourite = $this->favourites()->where(['favorer_id' => $favorer->getKey(), 'favorer_type' => get_class($favorer)])->first())
            return $favourite->delete();

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
