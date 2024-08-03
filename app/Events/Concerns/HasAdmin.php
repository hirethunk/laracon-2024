<?php

namespace App\Events\Concerns;

use App\States\GameState;
use App\States\UserState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;

trait HasAdmin
{
    #[StateId(UserState::class)]
    public int $admin_id;

    public function authorizeHasAdmin(GameState $game)
    {
        $this->assert(
            assertion: $game->isAdmin($this->admin_id),
            exception: 'An admin is required to perform this action.'
        );
    }
}
