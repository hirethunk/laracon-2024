<div>
    <x-card>
        <h2 class="text-lg text-gold-500 font-serif font-medium">
            Scoreboard
        <h2>
        <p class="mt-1 pb-4 text-sm text-neutral-300">
            Click any player's name to see their score history
        </p>

        <div class="flex items-center gap-x-4 w-full">
            <input
                placeholder="Find Player..."
                class="w-full bg-black border-2 rounded-lg shadow-sm outline-none focus:border-transparent focus:outline-none focus:ring-2 focus:ring-gold-500-light active:ring-2 active:ring-gold-500-light text-neutral-300 focus:rounded-md"
                wire:model.live="search"
            />
        </div>

        <div class="mt-4 overflow-auto max-h-96">
            <table class="w-full">
                <tbody>
                    @foreach($this->options as $player)
                        <tr>
                            @if($player['id'] === $this->player->id)
                                <td class="text-left text-gold-500">
                                    <a href="{{ route('player.profile', $player['id']) }}">
                                        {{ $player['name'] }}
                                    </a>
                                </td>
                                <td class="text-right text-gold-500">
                                    @if($player['is_active'])
                                        <span class="tabular-nums">{{ $player['score'] }}</span>
                                    @else
                                        <span class="text-neutral-300">Resigned</span>
                                    @endif
                                </td>
                            @else
                                <td class="text-left">
                                    <a href="{{ route('player.profile', $player['id']) }}">
                                        {{ $player['name'] }}
                                    </a>
                                </td>
                                <td class="text-right">
                                    @if($player['is_active'])
                                        <span class="tabular-nums">{{ $player['score'] }}</span>
                                    @else
                                        <span class="text-neutral-300">Resigned</span>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>
</div>
