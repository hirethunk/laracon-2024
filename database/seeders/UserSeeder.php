<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\User;
use App\Events\UserCreated;
use Illuminate\Database\Seeder;
use App\Events\UserPromotedToAdmin;
use App\Events\AdminApprovedNewPlayer;
use App\Events\UserRequestedToJoinGame;

class UserSeeder extends Seeder
{
    protected $game_id;

    public function __construct()
    {
        $this->game_id = Game::first()->id;
    }

    public function run(): void
    {
        $this->createUser(300);
    }

    protected function createUser(int $count): void
    {
        for ($i = 1; $i <= $count; $i++) {
            $user_id = UserCreated::fire(
                name: 'Test User'.$i,
                email: 'test'.$i.'@thunk.dev',
                password: bcrypt('password'),
            )->user_id;

            UserRequestedToJoinGame::fire(
                user_id: $user_id,
                game_id: $this->game_id,
            );
        }
    }
}
