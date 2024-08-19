<?php

use App\Events\AdminApprovedNewPlayer;
use App\Events\GameCreated;
use App\Events\UserAddedReferral;
use App\Events\UserCreated;
use App\Events\UserPromotedToAdmin;
use App\Events\UserRequestedToJoinGame;
use App\Models\Game;
use App\Models\User;
use App\States\PlayerState;
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

it('a user can request to join a game', function () {
    UserRequestedToJoinGame::fire(
        user_id: $this->user->id,
        game_id: $this->game->id,
    );

    expect($this->game->state()->usersAwaitingApproval())
        ->toHaveCount(1)
        ->and($this->game->state()->usersAwaitingApproval()->first()->id)
        ->toBe($this->user->id);
});

it('a user cannot request to join twice', function () {
    UserRequestedToJoinGame::fire(
        user_id: $this->user->id,
        game_id: $this->game->id,
    );

    UserRequestedToJoinGame::fire(
        user_id: $this->user->id,
        game_id: $this->game->id,
    );
})->throws('User has already requested to join this game.');

it('a user cannot request to join after they are in the game', function () {
    UserRequestedToJoinGame::fire(
        user_id: $this->user->id,
        game_id: $this->game->id,
    );

    AdminApprovedNewPlayer::fire(
        admin_id: $this->admin->id,
        user_id: $this->user->id,
        game_id: $this->game->id,
    );

    UserRequestedToJoinGame::fire(
        user_id: $this->user->id,
        game_id: $this->game->id,
    );
})->throws('User is already in the game.');

it('an admin cannot approve a player to join when they are already in the game', function () {
    UserRequestedToJoinGame::fire(
        user_id: $this->user->id,
        game_id: $this->game->id,
    );

    AdminApprovedNewPlayer::fire(
        admin_id: $this->admin->id,
        user_id: $this->user->id,
        game_id: $this->game->id,
    );

    AdminApprovedNewPlayer::fire(
        admin_id: $this->admin->id,
        user_id: $this->user->id,
        game_id: $this->game->id,
    );
})->throws('User is already in the game.');

it('an admin can admit the player into the game', function () {
    UserRequestedToJoinGame::fire(
        user_id: $this->user->id,
        game_id: $this->game->id,
    );

    $player_id = AdminApprovedNewPlayer::fire(
        admin_id: $this->admin->id,
        user_id: $this->user->id,
        game_id: $this->game->id,
    )->player_id;

    expect($this->game->state()->usersAwaitingApproval())
        ->toHaveCount(0);

    expect($this->game->state()->user_ids_approved->contains($this->user->id))
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

it('a nonAdmin cannot approve a user', function () {
    UserRequestedToJoinGame::fire(
        user_id: $this->user->id,
        game_id: $this->game->id,
    );

    AdminApprovedNewPlayer::fire(
        admin_id: $this->user->id,
        user_id: $this->user->id,
        game_id: $this->game->id,
    );
})->throws('Only admins can approve new players.');

it('grants an upvote for referrer and referee', function () {
    UserRequestedToJoinGame::fire(
        user_id: $this->referrer->id,
        game_id: $this->game->id,
    );

    AdminApprovedNewPlayer::fire(
        admin_id: $this->admin->id,
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

    UserRequestedToJoinGame::fire(
        user_id: $this->user->id,
        game_id: $this->game->id,
    );

    AdminApprovedNewPlayer::fire(
        admin_id: $this->admin->id,
        user_id: $this->user->id,
        game_id: $this->game->id,
    );

    // dump('referrer', PlayerState::load($referrer_player_id));

    // $player_id = $this->user->fresh()->players->first()->id;
    // dump('referred', PlayerState::load($player_id));

    expect($this->user->fresh()->players->first()->state()->score)->toBe(1);
    expect($this->referrer->fresh()->players->first()->state()->score)->toBe(1);
});
