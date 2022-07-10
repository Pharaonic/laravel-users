<?php

namespace Pharaonic\Laravel\Users\Traits\Actions\Vote;

use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Users\Models\Actions\Vote;

trait isVoter
{
    /**
     * Get Vote Object
     *
     * @param Model $votable
     * @return Vote|null
     */
    public function getVote(Model $votable)
    {
        return $this->votes()->where(['votable_id' => $votable->getKey(), 'votable_type' => get_class($votable)])->first();
    }

    /**
     * VoteUp Model
     *
     * @param Model $votable
     * @return boolean
     */
    public function voteUp(Model $votable)
    {
        if ($vote = $this->getVote($votable)) {
            return $vote->update(['vote' => true]);
        } else {
            return $this->votes()->make(['voter' => $this, 'vote' => true])->votable()->associate($votable)->save();
        }
    }

    /**
     * VoteDown Model
     *
     * @param Model $votable
     * @return boolean
     */
    public function voteDown(Model $votable)
    {
        if ($vote = $this->getVote($votable)) {
            return $vote->update(['vote' => false]);
        } else {
            return $this->votes()->make(['voter' => $this, 'vote' => false])->votable()->associate($votable)->save();
        }
    }

    /**
     * Unvote Model
     *
     * @param Model $votable
     * @return boolean
     */
    public function unvote(Model $votable)
    {
        if ($vote = $this->getVote($votable))
            return $vote->delete();

        return false;
    }

    /**
     * Has Voted By Voter
     *
     * @param Model $votable
     * @return boolean
     */
    public function voted(Model $votable)
    {
        return $this->votes()->where(['votable_id' => $votable->getKey(), 'votable_type' => get_class($votable)])->exists();
    }

    /**
     * Votes List
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function votes()
    {
        return $this->morphMany(Vote::class, 'voter');
    }

    /**
     * Votes Up List
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function upVotes()
    {
        return $this->votes()->where('vote', true);
    }

    /**
     * Votes Down List
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function downVotes()
    {
        return $this->votes()->where('vote', false);
    }
}
