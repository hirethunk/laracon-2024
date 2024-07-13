<x-guest-layout>
    <div class="flex flex-col space-y-4">
        <h2 class="text-3xl text-center">The Thunk Pyramid Scheme</p>

        <div class="flex flex-row justify-center space-x-4 mt-4">
            @if(Auth::user())
                <a href="{{ route('home') }}">
                    <x-primary-button>
                        Dashboard
                    </x-primary-button>
                </a>
            @else
                <a href="{{ route('register') }}">
                    <x-primary-button>
                        Register
                    </x-primary-button>
                </a>
                <a href="{{ route('login') }}">
                    <x-primary-button>
                        Login
                    </x-primary-button>
                </a>
            @endif
        </div>
    </div>
    <div class="py-8 font-normal text-sm">
        <h3 class="text-2xl pb-2">The rules are simple:</h3>
        <ol>
            <li>1. Join the game by talking to the man with the golden briefcase.</li>
            <li>2. You can upvote and downvote other players once per hour.</li>
            <li>3. The player with the highest score at 5pm on August 28 wins the $1,500 in the briefcase.</li>
            <li>4. There will be twists and turns. Keep an eye out for ways to win extra upvotes.</li>
        </ol>
        <div class="flex flex-col space-y-4">
            <h3 class="text-2xl mt-8">FAQ</h3>
            <div>
                <p class="italic">Is this a brazen scheme to build a mailing list and create a reason for you to come say hi to us at Laracon?</p>
                <p>Yes.</p>
            </div>
            <div>
                <p class="italic">But is it cheaper than buying a booth at a conference? </p>
                <p>Also yes.</p>
            </div>
            <div>
                <p class="italic">Are you actually giving $1,500 away? </p>
                <p>Take one look at that briefcase if you think we're messing around.</p>
            </div>
            <div>
                <p class="italic">It seems like there are a lot of ways to big-brain this game, team up with people, lie, cheat, steal, etc.?</p>
                <p>That's the spirit. The world is your oyster, my friend.</p>
            </div>
        </div>
    </div>
</x-guest-layout>
