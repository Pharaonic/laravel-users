<?php

namespace Pharaonic\Laravel\Users\Traits\Actions\Bookmark;

use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Users\Models\Actions\Bookmark;

trait isBookmaker
{
    /**
     * Bookmark Model
     *
     * @param Model $bookmarkable
     * @param array|null $data
     * @return boolean
     */
    public function bookmark(Model $bookmarkable, array $data = null)
    {
        $query = $this->bookmarks()->make(['bookmarker' => $this, 'data' => $data])->bookmarkable()->associate($bookmarkable);
        return ($bookmark = $query->first()) ? $bookmark->update(['data' => $data]) : $query->save();
    }

    /**
     * Unbookmark Model
     *
     * @param Model $bookmarkable
     * @return boolean
     */
    public function unbookmark(Model $bookmarkable)
    {
        if ($bookmarkable = $this->bookmarks()->make(['bookmarker' => $this])->bookmarkable()->associate($bookmarkable)->first())
            return $bookmarkable->delete();

        return false;
    }

    /**
     * Has Bookmarked By Bookmarker
     *
     * @param Model $bookmarkable
     * @return boolean
     */
    public function bookmarked(Model $bookmarkable)
    {
        return $this->bookmarks()->make(['bookmarker' => $this])->bookmarkable()->associate($bookmarkable)->exists();
    }

    /**
     * Bookmarks List
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function bookmarks()
    {
        return $this->morphMany(Bookmark::class, 'bookmarker');
    }
}
