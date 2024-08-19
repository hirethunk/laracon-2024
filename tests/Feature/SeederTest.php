<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

uses(RefreshDatabase::class);

beforeEach(function () {
});

it('runs seeders without throwing an exception', function () {
    try {
        Artisan::call('migrate:fresh', ['--seed' => true]);
        expect(true)->toBeTrue();
    } catch (\Exception $e) {
        info($e);
        dd('foo', $e->getMessage());
        expect(false)->toBeTrue();
    }
});
