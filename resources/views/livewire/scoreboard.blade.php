<div>
    <x-card>
        <div class="flex flex-col">
            <div class="text-center">
                <h1 class="text-3xl font-bold leading-tight cinzel text-gold-500">Scoreboard</h1>
            </div>
            <div class="mt-8">
                <table class="w-full text-lg">
                    <tbody>
                        @foreach($players as $player)
                            <tr>
                                @if($player->id === $this->player->id)
                                    <td class="text-left text-gold-900">{{ $player->user->name }}</td>
                                    <td class="text-right text-gold-900">{{ $player->score }}</td>
                                @else
                                    <td class="text-left">{{ $player->user->name }}</td>
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
