<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;

class UsersList extends Component
{
    public $search = '';

    public $userId = null;

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
