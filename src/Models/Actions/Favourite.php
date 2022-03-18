<?php

namespace Pharaonic\Laravel\Users\Models\Actions;

use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'favourable_type', 'favourable_id',
        'favorer_type', 'favorer_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function favourable()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function favorer()
    {
        return $this->morphTo();
    }
}
