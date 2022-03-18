<?php

namespace Pharaonic\Laravel\Users\Traits\Actions\Vote;

use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Users\Models\Actions\Vote;

trait isVotable
{
    /**
     * VoteUp with a Model
     *
     * @param Model $voter
     * @return boolean
     */
    public function voteUpBy(Model $voter)
    {
        $query = $this->votes()->make(['votable' => $this, 'vote' => true])->voter()->associate($voter);
        return ($vote = $query->first()) ? $vote->update(['vote' => true]) : $query->save();
    }

    /**
     * VoteDown with a Model
     *
     * @param Model $voter
     * @return boolean
     */
    public function voteDownBy(Model $voter)
    {
        $query = $this->votes()->make(['votable' => $this, 'vote' => false])->voter()->associate($voter);
        return ($vote = $query->first()) ? $vote->update(['vote' => false]) : $query->save();
    }

    /**
     * Unvote with a Model
     *
     * @param Model $voter
     * @return boolean
     */
    public function unVoteBy(Model $voter)
    {
        if ($voter = $this->votes()->make(['votable' => $this])->voter()->associate($voter)->first())
            return $voter->delete();

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
        return $this->votes()->make(['votable' => $this])->voter()->associate($voter)->exists();
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
