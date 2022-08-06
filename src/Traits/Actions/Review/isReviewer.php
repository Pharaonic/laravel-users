<?php

namespace Pharaonic\Laravel\Users\Traits\Actions\Review;

use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Users\Models\Actions\Review;

trait isReviewer
{
    /**
     * Get Review Object
     *
     * @param Model $reviewable
     * @return Vote|null
     */
    public function getReview(Model $reviewable)
    {
        return $this->reviews()->where(['reviewable_id' => $reviewable->getKey(), 'reviewable_type' => get_class($reviewable)])->first();
    }

    /**
     * Review Model
     *
     * @param Model $reviewable
     * @param float $rate
     * @param string|null $comment
     * @return boolean
     */
    public function review(Model $reviewable, float $rate = 0, string $comment = null)
    {
        if ($review = $this->getReview($reviewable)) {
            return $review->update([
                'rate'      => $rate,
                'comment'   => $comment
            ]);
        } else {
            return $this->reviews()->make([
                'rate'      => $rate,
                'comment'   => $comment,
            ])->reviewable()->associate($reviewable)->save();
        }
    }

    /**
     * UnReview Model
     *
     * @param Model $reviewable
     * @return boolean
     */
    public function unReview(Model $reviewable)
    {
        if ($review = $this->getReview($reviewable))
            return $review->delete();

        return false;
    }

    /**
     * Has Reviewed By Reviewer
     *
     * @param Model $reviewable
     * @return boolean
     */
    public function reviewed(Model $reviewable)
    {
        return $this->reviews()->where(['reviewable_id' => $reviewable->getKey(), 'reviewable_type' => get_class($reviewable)])->exists();
    }

    /**
     * Reviews List
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewer');
    }
}
