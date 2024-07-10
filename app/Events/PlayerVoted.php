<?php

namespace App\Events;

use App\Models\Player;
use Thunk\Verbs\Event;
use App\States\GameState;
use App\States\PlayerState;
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
            GameState::load($this->game_id)->player_ids->contains($this->player_id),
            'Voter is not in the game.'
        );

        $this->assert(
            GameState::load($this->game_id)->is_active,
            'The game is over.'
        );

        if (app()->environment('production') || app()->environment('testing')) {
            // Unlimited voting while testing locally
                $this->assert(
                    $this->state(PlayerState::class)->canVote(),
                    'Voter must wait 1 hour between votes.'
                );
            }
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
            PlayerState::load($this->upvotee_id)->is_active,
            'Upvotee has already resigned.'
        );

        $this->assert(
            $players->contains($this->downvotee_id),
            'Downvotee is not in the game.'
        );

        $this->assert(
            PlayerState::load($this->downvotee_id)->is_active,
            'Downvotee has already resigned.'
        );

        $this->assert(
            ! PlayerState::load($this->downvotee_id)->cannotBeDownvoted(),
            'Downvotee is immune from downvotes.'
        );

        $this->assert(
            ! PlayerState::load($this->upvotee_id)->cannotBeUpvoted(),
            'Upvotee is immune from upvotes.'
        );
    }

    public function applyToPlayer(PlayerState $state)
    {
        $state->ballots_cast[] = [
            'upvotee_id' => $this->upvotee_id,
            'downvotee_id' => $this->downvotee_id,
            'voted_at' => now(),
        ];
    }

    public function fired()
    {
        $amount = $this->state(GameState::class)->activeModifier()['slug'] === 'double-down' 
            ? 2 
            : 1;

        PlayerReceivedUpvote::fire(
            player_id: $this->upvotee_id,
            voter_id: $this->player_id,
            game_id: $this->game_id,
            type: 'ballot',
            amount: $amount,
        );

        PlayerReceivedDownvote::fire(
            player_id: $this->downvotee_id,
            voter_id: $this->player_id,
            game_id: $this->game_id,
            type: 'ballot',
            amount: $amount,
        );

        $modifier = $this->state(GameState::class)->activeModifier();

        if ($modifier['slug'] === 'buddy-system') {
            $buddy_system_started_at = $modifier['starts_at'];

            $buddy = PlayerState::load($this->upvotee_id);

            $mutual_vote_exists = collect($buddy->ballots_cast)
                ->filter(fn($b) => $b['upvotee_id'] === $this->player_id)
                ->filter(fn($b) => $b['voted_at'] > $buddy_system_started_at)
                ->first();

            $buddy_reward_already_given = collect($buddy->upvotes)
                ->filter(fn($u) => $u['source'] === $this->player_id)
                ->filter(fn($u) => $u['type'] === 'buddy-system-reward')
                ->first();

            if ($mutual_vote_exists && ! $buddy_reward_already_given) {
                PlayerReceivedUpvote::fire(
                    player_id: $buddy->id,
                    voter_id: $this->player_id,
                    game_id: $this->game_id,
                    type: 'buddy-system-reward',
                    amount: 2,
                );

                PlayerReceivedUpvote::fire(
                    player_id: $this->player_id,
                    voter_id: $buddy->id,
                    game_id: $this->game_id,
                    type: 'buddy-system-reward',
                    amount: 2,
                );
            }
        }
    }

    public function handle()
    {
        $player = Player::find($this->player_id);

        $player->last_voted_at = now();

        $player->save();
    }
}
