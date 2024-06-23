<?php

namespace App\Events;

use App\Models\User;
use Thunk\Verbs\Event;
use App\States\UserState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;

class UserNameUpdated extends Event
{
    #[StateId(UserState::class)]
    public ?int $user_id = null;

    public string $name;

    public function authorize()
    {
        $this->assert(
            ! $this->state(UserState::class)->isApproved(),
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
