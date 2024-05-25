<?php

namespace Tests;

use App\Models\Game;
use App\Models\User;
use App\Models\Player;
use App\Events\GameCreated;
use App\Events\UserCreated;
use App\Events\AdminApprovedNewPlayer;
use App\Events\UserPromotedToAdmin;
use App\Events\UserRequestedToJoinGame;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public Player $taylor;

    public Player $aaron;

    public Player $caleb;

    public function bootGame()
    {
        $game_id = GameCreated::fire(name: 'Laracon 2024')->game_id;
        $game = Game::find($game_id);

        $admin_id = UserCreated::fire(
            name: 'Jacob Davis',
            email: 'jacob@thunk.dev',
            password: 'password',
        )->user_id;

        $admin = User::find($admin_id);
        UserPromotedToAdmin::fire(user_id: $admin_id);

        $taylor_id = UserCreated::fire(
            name: 'Taylor Otwell',
            email: 'taylor@laravel.com',
            password: 'password',
        )->user_id;

        UserRequestedToJoinGame::fire(
            user_id: $taylor_id,
            game_id: $game->id,
        );

        AdminApprovedNewPlayer::fire(
            admin_id: $admin->id,
            user_id: $taylor_id,
            game_id: $game->id,
        );

        $this->taylor = User::find($taylor_id)->player;

        $aaron_id = UserCreated::fire(
            name: 'Aaron Francis',
            email: 'aaron@francis.com',
            password: 'password',
        )->user_id;

        UserRequestedToJoinGame::fire(
            user_id: $aaron_id,
            game_id: $game->id,
        );

        AdminApprovedNewPlayer::fire(
            admin_id: $admin->id,
            user_id: $aaron_id,
            game_id: $game->id,
        );

        $this->aaron = User::find($aaron_id)->player;

        $caleb_id = UserCreated::fire(
            name: 'Caleb Porzio',
            email: 'caleb@livewire.com',
            password: 'password',
        )->user_id;

        UserRequestedToJoinGame::fire(
            user_id: $caleb_id,
            game_id: $game->id,
        );

        AdminApprovedNewPlayer::fire(
            admin_id: $admin->id,
            user_id: $caleb_id,
            game_id: $game->id,
        );

        $this->caleb = User::find($caleb_id)->player;

        collect(range(1, 10))->each(function ($i) use ($admin, $game) {
            $user_id = UserCreated::fire(
                name: "User {$i}",
                email: $i . '@example.com',
                password: 'password',
            )->user_id;

            UserRequestedToJoinGame::fire(
                user_id: $user_id,
                game_id: $game->id,
            );

            AdminApprovedNewPlayer::fire(
                admin_id: $admin->id,
                user_id: $user_id,
                game_id: $game->id,
            );
        });
    }
}
