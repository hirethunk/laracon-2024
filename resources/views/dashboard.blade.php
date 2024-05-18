<x-app-layout>
    <x-slot name="header" class="">
        <h2 class="text-xl font-semibold leading-tight">
            {{ __("Let's make some money") }}
        </h2>
    </x-slot>

    @if(Auth::user()->player)
        <livewire:voting-card />
    @else
        <div class="py-12 bg-black">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden text-white border-8 shadow-sm border-amber-400 sm:rounded-lg">
                    <div class="p-6">
                        Before you can join the game, the man with the golden suit must approve your account. Find the man with the golden suit.
                    </div>
                    <div class="p-6">
                        He will not approve you unless your account name matches the name on your Laracon badge. If you need to change your name to match your badge, do that below.
                    </div>

                    <x-color class="bg-amber-400" label="Amber-400"/>
                    <x-color class="bg-gradient-to-r from-[#bf9a47] via-[#Ffe233] via-25% via-[#c7801d] via-25% via-[#ffe233] via-75% to-[#bf9a47]" label="Gold Gradient Example" />
                    <x-color class="bg-gold-500" label="gold-500" />
                    <x-color class="bg-gold-600" label="gold-600" />
                    <x-color class="bg-gold-700" label="gold-700" />
                    <x-color class="bg-gold-800" label="gold-800" />
                    <x-color class="bg-gold-900" label="gold-900" />
                    <x-color class="bg-gradient-to-r from-gold-500 via-gold-600 to-gold-700" label="Rich Gold Gradient" />

                    {{--text--}}
                    <div class="text-gold-500 text-shadow">Text</div>

                    {{--filters--}}
                    <div class="text-gold-500 filter brightness-125 blur-sm ...">Filter brightness blur</div>

                    {{--layering--}}
                    <div class="relative border border-white p-20">
                        <x-color class="absolute top-0 left-0 bg-gold-500 opacity-75"/>
                        <x-color class="absolute top-0 left-0 bg-gold-700 opacity-50"/>
                    </div>

                    {{--text-shadow--}}
                    <div class="text-gold-500" style="text-shadow: 1px 1px 2px #87711E;">Opulent Gold Text</div>

                    {{--shimmer--}}
                    <x-color class="shimmer bg-gold-500" label="Shimmering"/>

                    {{--glow--}}
                    <div class="text-gold-500" style="text-shadow: 0 0 10px #ffd700, 0 0 20px #ffd700, 0 0 30px #ffd700, 0 0 40px #ffd700, 0 0 50px #ffd700, 0 0 60px #ffd700, 0 0 70px #ffd700;">
                        Luxurious Gold Text
                    </div>

                    {{--gradient-text--}}
                    <div class="gradient-text brightness-125 text-4xl font-bold">
                        Gradient Text
                    </div>

                    {{--sparkle--}}
                    <div class="sparkle text-4xl font-bold">
                        Sparkling Gold Text
                    </div>

                    {{--garrish text--}}
                    <p class="garish-text brightness-125 text-4xl font-bold">Opulent Gold Text</p>

                    <x-color class="gold-gradient" label="gold gradient"/>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
