<?php

namespace Pharaonic\Laravel\Users\Traits\Actions\Bookmark;

use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Users\Models\Actions\Bookmark;

trait isBookmaker
{
    /**
     * Get Bookmark Object
     *
     * @param Model $bookmarkable
     * @return Bookmark|null
     */
    public function getBookmark(Model $bookmarkable)
    {
        return $this->bookmarks()->where(['bookmarkable_id' => $bookmarkable->getKey(), 'bookmarkable_type' => get_class($bookmarkable)])->first();
    }

    /**
     * Bookmark Model
     *
     * @param Model $bookmarkable
     * @param array|null $data
     * @return boolean
     */
    public function bookmark(Model $bookmarkable, array $data = null)
    {
        if ($bookmark = $this->getBookmark($bookmarkable)) {
            return $bookmark->update(['data' => $data]);
        } else {
            return $this->bookmarks()->make(['data' => $data])->bookmarkable()->associate($bookmarkable)->save();
        }
    }

    /**
     * Unbookmark Model
     *
     * @param Model $bookmarkable
     * @return boolean
     */
    public function unbookmark(Model $bookmarkable)
    {
        if ($bookmark = $this->getBookmark($bookmarkable))
            return $bookmark->delete();

        return false;
    }

    /**
     * Has Bookmarked By This User
     *
     * @param Model $bookmarkable
     * @return boolean
     */
    public function bookmarked(Model $bookmarkable)
    {
        return $this->bookmarks()->where(['bookmarkable_id' => $bookmarkable->getKey(), 'bookmarkable_type' => get_class($bookmarkable)])->exists();
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
