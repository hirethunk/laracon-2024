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
    public ?int $player_id = null;

    public function validate(UserState $user)
    {
        $this->assert(
            $user->current_game_id !== $this->game_id,
            'User is already in the game.'
        );
    }

    public function applyToUser(UserState $user)
    {
        $user->current_game_id = $this->game_id;

        $user->current_player_id = $this->player_id;
    }

    public function applyToGame(GameState $game)
    {
        $game->player_ids->push($this->player_id);

        // @todo - figure out why this is happening 3 times on replay lmao.
        $game->player_ids = $game->player_ids->unique();
    }

    public function applyToPlayer(PlayerState $player, UserState $user)
    {
        $player->user_id = $this->user_id;
        $player->game_id = $this->game_id;
        $player->name = $user->name;
        $player->ballots_cast = [];
        $player->is_active = true;
        $player->is_immune_until = now();
        $player->has_connected_with_ally = false;
        $player->prisoners_dilemma_choice = '';
        $player->code_to_give_to_ally = rand(1000, 9999);
        $player->can_submit_code_at = now();
    }

    public function fired(UserState $user, GameState $game)
    {
        $referrer = $user->referrer_player_id;

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

            if ($game->modifierIsActive('signing-bonus')) {
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
        Player::updateOrCreate(
            [
                'id' => $this->player_id,
            ],
            [
                'user_id' => $this->user_id,
                'game_id' => $this->game_id,
            ]
        );

        User::find($this->user_id)->update([
            'current_game_id' => $this->game_id
        ]);
    }
}
