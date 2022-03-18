<?php

namespace Pharaonic\Laravel\Users\Traits\Actions\Bookmark;

use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Users\Models\Actions\Bookmark;

trait isBookmarkable
{
    /**
     * Bookmark with a Model
     *
     * @param Model $bookmarker
     * @param array|null $data
     * @return boolean
     */
    public function bookmarkBy(Model $bookmarker, array $data = null)
    {
        $query = $this->bookmarks()->make(['bookmarkable' => $this, 'data' => $data])->bookmarker()->associate($bookmarker);
        return ($bookmark = $query->first()) ? $bookmark->update(['data' => $data]) : $query->save();
    }

    /**
     * Unbookmark with a Model
     *
     * @param Model $bookmarker
     * @return boolean
     */
    public function unBookmarkBy(Model $bookmarker)
    {
        if ($bookmarker = $this->bookmarks()->make(['bookmarkable' => $this])->bookmarker()->associate($bookmarker)->first())
            return $bookmarker->delete();

        return false;
    }

    /**
     * Has Bookmarked By Bookmarker
     *
     * @param Model $bookmarker
     * @return boolean
     */
    public function bookmarkedBy(Model $bookmarker)
    {
        return $this->bookmarks()->make(['bookmarkable' => $this])->bookmarker()->associate($bookmarker)->exists();
    }

    /**
     * Bookmarks List
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function bookmarks()
    {
        return $this->morphMany(Bookmark::class, 'bookmarkable');
    }
}
