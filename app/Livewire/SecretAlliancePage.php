<?php

namespace App\Livewire;

use App\Models\Game;
use App\Models\User;
use App\Models\Player;
use Livewire\Component;
use Thunk\Verbs\Facades\Verbs;
use Livewire\Attributes\Computed;
use App\Events\PlayerAssignedAlly;
use Illuminate\Support\Facades\Auth;
use App\Events\PlayerEnteredAllianceCode;
use App\Events\PlayerPlayedPrisonersDilemma;
use App\Events\PlayerPlayerPrisonersDilemma;

class SecretAlliancePage extends Component
{
    public int $code;

    public $message = '';

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

        $ally_id = $this->game->state()->players()
            ->filter(function ($player) {
                return $player->id !== $this->player->id
                    && ! $player->ally()
                    && $player->is_active;
            })
            ->random()
            ->id;
        
        PlayerAssignedAlly::fire(
            player_id: $this->player->id,
            game_id: $this->game->id,
            ally_id: $ally_id,
        );

        PlayerAssignedAlly::fire(
            player_id: $ally_id,
            game_id: $this->game->id,
            ally_id: $this->player->id,
        );

        Verbs::commit();

        return $this->player->state()->ally();
    }

    public function connectWithAlly()
    {
        PlayerEnteredAllianceCode::fire(
            player_id: $this->player->id,
            game_id: $this->game->id,
            ally_id: $this->ally->id,
            alliance_code: $this->code,
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
