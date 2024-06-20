<div>
    <div class=" bg-black">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-white border-amber-400 border-8 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col">
                        <div class="text-center flex flex-col space-y-4">
                            <h1 class="text-3xl font-bold text-amber-400">Scoreboard</h1>
                            <p class="text-sm">Click any player's name to see their score history</p>
                        </div>
                        <div class="mt-8">
                            <table class="w-full text-lg">
                                <tbody>
                                    @foreach($players as $player)
                                        <tr>
                                            @if($player->id === $this->player->id)
                                                <td class="text-left font-bold text-amber-400">
                                                    <a href="{{ route('player.profile', $player) }}">
                                                        {{ $player->user->name }}
                                                    </a>
                                                </td>
                                                <td class="text-right font-bold text-amber-400">{{ $player->score }}</td>
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
                </div>
            </div>
        </div>
    </div>
</div>
