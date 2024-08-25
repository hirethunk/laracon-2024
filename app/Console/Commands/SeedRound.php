<?php

namespace App\Console\Commands;

use App\Models\Game;
use App\Models\Player;
use App\States\GameState;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use App\Console\Commands\GameHelper;
use Illuminate\Support\Facades\Date;

class SeedRound extends Command
{
    protected $signature = 'seed:round {offset=0}';
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (
            $offset = intval($this->argument('offset') ?? 0)
        ) {
            Date::setTestNow(now()->addHours($offset));
        }
        
        $player_ids = Player::all()->pluck('id');
        $game_id = Game::first()->id;
        
        GameHelper::round($player_ids, $game_id);
    }
}
