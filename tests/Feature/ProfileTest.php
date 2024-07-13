<?php

use App\Events\UserCreated;
use App\Models\User;
use Thunk\Verbs\Facades\Verbs;

beforeEach(function () {
    Verbs::commitImmediately();
});

test('profile page is displayed', function () {
    $user_id = UserCreated::fire(
        name: 'foo',
        email: 'foo@bar.baz',
        password: bcrypt('password'),
    )->user_id;

    $user = User::find($user_id);

    $response = $this
        ->actingAs($user)
        ->get('/profile');

    $response->assertOk();
});

test('profile information can be updated', function () {
    $user_id = UserCreated::fire(
        name: 'foo',
        email: 'foo@bar.baz',
        password: bcrypt('password'),
    )->user_id;

    $user = User::find($user_id);

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $user->refresh();

    $this->assertSame('Test User', $user->name);
    $this->assertSame('test@example.com', $user->email);
    $this->assertNull($user->email_verified_at);
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $user_id = UserCreated::fire(
        name: 'foo',
        email: 'foo@bar.baz',
        password: bcrypt('password'),
    )->user_id;

    $user = User::find($user_id);

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'new name',
            'email' => $user->email,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $this->assertNotNull($user->refresh()->email_verified_at);
})->skip();

test('user can delete their account', function () {
    $user_id = UserCreated::fire(
        name: 'foo',
        email: 'foo@bar.baz',
        password: bcrypt('password'),
    )->user_id;

    $user = User::find($user_id);

    $response = $this
        ->actingAs($user)
        ->delete('/profile', [
            'password' => 'password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    $this->assertNull($user->fresh());
});

test('correct password must be provided to delete account', function () {
    $user_id = UserCreated::fire(
        name: 'foo',
        email: 'foo@bar.baz',
        password: bcrypt('password'),
    )->user_id;

    $user = User::find($user_id);

    $response = $this
        ->actingAs($user)
        ->from('/profile')
        ->delete('/profile', [
            'password' => 'wrong-password',
        ]);

    $response
        ->assertSessionHasErrorsIn('userDeletion', 'password')
        ->assertRedirect('/profile');

    $this->assertNotNull($user->fresh());
});
