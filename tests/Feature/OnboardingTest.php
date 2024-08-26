<?php

use App\Events\GameCreated;
use App\Events\PlayerJoinedGame;
use App\Events\UserAddedReferral;
use App\Events\UserCreated;
use App\Events\UserPromotedToAdmin;
use App\Livewire\HomePage;
use App\Models\Game;
use App\Models\User;
use Livewire\Livewire;
use Thunk\Verbs\Facades\Verbs;

beforeEach(function () {
    Verbs::commitImmediately();

    $game_id = GameCreated::fire(
        name: 'Laracon 2024',
        starts_at: now()
    )->game_id;

    $this->game = Game::find($game_id);

    $admin_id = UserCreated::fire(
        name: 'Jacob Davis',
        email: 'jacob@jacob.jacob',
        password: 'password',
    )->user_id;

    $referrer_id = UserCreated::fire(
        name: 'Jason Beggs',
        email: 'jason@jason.jason',
        password: 'password',
    )->user_id;

    $user_id = UserCreated::fire(
        name: 'Aaron Belz',
        email: 'aaron@aaron.aaron',
        password: 'password',
    )->user_id;

    $this->user = User::find($user_id);
    $this->admin = User::find($admin_id);
    $this->referrer = User::find($referrer_id);

    UserPromotedToAdmin::fire(user_id: $admin_id, game_id: $game_id);
});


it('an admin cannot approve a player to join when they are already in the game', function () {
    PlayerJoinedGame::fire(
        user_id: $this->user->id,
        game_id: $this->game->id,
    );

    PlayerJoinedGame::fire(
        user_id: $this->user->id,
        game_id: $this->game->id,
    );
})->throws('User is already in the game.');

it('an admin can admit the player into the game', function () {
    $player_id = PlayerJoinedGame::fire(
        user_id: $this->user->id,
        game_id: $this->game->id,
    )->player_id;

    expect($this->game->state()->player_ids->contains($player_id))
        ->toBeTrue();

    expect($this->game->state()->players())
        ->toHaveCount(1)
        ->and($this->game->state()->players()->first()->id)
        ->toBe($player_id);

    $this->user->refresh();
    $player = $this->user->currentPlayer();

    expect($this->user->currentPlayer()->id)->toBe($player_id);
    expect($this->user->state()->current_player_id)->toBe($player_id);
    expect($player->user->id)->toBe($this->user->id);
    expect($player->state()->user_id)->toBe($this->user->id);
});

it('grants an upvote for referrer and referee', function () {
    PlayerJoinedGame::fire(
        user_id: $this->referrer->id,
        game_id: $this->game->id,
    );

    $referrer_player_id = $this->referrer->fresh()->players->first()->id;

    UserAddedReferral::fire(
        user_id: $this->user->id,
        game_id: $this->game->id,
        referrer_player_id: $referrer_player_id,
    );

    expect($this->user->state()->referrer_player_id)->toBe($referrer_player_id);

    // UserRequestedToJoinGame::fire(
    //     user_id: $this->user->id,
    //     game_id: $this->game->id,
    // );

    PlayerJoinedGame::fire(
        user_id: $this->user->id,
        game_id: $this->game->id,
    );

    expect($this->user->fresh()->players->first()->state()->score)->toBe(1);
    expect($this->referrer->fresh()->players->first()->state()->score)->toBe(1);
});

test('there is confirmation once a referrer is chosen', function () {
    $homePage = Livewire::actingAs($this->user)->test(HomePage::class);

    $homePage->assertSee('Before you join, you may add a referrer');

    $referrer_id = $homePage->get('referrer_id');

    expect($referrer_id)->toBeNull();

    // UserRequestedToJoinGame::fire(
    //     user_id: $this->referrer->id,
    //     game_id: $this->game->id,
    // );

    PlayerJoinedGame::fire(
        user_id: $this->referrer->id,
        game_id: $this->game->id,
    );

    $referrer_player_id = $this->referrer->fresh()->players->first()->id;

    $homePage
        ->set('referrer_id', $referrer_player_id)
        ->call('addReferrer');

    $homePage = Livewire::actingAs($this->user->fresh())->test(HomePage::class);

    $referrer = $homePage->get('referrer');

    $homePage
        ->assertDontSee('Before you join, you may add a referrer')
        ->assertSee('Referred by')
        ->assertSee($referrer->user->name);
});
