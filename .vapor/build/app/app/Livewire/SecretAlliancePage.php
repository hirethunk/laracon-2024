<?php

namespace App\Livewire;

use App\Events\PlayerAssignedAlly;
use App\Events\PlayerEnteredAllianceCode;
use App\Events\PlayerPlayedPrisonersDilemma;
use App\Models\Game;
use App\Models\Player;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Thunk\Verbs\Facades\Verbs;

class SecretAlliancePage extends Component
{
    public string $code;

    #[Computed]
    public function user(): User
    {
        return Auth::user();
    }

    #[Computed]
    public function player(): Player
    {
        return $this->user->currentPlayer();
    }

    #[Computed]
    public function game(): Game
    {
        return $this->user->currentGame();
    }

    #[Computed]
    public function ally()
    {
        $ally = $this->player->state()->ally();

        if ($ally) {
            return $ally;
        }

        $ally = $this->game->state()->players()
            ->filter(function ($player) {
                return $player->id !== $this->player->id
                    && ! $player->ally()
                    && $player->is_active;
            })
            ->shuffle()
            ->first();

        if (! $ally) {
            return null;
        }

        PlayerAssignedAlly::fire(
            player_id: $this->player->id,
            game_id: $this->game->id,
            ally_id: $ally->id,
        );

        PlayerAssignedAlly::fire(
            player_id: $ally->id,
            game_id: $this->game->id,
            ally_id: $this->player->id,
        );

        Verbs::commit();

        return $this->player->state()->ally();
    }

    public function connectWithAlly()
    {
        if ((int) $this->code !== $this->ally->code_to_give_to_ally) {
            session()->flash('error', 'Invalid code.');

            return;
        }

        PlayerEnteredAllianceCode::fire(
            player_id: $this->player->id,
            game_id: $this->game->id,
            ally_id: $this->ally->id,
            alliance_code: (int) $this->code,
        );
    }

    public function playNice()
    {
        PlayerPlayedPrisonersDilemma::fire(
            player_id: $this->player->id,
            game_id: $this->game->id,
            nice_or_nasty: 'nice',
            ally_id: $this->ally->id,
        );
    }

    public function playNasty()
    {
        PlayerPlayedPrisonersDilemma::fire(
            player_id: $this->player->id,
            game_id: $this->game->id,
            nice_or_nasty: 'nasty',
            ally_id: $this->ally->id,
        );
    }

    public function render()
    {
        return view('livewire.secret-alliance-page')->layout('layouts.app');
    }
}
