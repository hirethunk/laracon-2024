<?php

namespace App\States;

use App\Models\User;
use Thunk\Verbs\State;
use App\States\PlayerState;

class UserState extends State
{
    public string $name;

    public bool $is_admin = false;

    public ?int $referrer_player_id = null;

    public int $player_id;

    public string $status = 'new';

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function model()
    {
        return User::find($this->id);
    }

    public function player()
    {
        return PlayerState::load($this->player_id);
    }
}
