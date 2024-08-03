<?php

namespace App\Events;

use App\Events\Concerns\AffectsVotes;
use App\Events\Concerns\HasGame;
use App\Events\Concerns\HasPlayer;
use App\Events\Concerns\RequiresActiveGame;
use App\Models\Player;
use App\States\GameState;
use App\States\PlayerState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class PlayerVoted extends Event
{
    use AffectsVotes;
    use HasGame;
    use HasPlayer;
    use RequiresActiveGame;

    #[StateId(PlayerState::class, 'upvotee')]
    public int $upvotee_id;

    #[StateId(PlayerState::class, 'downvotee')]
    public int $downvotee_id;

    public function authorize()
    {
        // Unlimited voting while testing locally
        if (app()->isLocal()) {
            return;
        }

        $this->assert(
            assertion: $this->player()->canVote(),
            exception: 'Voter must wait 1 hour between votes.'
        );
    }

    public function validate()
    {
        $this->assert(
            assertion: $this->upvotee_id !== $this->player_id && $this->downvotee_id !== $this->player_id,
            exception: 'Cannot vote for yourself.'
        );

        $game = $this->game();
        $upvotee = $this->states()->get('upvotee');
        $downvotee = $this->states()->get('downvotee');

        $this->assert($game->isPlayer($upvotee), 'Upvotee is not in the game.');
        $this->assert($upvotee->is_active, 'Upvotee has already resigned.');
        $this->assert(! $upvotee->cannotBeUpvoted(), 'Upvotee is immune from upvotes.');

        $this->assert($game->isPlayer($downvotee), 'Downvotee is not in the game.');
        $this->assert($downvotee->is_active, 'Downvotee has already resigned.');
        $this->assert(! $downvotee->cannotBeDownvoted(), 'Downvotee is immune from downvotes.');
    }

    public function apply(GameState $game)
    {
        $this->player()->ballots_cast[] = [
            'upvotee_id' => $this->upvotee_id,
            'downvotee_id' => $this->downvotee_id,
            'voted_at' => now(),
        ];

        $amount = $game->hasActiveModifier('double-down') ? 2 : 1;

        $this->applyUpvoteToPlayer(
            $this->upvotee_id, $this->player_id, 'ballot', $amount
        );

        $this->applyDownvoteToPlayer(
            $this->downvotee_id, $this->player_id, 'ballot', $amount
        );
    }

    public function applyBuddySystem(GameState $game)
    {
        if (! $game->hasActiveModifier('buddy-system')) {
            return;
        }

        $modifier = $game->activeModifier();

        $buddy = $this->states()->get('upvotee');

        // TODO: The `mutual_vote_exists` and `buddy_reward_already_given feels like
        //       something that could be tracked in state

        $mutual_vote_exists = collect($buddy->ballots_cast)
            ->filter(fn ($ballot) => $ballot['upvotee_id'] === $this->player_id)
            ->filter(fn ($ballot) => $ballot['voted_at'] > $modifier['starts_at'])
            ->isNotEmpty();

        $buddy_reward_already_given = collect($buddy->upvotes)
            ->filter(fn ($upvote) => $upvote['source'] === $this->player_id)
            ->filter(fn ($upvote) => $upvote['type'] === 'buddy-system-reward')
            ->isNotEmpty();

        if ($mutual_vote_exists && ! $buddy_reward_already_given) {
            $this->applyUpvoteToPlayer(
                $this->upvotee_id, $this->player_id, 'buddy-system-reward', 2
            );

            $this->applyUpvoteToPlayer(
                $this->player_id, $this->upvotee_id, 'buddy-system-reward', 2
            );
        }
    }

    public function handle()
    {
        Player::find($this->player_id)->update(['last_voted_at' => now()]);
    }
}
