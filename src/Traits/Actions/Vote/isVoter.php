<?php

namespace Pharaonic\Laravel\Users\Traits\Actions\Vote;

use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Users\Models\Actions\Vote;

trait isVoter
{
    /**
     * VoteUp Model
     *
     * @param Model $votable
     * @return boolean
     */
    public function voteUp(Model $votable)
    {
        $query = $this->votes()->make(['voter' => $this, 'vote' => true])->votable()->associate($votable);
        return ($vote = $query->first()) ? $vote->update(['vote' => true]) : $query->save();
    }

    /**
     * VoteDown Model
     *
     * @param Model $votable
     * @return boolean
     */
    public function voteDown(Model $votable)
    {
        $query = $this->votes()->make(['voter' => $this, 'vote' => false])->votable()->associate($votable);
        return ($vote = $query->first()) ? $vote->update(['vote' => false]) : $query->save();
    }

    /**
     * Unvote Model
     *
     * @param Model $votable
     * @return boolean
     */
    public function unvote(Model $votable)
    {
        if ($votable = $this->votes()->make(['voter' => $this])->votable()->associate($votable)->first())
            return $votable->delete();

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
        return $this->votes()->make(['voter' => $this])->votable()->associate($votable)->exists();
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
