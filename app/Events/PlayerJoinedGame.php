<?php

namespace App\Events;

use App\Events\Concerns\HasGame;
use App\Events\Concerns\HasPlayer;
use App\Events\Concerns\HasUser;
use App\Models\Player;
use App\Models\User;
use App\States\GameState;
use App\States\PlayerState;
use App\States\UserState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class PlayerJoinedGame extends Event
{
	use HasUser;
	use HasGame;
	use HasPlayer;

    public function applyToUser(UserState $user)
    {
        $user->current_player_id = $this->player_id;
    }

    public function applyToGame(GameState $game)
    {
        $game->user_ids_awaiting_approval = $game->user_ids_awaiting_approval
            ->reject(fn ($id) => $id === $this->user_id);

        $game->user_ids_approved->push($this->user_id);

        $game->player_ids->push($this->player_id);
    }

    public function applyToPlayer(PlayerState $player)
    {
        $player->user_id = $this->user_id;
        $player->game_id = $this->game_id;
        $player->name = $this->user()->name;
        $player->upvotes = [];
        $player->downvotes = [];
        $player->ballots_cast = [];
        $player->is_active = true;
        $player->is_immune_until = now();
    }

    public function fired()
    {
        $referrer = $this->user()->referrer_player_id;

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

            if ($this->game()->activeModifier()['slug'] === 'signing-bonus') {
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
