<?php

namespace Pharaonic\Laravel\Users\Traits\Actions\Favourite;

use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Users\Models\Actions\Favourite;

trait isFavorer
{
    /**
     * Favourite Model
     *
     * @param Model $favourable
     * @return boolean
     */
    public function favourite(Model $favourable)
    {
        $query = $this->favourites()->make(['favorer' => $this])->favourable()->associate($favourable);
        return $query->exists() ? true : $query->save();
    }

    /**
     * Unfavourite Model
     *
     * @param Model $favourable
     * @return boolean
     */
    public function unfavourite(Model $favourable)
    {
        if ($favourable = $this->favourites()->make(['favorer' => $this])->favourable()->associate($favourable)->first())
            return $favourable->delete();

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
       return $this->favourites()->make(['favorer' => $this])->favourable()->associate($favourable)->exists();
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
