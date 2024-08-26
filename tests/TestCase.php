<?php

namespace Tests;

use App\Events\GameCreated;
use App\Events\PlayerJoinedGame;
use App\Events\UserCreated;
use App\Events\UserPromotedToAdmin;
use App\Models\Game;
use App\Models\Player;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public Player $taylor;

    public Player $aaron;

    public Player $caleb;

    public User $admin;

    public User $unapproved_user;

    public Game $game;

    public function bootGame()
    {
        $game_id = GameCreated::fire(
            name: 'Laracon 2024',
            starts_at: now(),
        )->game_id;

        $game = Game::find($game_id);

        $admin_id = UserCreated::fire(
            name: 'Jacob Davis',
            email: 'jacob@thunk.dev',
            password: 'password',
        )->user_id;

        $this->admin = User::find($admin_id);
        UserPromotedToAdmin::fire(user_id: $admin_id, game_id: $game_id);

        $taylor_id = UserCreated::fire(
            name: 'Taylor Otwell',
            email: 'taylor@laravel.com',
            password: 'password',
        )->user_id;

        PlayerJoinedGame::fire(
            user_id: $taylor_id,
            game_id: $game->id,
        );

        $this->taylor = User::find($taylor_id)->currentPlayer();

        $aaron_id = UserCreated::fire(
            name: 'Aaron Francis',
            email: 'aaron@francis.com',
            password: 'password',
        )->user_id;

        PlayerJoinedGame::fire(
            user_id: $aaron_id,
            game_id: $game->id,
        );

        $this->aaron = User::find($aaron_id)->currentPlayer();

        $caleb_id = UserCreated::fire(
            name: 'Caleb Porzio',
            email: 'caleb@livewire.com',
            password: 'password',
        )->user_id;

        PlayerJoinedGame::fire(
            user_id: $caleb_id,
            game_id: $game->id,
        );

        $this->caleb = User::find($caleb_id)->currentPlayer();

        collect(range(1, 10))->each(function ($i) use ($game) {
            $user_id = UserCreated::fire(
                name: "User {$i}",
                email: $i.'@example.com',
                password: 'password',
            )->user_id;

            PlayerJoinedGame::fire(
                user_id: $user_id,
                game_id: $game->id,
            );
        });

        $this->game = $game;
    }

    public function getUnapprovedUser()
    {
        $user_id = UserCreated::fire(
            name: 'unapproved',
            email: 'unapproved@example.com',
            password: 'password',
        )->user_id;

        $this->unapproved_user = User::find($user_id);
    }
}
