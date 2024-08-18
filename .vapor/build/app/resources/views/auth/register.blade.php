<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="font-normal">
        @csrf

        <!-- Name -->
        <div>
            <div class="flex flex-row space-x-2">
                <x-input-label for="name" :value="__('Name')" />
                <p class="italic text-sm text-neutral-100">(must match your Laracon badge)</p>
            </div>
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="text-sm underline rounded-md text-neutral-500 hover:text-neutral-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gold-500-light" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4 font-normal">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <div class="mt-4 text-xs" x-data="{ open: false }"">
        <p class="text-center font-normal">
            By registering, you agree to our
            <button class="text-link" x-on:click="open = ! open">Terms of Service</button>.
        </p>

        {{-- modal --}}
        <div
        x-show="open"
        style="display: none"
        x-on:keydown.escape.prevent.stop="open = false"
        role="dialog"
        aria-modal="true"
        x-id="['modal-title']"
        :aria-labelledby="$id('modal-title')"
        class="fixed inset-0 z-10 overflow-y-auto"
    >
            <!-- Overlay -->
            <div x-show="open" x-transition.opacity class="fixed inset-0 bg-black bg-opacity-50"></div>

            <!-- Panel -->
            <div
                x-show="open" x-transition
                x-on:click="open = false"
                class="relative flex min-h-screen items-center justify-center p-4"
            >
                <div
                    x-on:click.stop
                    x-trap.noscroll.inert="open"
                    class="relative w-full max-w-sm overflow-y-auto rounded-xl p-12 shadow-lg bg-neutral-900"
                >
                    <!-- Title -->
                    <div class="flex flex-row justify-between items-center">
                        <h2 class="text-2xl text-gold-500 font-bold" :id="$id('modal-title')">Terms of Service</h2>
                        <button x-on:click="open = false" class="text-white font-bold text-2xl">&times;</button>
                    </div>

                    <!-- Content -->
                    <ul class="text-white font-normal mt-4 space-y-2">
                        <li>1. You will reach out to <a href="https://thunk.dev" class="text-link">Thunk</a> when you need to hire world class Laravel developers and product managers.</li>
                        <li>2. Thunk can email you to keep in touch. We hate email too, and solemnly swear not to email more than once a month.</li>
                        <li>3. You promise to channel your inner scumbag, and play this game in the most dishonorable way imagineable.</li>
                        <li>4. You agree that while DDD is pretty smart in theory, Verbs is the best way to do event sourcing.</li>
                        <li>5. You agree to cheer very loudly when Daniel Coulbourne gives his talk.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
