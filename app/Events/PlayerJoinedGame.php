<?php

namespace App\Events;

use App\Models\Player;
use Thunk\Verbs\Event;
use App\States\GameState;
use App\States\UserState;
use App\States\PlayerState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;

class PlayerJoinedGame extends Event
{
    #[StateId(UserState::class)]
    public int $user_id;

    #[StateId(GameState::class)]
    public int $game_id;

    #[StateId(PlayerState::class)]
    public int $player_id;

    public function applyToUser(UserState $state)
    {
        $state->status = 'approved';

        $state->player_id = $this->player_id;
    }

    public function applyToGame(GameState $state)
    {
        $state->user_ids_awaiting_approval = $state->user_ids_awaiting_approval
            ->reject(fn ($id) => $id === $this->user_id);

        $state->player_ids->push($this->player_id);
    }

    public function applyToPlayer(PlayerState $state)
    {
        $state->user_id = $this->user_id;
        $state->game_id = $this->game_id;
        $state->name = $this->state(UserState::class)->name;
        $state->upvotes = [];
        $state->downvotes = [];
        $state->ballots_cast = [];
    }

    public function fired()
    {
        $referrer = $this->state(UserState::class)->referrer_player_id;

        if ($referrer) {
            PlayerReceivedUpvote::fire(
                player_id: $referrer,
                game_id: $this->game_id,
                voter_id: $this->player_id,
            );

            PlayerReceivedUpvote::fire(
                player_id: $this->player_id,
                game_id: $this->game_id,
                voter_id: $referrer,
            );
        }
    }

    public function handle()
    {
        Player::create([
            'id' => $this->player_id,
            'user_id' => $this->user_id,
            'game_id' => $this->game_id,
        ]);
    }
}
