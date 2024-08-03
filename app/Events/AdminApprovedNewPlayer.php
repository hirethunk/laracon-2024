<?php

namespace App\Events;

use App\Events\Concerns\AffectsVotes;
use App\Events\Concerns\HasAdmin;
use App\Events\Concerns\HasGame;
use App\Events\Concerns\HasUser;
use App\Events\Concerns\RequiresActiveGame;
use App\Models\Player;
use App\Models\User;
use App\States\GameState;
use App\States\PlayerState;
use App\States\UserState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class AdminApprovedNewPlayer extends Event
{
	use RequiresActiveGame;
	use HasAdmin;
	use HasUser;
	use HasGame;
	use AffectsVotes;
	
	#[StateId(PlayerState::class)]
	public ?int $player_id = null;

    public function validateGame(GameState $game)
    {
        $this->assert(
			assertion: ! $game->isPlayer(user: $this->user_id), 
			exception: 'User is already in the game.'
        );
    }
	
	public function applyToGame(GameState $game): void
	{
		$game->addPlayer($this->player_id);
	}
	
	public function applyToPlayer(PlayerState $player): void
	{
		$player->user_id = $this->user_id;
		$player->game_id = $this->game_id;
		$player->name = $this->state(UserState::class)->name;
		$player->upvotes = [];
		$player->downvotes = [];
		$player->ballots_cast = [];
		$player->is_active = true;
		$player->is_immune_until = now();
	}
	
	public function applyToUser(UserState $user): void
	{
		$user->current_player_id = $this->player_id;
	}
	
	public function applyReferrer(): void
	{
		if (! $referrer = $this->user()->referrer()) {
			return;
		}
		
		$this->applyUpvoteToPlayer($this->player_id, $this->player_id, 'got-referred');
		$this->applyUpvoteToPlayer($referrer, $referrer->id, 'referred');
		
		if ($this->game()->hasActiveModifier('signing-bonus')) {
			$referrer->is_immune_until = now()->addHour();
		}
	}
	
	public function handle()
	{
		User::find($this->user_id)
			->update(['current_game_id' => $this->game_id]);
		
		return Player::create([
			'id' => $this->player_id,
			'user_id' => $this->user_id,
			'game_id' => $this->game_id,
		]);
	}
}
