<?php

namespace App\Console\Commands;

use App\Events\UserCreated;
use Glhd\Bits\Snowflake;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Thunk\Verbs\Facades\Verbs;

class SetupAdmins extends Command
{
    protected $signature = 'init:admins';
    protected $description = 'Creates all the admins';

    public function handle()
    {
        UserCreated::fire(
            name: 'John Drexler',
            email: 'john@thunk.dev',
            password: bcrypt(Snowflake::make()->id()),
        );

        UserCreated::fire(
            name: 'Daniel Coulbourne',
            email: 'daniel@thunk.dev',
            password: bcrypt(Snowflake::make()->id()),
        )->user_id;

        UserCreated::fire(
            name: 'Jacob Davis',
            email: 'jacob@thunk.dev',
            password: bcrypt(Snowflake::make()->id()),
        );

        UserCreated::fire(
            name: 'Josh Hanley',
            email: 'josh@thunk.dev',
            password: bcrypt(Snowflake::make()->id()),
        );

        UserCreated::fire(
            name: 'Chris Morrell',
            email: 'chris@nachi.org',
            password: bcrypt(Snowflake::make()->id()),
        );

        Verbs::commit();
    }
}
