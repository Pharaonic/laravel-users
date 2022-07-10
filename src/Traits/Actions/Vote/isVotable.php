<?php

namespace Pharaonic\Laravel\Users\Traits\Actions\Vote;

use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Users\Models\Actions\Vote;

trait isVotable
{
    /**
     * Get Vote Object
     *
     * @param Model $voter
     * @return Vote|null
     */
    public function getVote(Model $voter)
    {
        return $this->votes()->where(['voter_id' => $voter->getKey(), 'voter_type' => get_class($voter)])->first();
    }

    /**
     * VoteUp with a Model
     *
     * @param Model $voter
     * @return boolean
     */
    public function voteUpBy(Model $voter)
    {
        if ($vote = $this->getVote($voter)) {
            return $vote->update(['vote' => true]);
        } else {
            return $this->votes()->make(['votable' => $this, 'vote' => true])->voter()->associate($voter)->save();
        }
    }

    /**
     * VoteDown with a Model
     *
     * @param Model $voter
     * @return boolean
     */
    public function voteDownBy(Model $voter)
    {
        if ($vote = $this->getVote($voter)) {
            return $vote->update(['vote' => false]);
        } else {
            return $this->votes()->make(['votable' => $this, 'vote' => false])->voter()->associate($voter)->save();
        }
    }

    /**
     * Unvote with a Model
     *
     * @param Model $voter
     * @return boolean
     */
    public function unVoteBy(Model $voter)
    {
        if ($vote = $this->getVote($voter))
            return $vote->delete();

        return false;
    }

    /**
     * Has Voted By Voter
     *
     * @param Model $voter
     * @return boolean
     */
    public function votedBy(Model $voter)
    {
        return $this->votes()->where(['voter_id' => $voter->getKey(), 'voter_type' => get_class($voter)])->exists();
    }

    /**
     * Votes List
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function votes()
    {
        return $this->morphMany(Vote::class, 'votable');
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
