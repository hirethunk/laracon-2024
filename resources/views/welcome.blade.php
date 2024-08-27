<x-welcome-layout>
    <x-card>
        <h2 class="text-3xl text-center font-serif font-bold mt-2 text-gold-500">
            The Thunk Pyramid Scheme
        </h2>

        <div class="flex flex-row justify-center space-x-4 mt-6">
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
    </x-card>

    <div class="pt-8 font-normal text-sm text-white">
        <h3 class="text-2xl pb-4">The rules are simple:</h3>
        <ol class="space-y-2">
            <li class="flex items-start">
                <p class="text-gold-100-dark pr-2">
                    <x-icons.briefcase class="w-5 h-5 pt-0.5"/>
                </p>
                <p>Join the game by talking to the 
                <span class="text-indigo-300 font-bold">
                    <a href="https://x.com/johnrudolphdrex" target="_blank">
                        man
                    </a>
                </span>
                 with the <span class="text-gold-500">plastic bag full of $1,500.</span></p>
            </li>
            <li class="flex items-start">
                <p class="text-gold-100-dark pr-2">
                    <x-icons.hand-thumb-up class="w-5 h-5 pt-0.5"/>
                </p>
                <p>You can upvote and downvote other players once per hour.</p>
            </li>
            <li class="flex items-start">
                <p class="text-gold-100-dark pr-2">
                    <x-icons.money class="w-5 h-5 pt-0.5"/>
                </p>
                <p>The player with the highest score at 5pm on August 28 wins the <span class="text-gold-500">plastic bag full of $1,500.</span></p>
            </li>
            <li class="flex items-start">
                <p class="text-gold-100-dark pr-2">
                    <x-icons.eye class="w-5 h-5 pt-0.5"/>
                </p>
                <p>There will be twists and turns. Keep an eye out for ways to win extra upvotes.</p>
            </li>
        </ol>
        <div x-data="{ show: false }">
            <button class="flex items-center gap-4 mt-8" x-on:click="show = !show" >
                <h3 class="text-2xl">FAQ</h3>

                <div x-show="! show" x-cloak class="text-gold-500"><x-icons.chevron-down/></div>
                <div x-show="show" x-cloak class="text-gold-500"><x-icons.chevron-up/></div>
            </button>
            <div x-show="show" x-cloak x-collapse class="pt-4 flex flex-col space-y-4">
                <div>
                    <p class="italic">Is this a brazen scheme to build a mailing list and create a reason for you to come say hi to us at Laracon?</p>
                    <p>Yes.</p>
                </div>
                <div>
                    <p class="italic">Are you actually giving away <span class="text-gold-500">$1,500</span>?</p>
                    <p>Take one look at that 
                    <button class="text-indigo-400 font-bold">
                        <a href="https://x.com/johnrudolphdrex/status/1825945077044359672" target="_blank">
                            dough
                        </a>
                    </button>
                    
                     if you think we're messing around.</p>
                </div>
                <div>
                    <p class="italic">It seems like there are a lot of ways to come up with big-brain strategies, team up with people, lie, etc.?</p>
                    <p>That's the spirit. The world is your oyster, my friend.</p>
                </div>
            </div>
        </div>
    </div>
</x-welcome-layout>
