<?php

namespace App\Events;

use App\Models\Player;
use App\Models\User;
use App\States\GameState;
use App\States\PlayerState;
use App\States\UserState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;
use Thunk\Verbs\Facades\Verbs;

class PlayerJoinedGame extends Event
{
	use AffectsVotes;
	
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
	    
	    if ($referrer = $this->referrer()) {
		    $this->upvotePlayer($state, $this->player_id, 'got-referred');
			$this->upvotePlayer($referrer, $referrer->id, 'referred');
		    
		    if ($this->state(GameState::class)->activeModifier()['slug'] === 'signing-bonus') {
			    $referrer->is_immune_until = now()->addHour();
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
	
	protected function referrer(): ?PlayerState
	{
		if ($id = $this->state(UserState::class)->referrer_player_id) {
			return PlayerState::load($id);
		}
		
		return null;
	}
}
