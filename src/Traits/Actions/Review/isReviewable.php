<?php

namespace Pharaonic\Laravel\Users\Traits\Actions\Review;

use Illuminate\Contracts\Auth\Authenticatable;
use Pharaonic\Laravel\Users\Models\Actions\Review;

trait isReviewable
{
    /**
     * Get Review Object
     *
     * @param Authenticatable $reviewer
     * @return Vote|null
     */
    public function getReview(Authenticatable $reviewer)
    {
        return $this->reviews()->where(['reviewer_id' => $reviewer->getKey(), 'reviewer_type' => get_class($reviewer)])->first();
    }

    /**
     * Review Model By reviewer
     *
     * @param Authenticatable $reviewer
     * @param float $rate
     * @param string|null $comment
     * @return boolean
     */
    public function reviewBy(Authenticatable $reviewer, float $rate = 0, string $comment = null)
    {
        if ($review = $this->getReview($reviewer)) {
            return $review->update([
                'rate'      => $rate,
                'comment'   => $comment
            ]);
        } else {
            return $this->reviews()->make([
                'rate'      => $rate,
                'comment'   => $comment
            ])->reviewer()->associate($reviewer)->save();
        }
    }

    /**
     * UnReview Model By reviewer
     *
     * @param Authenticatable $reviewer
     * @return boolean
     */
    public function unReviewBy(Authenticatable $reviewer)
    {
        if ($review = $this->getReview($reviewer))
            return $review->delete();

        return false;
    }

    /**
     * Has Reviewed By reviewer
     *
     * @param Authenticatable $reviewer
     * @return boolean
     */
    public function reviewedBy(Authenticatable $reviewer)
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
