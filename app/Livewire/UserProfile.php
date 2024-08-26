<?php

namespace App\Livewire;

use App\Events\UserNameUpdated;
use Livewire\Component;

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

        return redirect()->route('profile.edit');
    }

    public function render()
    {
        return view('livewire.user-profile');
    }
}
