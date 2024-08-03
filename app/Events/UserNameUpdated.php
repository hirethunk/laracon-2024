<?php

namespace App\Events;

use App\Events\Concerns\HasUser;
use App\Models\User;
use App\States\UserState;
use Thunk\Verbs\Event;

class UserNameUpdated extends Event
{
	use HasUser;

    public string $name;

    public function authorize()
    {
        $this->assert(
			assertion: ! $this->user()->currentPlayer(), 
			exception: 'Cannot change name after Approval.'
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
