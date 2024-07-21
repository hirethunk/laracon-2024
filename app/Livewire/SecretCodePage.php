<?php

namespace App\Livewire;

use App\Events\PlayerEnteredSecretCode;
use App\Models\Game;
use App\Models\Player;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class SecretCodePage extends Component
{
    public string $code = '';

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

    public function submitCode()
    {
        $game = $this->game->state();

        $unused_codes = collect($game->unused_codes);

        $used_codes = collect($game->used_codes);

        if ($used_codes->contains($this->code)) {
            $this->message = 'This code has already been used.';
        }

        if ($unused_codes->contains($this->code)) {
            $this->message = 'Code accepted!';
        }

        if (! $unused_codes->contains($this->code) && ! $used_codes->contains($this->code)) {
            $this->message = 'Invalid code. You have received a downvote.';
        }

        PlayerEnteredSecretCode::fire(
            player_id: $this->player->id,
            game_id: $this->game->id,
            secret_code: $this->code,
        );
    }

    public function render()
    {
        return view('livewire.secret-code-page')->layout('layouts.app');
    }
}
