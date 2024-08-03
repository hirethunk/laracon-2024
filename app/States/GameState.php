<?php

namespace App\States;

use App\Models\Game;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Thunk\Verbs\State;

class GameState extends State
{
    public string $name;

    public bool $is_active;

    public Collection $user_ids_awaiting_approval;

    public Collection $player_ids;

    public Collection $admin_user_ids;

    public Collection $unused_codes;

    public Collection $used_codes;

    public Carbon $starts_at;

    public array $modifiers;

    public function activeModifier()
    {
        return collect($this->modifiers)->filter(fn ($modifier) => Carbon::parse($modifier['starts_at']) <= now()
            && Carbon::parse($modifier['ends_at']) >= now()
        )->first();
    }

	public function hasActiveModifier(string $slug): bool
	{
		$modifier = $this->activeModifier();
		
		return $modifier && $modifier['slug'] === $slug;
	}

    public function model()
    {
        return Game::find($this->id);
    }

    public function usersAwaitingApproval()
    {
        return collect($this->user_ids_awaiting_approval)->map(fn ($id) => User::find($id));
    }

    public function players()
    {
        return collect($this->player_ids)->map(fn ($id) => PlayerState::load($id));
    }
	
	public function addPlayer(PlayerState|int $player): static
	{
		$player = state($player, PlayerState::class);
		
		$this->user_ids_awaiting_approval = $this->user_ids_awaiting_approval->reject(fn($id) => $id === $player->user_id);
		$this->player_ids->push($player->id);
		
		return $this;
	}
	
	public function isAdmin(PlayerState|int $player): bool
	{
		return $this->admin_user_ids->contains(id($player));
	}
	
	public function isAwaitingApproval(UserState|int $user): bool
	{
		return $this->user_ids_awaiting_approval->contains(id($user));
	}
	
	public function isPlayer(PlayerState|int|null $player = null, UserState|int|null $user = null): bool
	{
		if (null === $player && null === $user) {
			throw new InvalidArgumentException('isPlayer requires a $player or $user argument');
		}
		
		return null !== $user
			? $this->players()->contains(fn(PlayerState $state) => $state->user_id === id($user))
			: $this->player_ids->contains(id($player));
	}
	
	public function useCode(string $code): void
	{
		if ($this->unused_codes->contains($code)) {
			$this->unused_codes = $this->unused_codes->reject($code);
			$this->used_codes->push($code);
		}
		
		if (! $this->isCodeValid($code)) {
			throw new InvalidArgumentException('Invalid secret code.');
		}
	}
	
	public function isCodeValid(string $code): bool
	{
		return $this->used_codes->contains($code) || $this->unused_codes->contains($code);
	}
}
