<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;

class Autocomplete extends Component
{
    public $name;
    public $userId;

    #[Computed]
    public function user()
    {
        return User::find($this->userId);
    }

    #[Computed]
    public function users()
    {
        return User::query()
            ->when($this->name, function ($query, $name) {
                return $query->where('name', 'LIKE', '%' . $name . '%');
            })
            ->orderBy('name')
            ->get();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.autocomplete');
    }
}
