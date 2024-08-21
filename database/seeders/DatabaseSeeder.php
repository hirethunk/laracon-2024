<?php

namespace Database\Seeders;

use App\Events\AdminApprovedNewPlayer;
use App\Events\GameCreated;
use App\Events\UserCreated;
use App\Events\UserPromotedToAdmin;
use App\Events\UserRequestedToJoinGame;
use Illuminate\Database\Seeder;
use Thunk\Verbs\Facades\Verbs;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Verbs::commitImmediately();

        $game_id = GameCreated::fire(
            name: 'Laracon 2024',
            starts_at: now(),
        )->game_id;

        $admin_id = UserCreated::fire(
            name: 'Admin Guy',
            email: 'a@thunk.dev',
            password: bcrypt('password'),
        )->user_id;

        UserPromotedToAdmin::fire(
            user_id: $admin_id,
            game_id: $game_id
        );

        $john_id = UserCreated::fire(
            name: 'John Drexler',
            email: 'john@thunk.dev',
            password: bcrypt('password'),
        )->user_id;

        AdminApprovedNewPlayer::fire(
            admin_id: $admin_id,
            user_id: $john_id,
            game_id: $game_id
        );

        $daniel_id = UserCreated::fire(
            name: 'Daniel Coulbourne',
            email: 'daniel@thunk.dev',
            password: bcrypt('password'),
        )->user_id;

        AdminApprovedNewPlayer::fire(
            admin_id: $admin_id,
            user_id: $daniel_id,
            game_id: $game_id
        );

        $jacob_id = UserCreated::fire(
            name: 'Jacob Davis',
            email: 'jacob@thunk.dev',
            password: bcrypt('password'),
        )->user_id;

        AdminApprovedNewPlayer::fire(
            admin_id: $admin_id,
            user_id: $jacob_id,
            game_id: $game_id
        );

        $josh_id = UserCreated::fire(
            name: 'Josh Hanley',
            email: 'josh@thunk.dev',
            password: bcrypt('password'),
        )->user_id;

        AdminApprovedNewPlayer::fire(
            admin_id: $admin_id,
            user_id: $josh_id,
            game_id: $game_id
        );

        $caleb_porzio = UserCreated::fire(
            name: 'Caleb Porzio',
            email: 'caleb@thunk.dev',
            password: bcrypt('password'),
        )->user_id;

        AdminApprovedNewPlayer::fire(
            admin_id: $admin_id,
            user_id: $caleb_porzio,
            game_id: $game_id
        );

        $user_id = UserCreated::fire(
            name: 'Test User One',
            email: 'testOne@thunk.dev',
            password: bcrypt('password'),
        )->user_id;

        UserRequestedToJoinGame::fire(
            user_id: $user_id,
            game_id: $game_id
        );

        $user_id = UserCreated::fire(
            name: 'Test User Two',
            email: 'testTwo@thunk.dev',
            password: bcrypt('password'),
        )->user_id;

        UserRequestedToJoinGame::fire(
            user_id: $user_id,
            game_id: $game_id
        );

        $user_id = UserCreated::fire(
            name: 'Test User Three',
            email: 'testThree@thunk.dev',
            password: bcrypt('password'),
        )->user_id;

        UserRequestedToJoinGame::fire(
            user_id: $user_id,
            game_id: $game_id
        );

        $user_id = UserCreated::fire(
            name: 'Test User Four',
            email: 'testFour@thunk.dev',
            password: bcrypt('password'),
        )->user_id;

        UserRequestedToJoinGame::fire(
            user_id: $user_id,
            game_id: $game_id
        );

        $user_id = UserCreated::fire(
            name: 'Test User Four',
            email: 'blah@thunk.dev',
            password: bcrypt('password'),
        )->user_id;

        UserRequestedToJoinGame::fire(
            user_id: $user_id,
            game_id: $game_id
        );
    }
}
