<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\User;
use App\Events\UserCreated;
use Illuminate\Database\Seeder;
use App\Events\AdminApprovedNewPlayer;

class PlayerSeeder extends Seeder
{
    protected $admin_id;
    protected $game_id;

    public function __construct()
    {
        $this->admin_id = User::first()->id;
        $this->game_id = Game::first()->id;
    }

    public function run(): void
    {
        $this->createPlayer(300);
    }

    protected function createPlayer(int $count): void
    {
        for ($i = 1; $i <= $count; $i++) {
            $user_id = UserCreated::fire(
                name: 'Test User'.$i,
                email: 'test'.$i.'@thunk.dev',
                password: bcrypt('password'),
            )->user_id;

            AdminApprovedNewPlayer::fire(
                admin_id: $this->admin_id,
                user_id: $user_id,
                game_id: $this->game_id,
            );
        }
    }
}
