<?php

namespace App\Console\Commands;

use App\Events\GameCreated;
use App\Events\UserPromotedToAdmin;
use App\Models\User;
use Illuminate\Console\Command;
use Thunk\Verbs\Facades\Verbs;

class CreateGame extends Command
{
    protected $signature = 'init:game {game_name} {--delay_minutes=}';

    protected $description = 'Creates a game and adds all the admins as admins';

    public function handle()
    {
        $game_id = GameCreated::fire(
            name: $this->argument('game_name'),
            starts_at: now()->addMinutes(intval($this->option('delay_minutes')))
        )->game_id;

        $admin_users = User::whereIn('email', [
            'daniel@thunk.dev',
            'john@thunk.dev',
            'jacob@thunk.dev',
            'josh@thunk.dev',
            'chris@nachi.org',
        ])->get();

        $admin_users->each(function ($user) use ($game_id) {
            UserPromotedToAdmin::fire(
                user_id: $user->id,
                game_id: $game_id
            );
        });

        Verbs::commit();
    }
}
