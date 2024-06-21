@props([
    'state',
    'subHistory' => null,
])

<div>
    <ul role="list" class="text-white">
        @forelse ($state->getHistory($subHistory) as $history_item)
            @if($history_item->component)
                <x-history.custom-item
                    :dto="$history_item"
                    :time="$history_item->humanTime()"
                    :isLast="$loop->last"
                />
            @elseif($history_item->message)
                <x-history.item
                    :text="$history_item->message"
                    :time="$history_item->humanTime()"
                    :isLast="$loop->last"
                />
            @endif
        @empty
            <div class="text-center text-gray-400">No History Yet</div>
        @endforelse
    </ul>
</div>
