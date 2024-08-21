<?php

namespace Database\Seeders;

use App\Events\UserCreated;
use App\Events\UserRequestedToJoinGame;
use App\Models\Game;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $this->createUser(20);
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
                game_id: Game::first()->id,
            );
        }
    }
}
