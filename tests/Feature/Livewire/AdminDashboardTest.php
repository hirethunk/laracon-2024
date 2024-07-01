<?php

use App\Livewire\AdminDashboard;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(AdminDashboard::class)
        ->assertStatus(200);
});
