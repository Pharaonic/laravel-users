<?php

namespace Pharaonic\Laravel\Users\Traits\Actions\Review;

use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Users\Models\Actions\Review;

trait isReviewable
{
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
        $query = $this->reviews()->make([
            'reviewable' => $this,
            'rate'      => $rate,
            'comment'   => $comment
        ])->reviewer()->associate($reviewer);

        return ($review = $query->first()) ? $review->update([
            'rate'      => $rate,
            'comment'   => $comment
        ]) : $query->save();
    }

    /**
     * UnReview Model By reviewer
     *
     * @param Model $reviewer
     * @return boolean
     */
    public function unReviewBy(Model $reviewer)
    {
        if ($reviewer = $this->reviews()->make(['reviewable' => $this])->reviewer()->associate($reviewer)->first())
            return $reviewer->delete();

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
        return $this->reviews()->make(['reviewable' => $this])->reviewer()->associate($reviewer)->exists();
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
