<?php

namespace Pharaonic\Laravel\Users\Traits\Actions\Review;

use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Users\Models\Actions\Review;

trait isReviewer
{
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
        $query = $this->reviews()->make([
            'reviewer'  => $this,
            'rate'      => $rate,
            'comment'   => $comment,
        ])->reviewable()->associate($reviewable);

        return ($review = $query->first()) ? $review->update([
            'rate'      => $rate,
            'comment'   => $comment
        ]) : $query->save();
    }

    /**
     * UnReview Model
     *
     * @param Model $reviewable
     * @return boolean
     */
    public function unReview(Model $reviewable)
    {
        if ($reviewable = $this->reviews()->make(['reviewer' => $this])->reviewable()->associate($reviewable)->first())
            return $reviewable->delete();

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
        return $this->reviews()->make(['reviewer' => $this])->reviewable()->associate($reviewable)->exists();
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
