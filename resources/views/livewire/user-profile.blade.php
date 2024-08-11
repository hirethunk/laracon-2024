<section>
    <header>
        <h2 class="text-lg font-medium text-gold-900">
            {{ __('Profile Information') }}
        </h2>

        @if(! $this->user->is_approved)
            <p class="mt-1 font-sans text-md text-gold-500">
                {{ __("Your Name must match your Laracon Badge") }}
            </p>
        @endif
    </header>

   <div class="mt-6 space-y-6">
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" email="email" type="text" class="mt-1 block w-full" :value="old('email', $this->user->email)" required autofocus autocomplete="email" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input wire:model="name" id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $this->user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>
        @if(! $this->user->is_approved)
            <x-primary-button wire:click="updateName">{{ __('Update Name') }}</x-primary-button>
        @endif
    </div>
</section>
