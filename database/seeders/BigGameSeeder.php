<?php

namespace Database\Seeders;

use App\Events\AdminApprovedNewPlayer;
use App\Events\GameCreated;
use App\Events\PlayerEnteredSecretCode;
use App\Events\PlayerResigned;
use App\Events\PlayerVoted;
use App\Events\UserAddedReferral;
use App\Events\UserCreated;
use App\Events\UserPromotedToAdmin;
use App\Events\UserRequestedToJoinGame;
use App\Modifiers\Laracon2024Template;
use App\States\GameState;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Thunk\Verbs\Exceptions\EventNotValidForCurrentState;
use Thunk\Verbs\Facades\Verbs;
use Thunk\Verbs\Lifecycle\Queue;

use function Laravel\Prompts\progress;

class BigGameSeeder extends Seeder
{
    protected const PLAYER_COUNT = 500;

    protected int $game_id;

    protected GameState $game;

    protected int $admin_id;

    protected Collection $users;

    protected Collection $players;

    protected Collection $unused_codes;

    protected Queue $queue;

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

        while (now()->lte($this->game->ends_at)) {
            $this->playRound();
            Date::setTestNow(now()->addHour());
        }

        $this->command->newLine();
    }

    protected function setup(): void
    {
        ini_set('memory_limit', '-1');

        $this->queue = app(Queue::class);
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

            UserRequestedToJoinGame::fire(
                user_id: $user_id,
                game_id: $this->game_id
            );

            if (count($player_ids) && random_int(1, 3) === 1) {
                $referrer_id = Arr::random($player_ids);

                UserAddedReferral::fire(
                    user_id: $user_id,
                    game_id: $this->game_id,
                    referrer_player_id: $referrer_id,
                );
            }

            AdminApprovedNewPlayer::fire(
                admin_id: $this->admin_id,
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
            if (count($this->queue->event_queue) >= 100) {
                Verbs::commit();
            }

            $other_player_ids = $this->players->random(5)->filter(fn ($id) => $id !== $player_id);

            if ($other_player_ids->count() < 2) {
                $progress->advance();
                break;
            }

            try {
                PlayerVoted::fire(
                    game_id: $this->game_id,
                    player_id: $player_id,
                    upvotee_id: $other_player_ids->pop(),
                    downvotee_id: $other_player_ids->pop(),
                );

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

                // Maybe resign
                if ($other_player_ids->isNotEmpty() && random_int(1, round(static::PLAYER_COUNT / 2)) === 1) {
                    PlayerResigned::fire(
                        player_id: $player_id,
                        beneficiary_id: $other_player_ids->pop(),
                        game_id: $this->game_id,
                    );
                    $this->players->forget($index);
                }
            } catch (EventNotValidForCurrentState) {
                // just ignore
            } catch (AuthorizationException) {
                // also just ignore
            }

            $progress->advance();
        }

        Verbs::commit();

        $progress->finish();
    }
}
