<?php

// namespace App\Events;

// use App\States\GameState;
// use App\States\UserState;
// use Thunk\Verbs\Attributes\Autodiscovery\StateId;
// use Thunk\Verbs\Event;

// class UserRequestedToJoinGame extends Event
// {
//     #[StateId(UserState::class)]
//     public int $user_id;

//     #[StateId(GameState::class)]
//     public int $game_id;

//     public function validate()
//     {
//         $this->assert(
//             $this->state(GameState::class)->user_ids_approved->contains($this->user_id) === false,
//             'User is already in the game.'
//         );
//     }

//     public function applyToUser(UserState $state)
//     {
//         //
//     }
// }
