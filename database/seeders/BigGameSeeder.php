<?php

namespace Database\Seeders;

use Throwable;
use App\States\GameState;
use App\Events\GameCreated;
use App\Events\PlayerVoted;
use App\Events\UserCreated;
use App\States\PlayerState;
use Illuminate\Support\Arr;
use App\Events\PlayerResigned;
use Thunk\Verbs\Facades\Verbs;
use Illuminate\Database\Seeder;
use App\Events\PlayerJoinedGame;
use App\Events\UserAddedReferral;
use Illuminate\Support\Collection;
use App\Events\UserPromotedToAdmin;
use Illuminate\Support\Facades\Date;
use App\Modifiers\Laracon2024Template;
use function Laravel\Prompts\progress;
use App\Events\PlayerEnteredSecretCode;
use Illuminate\Auth\Access\AuthorizationException;
use Thunk\Verbs\Exceptions\EventNotValidForCurrentState;

class BigGameSeeder extends Seeder
{
    protected const PLAYER_COUNT = 100;

    protected int $game_id;

    protected GameState $game;

    protected int $admin_id;

    protected Collection $users;

    protected Collection $players;

    protected Collection $unused_codes;

    public function run(): void
    {
        // $GLOBALS['info'] = $this->command->line(...);
        // $GLOBALS['info'] = fn() => null;

        // Event::listen(function(QueryExecuted $e) {
        // 	$this->command->warn($e->toRawSql());
        // });
        
        $this->command->newLine();

        $this->setup();
        $this->createGame();
        $this->createPlayers();

        $round = 1;

        while (now()->lte($this->game->ends_at)) {
            if ($round = 2) {
                config(['dump' => true]);
            }
            $this->playRound();
            Date::setTestNow(now()->addHour());
            $round++;
        }
        
        $this->command->newLine();
    }

    protected function setup(): void
    {
        ini_set('memory_limit', '-1');

        $this->game_id = snowflake_id();
        $this->admin_id = snowflake_id();

        $this->unused_codes = collect(Laracon2024Template::CODES);
    }

    protected function createGame(): void
    {
        $progress = progress('Creating game...', 4);
        $progress->start();
        
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

    protected function createPlayers(): void
    {
        $faker = fake();
        $user_ids = [];
        $player_ids = [];
        
        $progress = progress('Creating players...', self::PLAYER_COUNT);
        $progress->start();

        for ($i = 0; $i < self::PLAYER_COUNT; $i++) {
            $user_id = snowflake_id();
            $player_id = snowflake_id();

            UserCreated::fire(
                user_id: $user_id,
                name: $faker->unique()->name(),
                email: $faker->unique()->safeEmail(),
                password: bcrypt('password'),
            );

            if (count($player_ids) && random_int(1, 3) === 1) {
                $referrer_id = Arr::random($player_ids);

                UserAddedReferral::fire(
                    user_id: $user_id,
                    game_id: $this->game_id,
                    referrer_player_id: $referrer_id,
                );
            }

            PlayerJoinedGame::fire(
                user_id: $user_id,
                game_id: $this->game_id,
                player_id: $player_id,
            );

            $user_ids[] = $user_id;
            $player_ids[] = $player_id;
            
            $progress->advance();
        }

        $this->users = collect($user_ids);
        $this->players = collect($player_ids);

        Verbs::commit();
        
        $progress->finish();
    }
    
    protected function playRound(): void
    {
        $progress = progress('Playing '.now()->format('h:ia').' round...', $this->players->count());
        $progress->start();
        
        foreach ($this->players as $index => $player_id) {
            if(config('dump', false)) {
                dump('player: ' . $player_id);
            }

            $downvoteTarget = $this->players
                ->shuffle()
                ->first(fn ($id) => $id !== $player_id && !PlayerState::load($id)->cannotBeDownvoted());

            $upvoteTarget = $this->players
                ->shuffle()
                ->first(fn ($id) => $id !== $player_id && !PlayerState::load($id)->cannotBeUpvoted());

            if (! $downvoteTarget || ! $upvoteTarget) {
                break;
            }
            
            try {
                PlayerVoted::fire(
                    game_id: $this->game_id,
                    player_id: $player_id,
                    upvotee_id: $upvoteTarget,
                    downvotee_id: $downvoteTarget,
                );
                
                if(config('dump', false)) {
                    dump('trying code');
                }
                // Maybe play secret code
                if (random_int(1, 10) === 1 && $this->unused_codes->isNotEmpty()) {
                    $code = $this->unused_codes->pop();
                    
                    // most people play it just once, 1 in 10 play it 10+ times
                    $plays = random_int(1, 10) === 1 ? random_int(10, 50) : 1;
                    
                    for ($i = 0; $i < $plays; $i++) {
                        PlayerEnteredSecretCode::fire(
                            player_id: $player_id,
                            game_id: $this->game_id,
                            secret_code: $code,
                        );
                    }
                }
                
                if(config('dump', false)) {
                    dump('trying resign');
                }
                // Maybe resign
                if ($this->players->count() > 2 && random_int(1, round(static::PLAYER_COUNT / 2)) === 1) {
                    PlayerResigned::fire(
                        player_id: $player_id,
                        beneficiary_id: $upvoteTarget,
                        game_id: $this->game_id,
                    );
                    $this->players->forget($index);
                }
            } catch (EventNotValidForCurrentState $e) {
                // just ignore
                dd($e);
            } catch (AuthorizationException) {
                // also just ignore
                dd('auth error');
            } catch (Throwable $e) {
                dd($e);
            }
            
            $progress->advance();
        }
        
        Verbs::commit();
        
        $progress->finish();
    }
}
