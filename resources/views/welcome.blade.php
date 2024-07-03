<x-guest-layout>
    <div class="flex flex-col space-y-4">
    <p>TBD make this page good.</p>

    @if(Auth::user())
        <a href="{{ route('login') }}">
            <x-primary-button>
                Login
            </x-primary-button>
        </a>
    @else
        <a href="{{ route('dashboard') }}">
            <x-primary-button>
                Dashboard
            </x-primary-button>    
        </a>
    @endif

</x-guest-layout>