<?php

namespace App\Console\Commands;

use App\States\GameState;
use App\Events\GameCreated;
use App\Events\PlayerJoinedGame;
use App\Events\UserCreated;
use Thunk\Verbs\Facades\Verbs;
use Illuminate\Console\Command;
use App\Events\UserPromotedToAdmin;
use Illuminate\Support\Collection;


use function Laravel\Prompts\progress;

class SeedGame extends Command
{
    protected $signature = 'seed:game {count=50}';
    protected $description = 'Command description';

    public int $player_count;
    public Collection $user_ids;
    public Collection $player_ids;

    public int $game_id;
    public GameState $game;
    
    public int $admin_id;


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->scaffoldGame();

        $this->createPlayers();
    }

    public function scaffoldGame()
    {
        $this->game_id = snowflake_id();
        $this->admin_id = snowflake_id();

        $this->player_count = $this->argument('count') ?? 50;
        $this->user_ids = collect(array_fill(0, $this->player_count, null))
            ->map(fn () => snowflake_id());


        $progress = progress('Creating game...', 4);

        $progress->start();

        $this->call('migrate:fresh');

        
        GameCreated::fire(
            game_id: $this->game_id,
            name: 'Laracon 2024',
            starts_at: now(),
        );

        $this->game = GameState::load($this->game_id);
        
        $progress->advance();

        UserCreated::fire(
            user_id: $this->admin_id,
            name: 'Admin',
            email: 'admin@admin.com',
            password: bcrypt('password'),
        );
        
        $progress->advance();

        UserPromotedToAdmin::fire(
            user_id: $this->admin_id,
            game_id: $this->game_id,
        );
        
        $progress->advance();

        Verbs::commit();
        
        $progress->advance();
    }

    public function createPlayers()
    {
        $progress = progress('Creating players...', $this->player_count);
        $progress->start();

        $this->player_ids = $this->user_ids->map(function ($player_id) use ($progress) {
            UserCreated::fire(
                user_id: $player_id,
                name: fake()->unique()->name(),
                email: fake()->unique()->safeEmail(),
                password: bcrypt('password'),
            );

            $player_id = PlayerJoinedGame::fire(
                user_id: $player_id,
                game_id: $this->game_id,
            )->player_id;

            $progress->advance();

            return $player_id;
        });

        Verbs::commit();
    }
}
