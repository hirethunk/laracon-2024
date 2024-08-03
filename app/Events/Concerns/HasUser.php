<?php

namespace App\Events\Concerns;

use App\States\UserState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;

trait HasUser
{
    #[StateId(UserState::class, 'user')]
    public int $user_id;

    public function user(): UserState
    {
        return $this->states()->get('user');
    }
}
