<?php

namespace Pharaonic\Laravel\Users\Traits\Actions\Review;

use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Users\Models\Actions\Review;

trait isReviewable
{
    /**
     * Get Review Object
     *
     * @param Model $reviewer
     * @return Vote|null
     */
    public function getReview(Model $reviewer)
    {
        return $this->reviews()->where(['reviewer_id' => $reviewer->getKey(), 'reviewer_type' => get_class($reviewer)])->first();
    }

    /**
     * Review Model By reviewer
     *
     * @param Model $reviewer
     * @param float $rate
     * @param string|null $comment
     * @return boolean
     */
    public function reviewBy(Model $reviewer, float $rate = 0, string $comment = null)
    {
        if ($review = $this->getReview($reviewer)) {
            return $review->update([
                'rate'      => $rate,
                'comment'   => $comment
            ]);
        } else {
            return $this->reviews()->make([
                'reviewable' => $this,
                'rate'      => $rate,
                'comment'   => $comment
            ])->reviewer()->associate($reviewer)->save();
        }
    }

    /**
     * UnReview Model By reviewer
     *
     * @param Model $reviewer
     * @return boolean
     */
    public function unReviewBy(Model $reviewer)
    {
        if ($review = $this->getReview($reviewer))
            return $review->delete();

        return false;
    }

    /**
     * Has Reviewed By reviewer
     *
     * @param Model $reviewer
     * @return boolean
     */
    public function reviewedBy(Model $reviewer)
    {
        return $this->reviews()->where(['reviewer_id' => $reviewer->getKey(), 'reviewer_type' => get_class($reviewer)])->exists();
    }

    /**
     * Reviews List
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    /**
     * Getting rating value
     *
     * @return float
     */
    public function getRatingAttribute()
    {
        if ($this->relationLoaded('reviews'))
            return (float) $this->reviews->avg('rate');

        return (float) $this->reviews()->avg('rate');
    }
}
