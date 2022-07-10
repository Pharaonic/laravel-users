<?php

namespace Pharaonic\Laravel\Users\Traits\Actions\Bookmark;

use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Users\Models\Actions\Bookmark;

trait isBookmarkable
{
    /**
     * Get Bookmark Object
     *
     * @param Model $bookmarker
     * @return Bookmark|null
     */
    public function getBookmark(Model $bookmarker)
    {
        return $this->bookmarks()->where(['bookmarker_id' => $bookmarker->getKey(), 'bookmarker_type' => get_class($bookmarker)])->first();
    }

    /**
     * Bookmark with a Model
     *
     * @param Model $bookmarker
     * @param array|null $data
     * @return boolean
     */
    public function bookmarkBy(Model $bookmarker, array $data = null)
    {
        if ($bookmark = $this->getBookmark($bookmarker)) {
            return $bookmark->update(['data' => $data]);
        } else {
            return $this->bookmarks()->make(['bookmarkable' => $this, 'data' => $data])->bookmarker()->associate($bookmarker)->save();
        }
    }

    /**
     * Unbookmark with a Model
     *
     * @param Model $bookmarker
     * @return boolean
     */
    public function unBookmarkBy(Model $bookmarker)
    {
        if ($bookmark = $this->getBookmark($bookmarker))
            return $bookmark->delete();

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
        return $this->bookmarks()->where(['bookmarker_id' => $bookmarker->getKey(), 'bookmarker_type' => get_class($bookmarker)])->exists();
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
