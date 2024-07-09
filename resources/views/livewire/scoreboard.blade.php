<div>
    <x-card>
        <div class="flex flex-col">
            <div class="text-center flex flex-col space-y-4">
                <h1 class="text-3xl font-bold text-gold-500">Scoreboard</h1>
                <p class="text-sm">Click any player's name to see their score history</p>
            </div>
            <div class="mt-8">
                <table class="w-full text-lg">
                    <tbody>
                        @foreach($players as $player)
                            <tr>
                                @if($player->id === $this->player->id)
                                    <td class="text-left font-bold text-gold-900">
                                        <a href="{{ route('player.profile', $player) }}">
                                            {{ $player->user->name }}
                                        </a>
                                    </td>
                                    <td class="text-right font-bold text-gold-900">{{ $player->score }}</td>
                                @else
                                <td class="text-left">
                                        <a href="{{ route('player.profile', $player) }}">
                                            {{ $player->user->name }}
                                        </a>
                                    </td>
                                    <td class="text-right">{{ $player->score }}</td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </x-card>
</div>
