@props([
    'state',
    'subHistory' => null,
])

<div>
    <ul role="list" class="space-y-6 text-white">
        @foreach ($state->getHistory($subHistory) as $history_item)
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
        @endforeach
    </ul>
</div>
