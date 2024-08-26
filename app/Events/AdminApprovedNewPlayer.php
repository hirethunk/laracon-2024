<?php

namespace App\Events;

use App\States\GameState;
use App\States\PlayerState;
use App\States\UserState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class AdminApprovedNewPlayer extends Event
{
    #[StateId(UserState::class, 'admin')]
    public int $admin_id;

    #[StateId(UserState::class, 'user')]
    public int $user_id;

    #[StateId(GameState::class)]
    public int $game_id;

    #[StateId(PlayerState::class)]
    public ?int $player_id = null;

    public function authorize(UserState $admin)
    {
        $this->assert(
            $admin->is_admin_for->contains($this->game_id),
            'Only admins can approve new players.'
        );
    }

    public function validate(GameState $game)
    {
        $this->assert(
            ! $game->players()->map(fn ($p) => $p->user_id)->contains($this->user_id),
            'User is already in the game.'
        );

        $this->assert(
            $game->ends_at > now(),
            'The game is over.'
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

    // public function fired(GameState $game)
    // {
    //     if ($game->user_ids_approved->contains($this->user_id)) {
    //         return;
    //     }

    //     PlayerJoinedGame::fire(
    //         user_id: $this->user_id,
    //         game_id: $this->game_id,
    //         player_id: $this->player_id,
    //     );
    // }
}
