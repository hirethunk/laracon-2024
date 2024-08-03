<?php

namespace App\Events;

use App\Events\Concerns\HasGame;
use App\Events\Concerns\HasPlayer;
use App\Events\Concerns\RequiresActiveGame;
use App\Models\Player;
use App\States\PlayerState;
use Thunk\Verbs\Event;

class PlayerVoted extends Event
{
	use HasGame;
	use HasPlayer;
	use RequiresActiveGame;

    public int $upvotee_id;

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

    public function applyToPlayer(PlayerState $player)
    {
        $player->ballots_cast[] = [
            'upvotee_id' => $this->upvotee_id,
            'downvotee_id' => $this->downvotee_id,
            'voted_at' => now(),
        ];
    }

    public function fired()
    {
        $amount = $this->game()->activeModifier()['slug'] === 'double-down'
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

        $modifier = $this->game()->activeModifier();

        if ($modifier['slug'] === 'buddy-system') {
            $buddy_system_started_at = $modifier['starts_at'];

            $buddy = PlayerState::load($this->upvotee_id);

            $mutual_vote_exists = collect($buddy->ballots_cast)
                ->filter(fn ($b) => $b['upvotee_id'] === $this->player_id)
                ->filter(fn ($b) => $b['voted_at'] > $buddy_system_started_at)
                ->first();

            $buddy_reward_already_given = collect($buddy->upvotes)
                ->filter(fn ($u) => $u['source'] === $this->player_id)
                ->filter(fn ($u) => $u['type'] === 'buddy-system-reward')
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
