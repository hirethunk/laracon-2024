<?php

namespace App\Events;

use App\Models\User;
use Thunk\Verbs\Event;
use App\States\UserState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;

class UserCreated extends Event
{
    #[StateId(UserState::class)]
    public ?int $user_id = null;

    public string $name;

    public string $email;

    public string $password;

    public function apply(UserState $state)
    {
        $state->name = $this->name;
        
        $state->status = 'new-signup';

        $state->is_admin_for = collect();
    }

    public function handle()
    {
        User::create([
            'id' => $this->user_id,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ]);
    }
}
