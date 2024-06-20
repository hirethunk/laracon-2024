@props([
    'type',
    'amount',
    'voter_name',
    'score',
    'time',
    'isLast' => false,
])

@php
    if($type = 'ballot') {
        $amount > 0
            ? $text = 'Received upvote from '.$voter_name
            : $text = 'Received downvote from '.$voter_name;
    } else {
    
    }

    $amount > 0 
        ? $amount_string = '+'.$amount
        : $amount_string = $amount;
@endphp

<li class="relative flex gap-x-4 justify-between px-4 py-2">
    <div class="flex gap-x-2 items-center">
        <p @class([
            "flex-auto py-0.5 text-sm leading-5",
            "text-gold-500" => $amount > 0,
            "text-white" => $amount < 1,
        ])>
            {{$amount_string}}
        </p>
        <p class="flex-auto py-0.5 text-sm leading-5 text-gold-500">
        <p @class([
            "flex-auto py-0.5 text-sm leading-5",
            "text-gold-500" => $amount > 0,
            "text-white" => $amount < 1,
        ])>
            {{ $text }}
        </p>
        <time class="flex-none py-0.5 text-xs leading-5 text-gray-500">
            {{$time}}
        </time>
    </div>
    <div class="flex gap-x-2">
        <p @class([
            "flex-auto py-0.5 text-sm leading-5",
            "text-gold-500" => $amount > 0,
            "text-white" => $amount < 1,
        ])>
            {{$score}}
        </p>
    </div>
</li>
