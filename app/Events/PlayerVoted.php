<?php

namespace App\Events;

use App\States\GameState;
use App\States\PlayerState;
use Thunk\Verbs\Event;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;

class PlayerVoted extends Event
{
    #[StateId(PlayerState::class)]
    public int $player_id;

    public int $upvotee_id;

    public int $downvotee_id;

    #[StateId(GameState::class)]
    public int $game_id;

    public function authorize()
    {
        $this->assert(
            $this->state(GameState::class)->player_ids->contains($this->player_id),
            'Voter is not in the game.'
        );

        $last_voted_at = $this->state(PlayerState::class)->lastVotedAt();

        $this->assert(
            ! $last_voted_at || now() > $last_voted_at->addHour(),
            'Voter must wait 1 hour between votes.'
        );
    }

    public function validate()
    {
        $this->assert(
            $this->upvotee_id !== $this->player_id && $this->downvotee_id !== $this->player_id,
            'Cannot vote for yourself.'
        );

        $players = $this->state(GameState::class)->player_ids;

        $this->assert(
            $players->contains($this->upvotee_id),
            'Upvotee is not in the game.'
        );

        $this->assert(
            $players->contains($this->downvotee_id),
            'Downvotee is not in the game.'
        );
    }

    public function applyToPlayer(PlayerState $state)
    {
        $state->ballots_cast->push([
            'upvotee_id' => $this->upvotee_id,
            'downvotee_id' => $this->downvotee_id,
            'voted_at' => now(),
        ]);
    }

    public function fired()
    {
        PlayerReceivedUpvote::fire(
            player_id: $this->upvotee_id,
            voter_id: $this->player_id,
            game_id: $this->game_id,
        );
        
        PlayerReceivedDownvote::fire(
            player_id: $this->downvotee_id,
            voter_id: $this->player_id,
            game_id: $this->game_id,
        );
    }
}
