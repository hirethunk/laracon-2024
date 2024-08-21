<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

class UsersList extends Component
{
    public $search = '';

    public $user_id = null;

    #[Computed]
    public function users()
    {
        return User::orderBy('name')
            ->when(
                $this->search,
                fn ($query, $value) => $query->where('name', 'like', "%{$value}%")
            )
            ->get();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.users-list');
    }
}
