<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Events\UserNameUpdated;

#[On('refreshComponent')]
class UserProfile extends Component
{
    public $user;

    public $name;

    public function mount()
    {
        $this->user = auth()->user();
        $this->name = $this->user->name;
    }

    public function updateName()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        UserNameUpdated::fire(
            user_id: $this->user->id,
            name: $this->name
        );

        session()->flash('event', 'UserNameUpdated');

        $this->dispatch('refreshComponent');
    }

    public function render()
    {
        return view('livewire.user-profile');
    }
}
