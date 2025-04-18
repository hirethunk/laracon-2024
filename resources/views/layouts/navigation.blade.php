<nav x-data="{ open: false }"
    :class="{
        'border-y-2 border-b-transparent rounded-b': open,
        'border-y-2': ! open
    }"
    class="border-neutral-900 relative"
>
        <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 relative">
            <div class="flex z-10">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <x-application-logo />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex text-2xl font-bold leading-tight text-center font-serif">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home') || request()->routeIs('player-dashboard')">
                        {{ __('home') }}
                    </x-nav-link>
                </div>

                @foreach(collect(Auth::user()->state()->is_admin_for) as $game_id)
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex text-2xl font-bold leading-tight text-center font-serif">
                        <x-nav-link :href="route('admin-dashboard', $game_id)" :active="request()->routeIs('admin-dashboard', $game_id)">
                            Admin for {{ App\Models\Game::find($game_id)->name }}
                        </x-nav-link>
                    </div>
                @endforeach
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 z-10">
                <x-dropdown align="right" width="48" contentClasses="bg-gold-500">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 rounded-md text-gold-500 font-bold font-serif hover:text-neutral-700 focus:outline-none transition ease-in-out duration-150 lowercase">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden z-10">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white font-extrabold hover:text-gold-500-light focus:text-gold-500-light focus:outline-none active:text-gold-500-dark transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'absolute z-20 block w-full shadow-lg drop-shadow-[0_1px_1px_rgba(220,220,220,0.5)]': open, 'hidden': ! open}" class="hidden sm:hidden bg-black">
        <div class="py-2 space-y-1">
            <x-responsive-nav-link class="hover:bg-gold-100" :href="route('home')" :active="request()->routeIs('home') || request()->routeIs('player-dashboard')">
                {{ __('Home') }}
            </x-responsive-nav-link>

            @foreach(collect(Auth::user()->state()->is_admin_for) as $game_id)
                <x-responsive-nav-link class="hover:bg-gold-100" :href="route('admin-dashboard', $game_id)" :active="request()->routeIs('admin-dashboard', $game_id)">
                    Admin for {{ App\Models\Game::find($game_id)->name }}
                </x-responsive-nav-link>
            @endforeach
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-t-white">
            <div class="px-4">
                <div class="font-medium text-base text-neutral-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-neutral-400">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1 mb-1">
                <x-responsive-nav-link class="hover:bg-gold-100" :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link class="hover:bg-gold-100" :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
