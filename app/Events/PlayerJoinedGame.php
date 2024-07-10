<?php

namespace App\Events;

use App\Models\Player;
use App\Models\User;
use App\States\GameState;
use App\States\PlayerState;
use App\States\UserState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

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
        $state->current_player_id = $this->player_id;
    }

    public function applyToGame(GameState $state)
    {
        $state->user_ids_awaiting_approval = $state->user_ids_awaiting_approval
            ->reject(fn ($id) => $id === $this->user_id);

        $state->user_ids_approved->push($this->user_id);

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
        $state->is_active = true;
        $state->is_immune_until = now();
    }

    public function fired()
    {
        $referrer = $this->state(UserState::class)->referrer_player_id;

        if ($referrer) {
            PlayerReceivedUpvote::fire(
                player_id: $referrer,
                game_id: $this->game_id,
                voter_id: $this->player_id,
                type: 'got-referred',
                amount: 1,
            );

            PlayerReceivedUpvote::fire(
                player_id: $this->player_id,
                game_id: $this->game_id,
                voter_id: $referrer,
                type: 'referred',
                amount: 1,
            );

            if ($this->state(GameState::class)->activeModifier()['slug'] === 'signing-bonus') {
                PlayerBecameImmune::fire(
                    player_id: $referrer,
                    game_id: $this->game_id,
                    type: 'signing-bonus',
                    is_immune_until: now()->addHours(1),
                );
            }
        }
    }

    public function handle()
    {
        Player::create([
            'id' => $this->player_id,
            'user_id' => $this->user_id,
            'game_id' => $this->game_id,
        ]);

        $user = User::find($this->user_id);

        $user->current_game_id = $this->game_id;

        $user->save();
    }
}
