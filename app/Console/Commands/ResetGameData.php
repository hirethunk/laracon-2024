<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetGameData extends Command
{
    protected $signature = 'game:reset-data';

    protected $description = 'Truncates all the game data tables. Leaves the Verbs tables alone.';

    public function handle()
    {
        if (DB::getDriverName() !== 'sqlite') {
            $this->info('Disabling foreign key checks...');
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        $this->info('Truncating games table...');
        DB::table('games')->truncate();

        $this->info('Truncating users table...');
        DB::table('users')->truncate();

        $this->info('Truncating players table...');
        DB::table('players')->truncate();

        $this->info('Truncating snapshots table...');
        DB::table('verb_snapshots')->truncate();

        if (DB::getDriverName() !== 'sqlite') {
            $this->info('Enabling foreign key checks...');
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}
