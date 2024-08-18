<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;

class UsersList extends Component
{
    #[Computed]
    public function users()
    {
        return User::orderBy('name')->get();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.users-list');
    }
}
