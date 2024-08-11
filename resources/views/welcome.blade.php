<x-welcome-layout>
    <x-card>
        <h2 class="text-3xl text-center font-serif font-bold mt-2 text-gold-500">
            The Thunk Pyramid Scheme
        </h2>

        <div class="flex flex-row justify-center space-x-4 mt-6">
            @if(Auth::user())
                <a href="{{ route('home') }}">
                    <x-primary-button color="gold">
                        Dashboard
                    </x-primary-button>
                </a>
            @else
                <a href="{{ route('register') }}">
                    <x-primary-button color="gold">
                        Register
                    </x-primary-button>
                </a>
                <a href="{{ route('login') }}">
                    <x-primary-button color="gold">
                        Login
                    </x-primary-button>
                </a>
            @endif
        </div>
    </x-card>

    <div class="pt-8 font-normal text-sm text-white">
        <h3 class="text-2xl pb-4">The rules are simple:</h3>
        <ol class="list-decimal marker:text-gold-100-dark px-4 space-y-2">
        {{-- marker:text-[#fff1c8] --}}
            <li>Join the game by talking to the man with the golden briefcase.</li>
            <li>You can upvote and downvote other players once per hour.</li>
            <li>The player with the highest score at 5pm on August 28 wins the <span class="text-gold-500">$1,500</span> in the briefcase.</li>
            <li>There will be twists and turns. Keep an eye out for ways to win extra upvotes.</li>
        </ol>
        <div x-data="{ show: false }">
            <button class="flex items-center gap-4 mt-8" x-on:click="show = !show" >
                <h3 class="text-2xl">FAQ</h3>

                <div x-show="! show" class="text-gold-500"><x-icons.chevron-down/></div>
                <div x-show="show" class="text-gold-500"><x-icons.chevron-up/></div>
            </button>
            <div x-show="show" x-collapse class="pt-4 flex flex-col space-y-4">
                <div>
                    <p class="italic">Is this a brazen scheme to build a mailing list and create a reason for you to come say hi to us at Laracon?</p>
                    <p>Yes.</p>
                </div>
                <div>
                    <p class="italic">But is it cheaper than buying a booth at a conference? </p>
                    <p>Also yes.</p>
                </div>
                <div>
                    <p class="italic">Are you actually giving <span class="text-gold-500">$1,500</span> away?</p>
                    <p>Take one look at that briefcase if you think we're messing around.</p>
                </div>
                <div>
                    <p class="italic">It seems like there are a lot of ways to big-brain this game, team up with people, lie, cheat, steal, etc.?</p>
                    <p>That's the spirit. The world is your oyster, my friend.</p>
                </div>
            </div>
        </div>
    </div>
</x-welcome-layout>
