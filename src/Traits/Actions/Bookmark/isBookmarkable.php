<?php

namespace Pharaonic\Laravel\Users\Traits\Actions\Bookmark;

use Illuminate\Contracts\Auth\Authenticatable;
use Pharaonic\Laravel\Users\Models\Actions\Bookmark;

trait isBookmarkable
{
    /**
     * Get Bookmark Object
     *
     * @param Authenticatable $bookmarker
     * @return Bookmark|null
     */
    public function getBookmark(Authenticatable $bookmarker)
    {
        return $this->bookmarks()->where(['bookmarker_id' => $bookmarker->getKey(), 'bookmarker_type' => get_class($bookmarker)])->first();
    }

    /**
     * Bookmark with a Model
     *
     * @param Authenticatable $bookmarker
     * @param array|null $data
     * @return boolean
     */
    public function bookmarkBy(Authenticatable $bookmarker, array $data = null)
    {
        if ($bookmark = $this->getBookmark($bookmarker)) {
            return $bookmark->update(['data' => $data]);
        } else {
            return $this->bookmarks()->make(['bookmarkable' => $this, 'data' => $data])->bookmarker()->associate($bookmarker)->save();
        }
    }

    /**
     * Un-bookmark with a Model
     *
     * @param Authenticatable $bookmarker
     * @return boolean
     */
    public function unBookmarkBy(Authenticatable $bookmarker)
    {
        if ($bookmark = $this->getBookmark($bookmarker))
            return $bookmark->delete();

        return false;
    }

    /**
     * Has Bookmarked By Bookmarker
     *
     * @param Authenticatable $bookmarker
     * @return boolean
     */
    public function bookmarkedBy(Authenticatable $bookmarker)
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
