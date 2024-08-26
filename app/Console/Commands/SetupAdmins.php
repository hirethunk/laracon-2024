<?php

namespace App\Console\Commands;

use App\Models\User;
use Glhd\Bits\Snowflake;
use App\Events\UserCreated;
use App\Events\UserPromotedToAdmin;
use App\Models\Game;
use Thunk\Verbs\Facades\Verbs;
use Illuminate\Console\Command;

class SetupAdmins extends Command
{
    protected $signature = 'init:admins';

    protected $description = 'Creates all the admins';

    public function handle()
    {

        $john = User::firstWhere('email', 'john@thunk.dev')?->id ?? UserCreated::fire(
            name: 'John Drexler',
            email: 'john@thunk.dev',
            password: bcrypt(Snowflake::make()->id()),
        )->user_id;

        $daniel = User::firstWhere('email', 'daniel@thunk.dev')?->id ?? UserCreated::fire(
            name: 'Daniel Coulbourne',
            email: 'daniel@thunk.dev',
            password: bcrypt(Snowflake::make()->id()),
        )->user_id;

        $jacob = User::firstWhere('email', 'jacob@thunk.dev')?->id ?? UserCreated::fire(
            name: 'Jacob Davis',
            email: 'jacob@thunk.dev',
            password: bcrypt(Snowflake::make()->id()),
        )->user_id;

        $josh = User::firstWhere('email', 'josh@thunk.dev')?->id ?? UserCreated::fire(
            name: 'Josh Hanley',
            email: 'josh@thunk.dev',
            password: bcrypt(Snowflake::make()->id()),
        )->user_id;

        $chris = User::firstWhere('email', 'chris@nachi.org')?->id ?? UserCreated::fire(
            name: 'Chris Morrell',
            email: 'chris@nachi.org',
            password: bcrypt(Snowflake::make()->id()),
        )->user_id;

        $admins = [$john, $daniel, $jacob, $josh, $chris];

        Game::all()->each(function ($g) use ($admins) {
            foreach ($admins as $admin) {
                UserPromotedToAdmin::fire(
                    user_id: $admin,
                    game_id: $g->id,
                );
            }
        });

        Verbs::commit();
    }
}
