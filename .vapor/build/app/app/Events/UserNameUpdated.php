<?php

namespace App\Events;

use App\Models\User;
use App\States\UserState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class UserNameUpdated extends Event
{
    #[StateId(UserState::class)]
    public ?int $user_id = null;

    public string $name;

    public function authorize()
    {
        $this->assert(
            ! $this->state(UserState::class)->currentPlayer(),
            'Cannot change name after Approval.'
        );
    }

    public function apply(UserState $state)
    {
        $state->name = $this->name;
    }

    public function handle()
    {
        $user = User::find($this->user_id);

        $user->name = $this->name;

        $user->save();
    }
}
