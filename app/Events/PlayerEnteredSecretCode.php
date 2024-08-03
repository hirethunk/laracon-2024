<?php

namespace App\Events;

use App\Events\Concerns\AffectsVotes;
use App\Events\Concerns\HasGame;
use App\Events\Concerns\HasPlayer;
use App\Events\Concerns\RequiresActiveGame;
use InvalidArgumentException;
use Thunk\Verbs\Event;

class PlayerEnteredSecretCode extends Event
{
    use AffectsVotes;
    use HasGame;
    use HasPlayer;
    use RequiresActiveGame;

    public string $secret_code;

    public function apply()
    {
        try {
            $this->game()->useCode($this->secret_code);
            $this->applyUpvoteToPlayer(
                $this->player_id, $this->player_id, $this->type
            );
        } catch (InvalidArgumentException) {
            // If you try to use a bogus code, you receive a downvote
            $this->applyDownvoteToPlayer(
                $this->player_id, $this->player_id, 'invalid-secret-code'
            );
        }
    }

    public function handle()
    {
        $this->syncPlayerScore($this->player());
    }
}
