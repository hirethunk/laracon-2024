<?php

namespace App\Events;

use App\Models\Player;
use App\States\GameState;
use App\States\PlayerState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class PlayerVoted extends Event
{
    #[StateId(PlayerState::class, 'player')]
    public int $player_id;

    #[StateId(PlayerState::class, 'upvotee')]
    public int $upvotee_id;

    #[StateId(PlayerState::class, 'downvotee')]
    public int $downvotee_id;

    #[StateId(GameState::class)]
    public int $game_id;

    public function authorize(GameState $game, PlayerState $player)
    {
        $this->assert(
            $game->player_ids->contains($this->player_id),
            'Voter is not in the game.'
        );

        $this->assert(
            $game->ends_at > now(),
            'The game is over.'
        );

        if (app()->environment('production') || app()->environment('testing')) {
            // Unlimited voting while testing locally
            $this->assert(
                $player->canVote(),
                'Voter must wait 1 hour between votes.'
            );
        }
    }

    public function validate(GameState $game, PlayerState $upvotee, PlayerState $downvotee)
    {
        $this->assert(
            $this->upvotee_id !== $this->player_id && $this->downvotee_id !== $this->player_id,
            'Cannot vote for yourself.'
        );

        $this->assert(
            $upvotee->game_id === $this->game_id,
            'Upvotee is not in the game.'
        );

        $this->assert(
            $upvotee->is_active,
            'Upvotee has already resigned.'
        );

        $this->assert(
            ! $upvotee->cannotBeUpvoted(),
            'Upvotee is immune from upvotes.'
        );

        $this->assert(
            $downvotee->game_id === $this->game_id,
            'Downvotee is not in the game.'
        );

        $this->assert(
            $downvotee->is_active,
            'Downvotee has already resigned.'
        );

        $this->assert(
            ! $downvotee->cannotBeDownvoted(),
            'Downvotee is immune from downvotes.'
        );
    }

    public function applyToPlayer(PlayerState $player)
    {
        $player->ballots_cast[] = [
            'upvotee_id' => $this->upvotee_id,
            'downvotee_id' => $this->downvotee_id,
            'voted_at' => now(),
        ];
    }

    public function fired(GameState $game, PlayerState $upvotee)
    {
        $amount = $game->modifierIsActive('double-down') ? 2 : 1;

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

        $modifier = $game->activeModifier();

        if ($modifier['slug'] === 'buddy-system') {
            $buddy_system_started_at = $modifier['starts_at'];

            $mutual_vote_exists = collect($upvotee->ballots_cast)
                ->filter(fn ($b) => $b['upvotee_id'] === $this->player_id)
                ->filter(fn ($b) => $b['voted_at'] > $buddy_system_started_at)
                ->first();

            $buddy_reward_already_given = $upvotee->buddy_system_reward_received;

            if ($mutual_vote_exists && ! $buddy_reward_already_given) {
                PlayerReceivedUpvote::fire(
                    player_id: $upvotee->id,
                    voter_id: $this->player_id,
                    game_id: $this->game_id,
                    type: 'buddy-system-reward',
                    amount: 2,
                );

                PlayerReceivedUpvote::fire(
                    player_id: $this->player_id,
                    voter_id: $upvotee->id,
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
