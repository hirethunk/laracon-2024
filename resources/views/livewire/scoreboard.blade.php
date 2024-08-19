<div>
    <x-card>
        <h2 class="text-lg text-gold-500 font-serif font-medium">
            Scoreboard
        <h2>
        <p class="mt-1 pb-4 text-sm text-neutral-300">
            Click any player's name to see their score history
        </p>
        <table class="w-full">
            <tbody>
                @foreach($players as $player)
                    <tr>
                        @if($player->id === $this->player->id)
                            <td class="text-left text-gold-500">
                                <a href="{{ route('player.profile', $player) }}">
                                    {{ $player->user->name }}
                                </a>
                            </td>
                            <td class="text-right text-gold-500">
                                @if($player->is_active)
                                    {{ $player->score }}
                                @else
                                    <span class="text-neutral-300">Resigned</span>
                                @endif
                            </td>
                        @else
                        <td class="text-left">
                                <a href="{{ route('player.profile', $player) }}">
                                    {{ $player->user->name }}
                                </a>
                            </td>
                            <td class="text-right">
                                @if($player->is_active)
                                    {{ $player->score }}
                                @else
                                    <span class="text-neutral-300">Resigned</span>
                                @endif
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-card>
</div>
