<?php

namespace App\Events;

use App\Models\User;
use App\States\UserState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

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

        $state->is_admin_for = collect();
    }

    public function fired()
    {
		if (app()->isProduction()) {
            UserSubscribedToNewsletter::fire(
                email: $this->email,
				first_name: str($this->name)->before(' '),
            );
        }
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
