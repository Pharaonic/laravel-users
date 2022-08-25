<?php

namespace Pharaonic\Laravel\Users\Traits\Actions\Vote;

use Illuminate\Contracts\Auth\Authenticatable;
use Pharaonic\Laravel\Users\Models\Actions\Vote;

trait isVotable
{
    /**
     * Bootstrap the trait.
     *
     * @return void
     */
    public static function bootIsVotable()
    {
        static::deleting(function ($model) {
            $model->votes()->delete();
        });
    }

    /**
     * Get Vote Object
     *
     * @param Authenticatable $voter
     * @return Vote|null
     */
    public function getVote(Authenticatable $voter)
    {
        return $this->votes()->where(['voter_id' => $voter->getKey(), 'voter_type' => get_class($voter)])->first();
    }

    /**
     * VoteUp with a Model
     *
     * @param Authenticatable $voter
     * @return boolean
     */
    public function voteUpBy(Authenticatable $voter)
    {
        if ($vote = $this->getVote($voter)) {
            return $vote->update(['vote' => true]);
        } else {
            return $this->votes()->make(['vote' => true])->voter()->associate($voter)->save();
        }
    }

    /**
     * VoteDown with a Model
     *
     * @param Authenticatable $voter
     * @return boolean
     */
    public function voteDownBy(Authenticatable $voter)
    {
        if ($vote = $this->getVote($voter)) {
            return $vote->update(['vote' => false]);
        } else {
            return $this->votes()->make(['vote' => false])->voter()->associate($voter)->save();
        }
    }

    /**
     * Unvote with a Model
     *
     * @param Authenticatable $voter
     * @return boolean
     */
    public function unVoteBy(Authenticatable $voter)
    {
        if ($vote = $this->getVote($voter))
            return $vote->delete();

        return false;
    }

    /**
     * Has Voted By Voter
     *
     * @param Authenticatable $voter
     * @return boolean
     */
    public function votedBy(Authenticatable $voter)
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
