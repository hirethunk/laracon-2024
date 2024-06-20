@props([
    'type',
    'amount',
    'voter_name',
    'score',
    'time',
    'isLast' => false,
])

@php
    if($type === 'ballot') {
        $amount > 0
            ? $text = 'Upvoted by '.$voter_name
            : $text = 'Downvoted by '.$voter_name;
    } elseif($type === 'resignation') {
        $text = 'Inherited from '.$voter_name;
    }

    $amount > 0 
        ? $amount_string = '+'.$amount
        : $amount_string = $amount;
@endphp

<li class="relative flex gap-x-4 justify-between items-center text-xs py-2">
    <div class="flex gap-x-2 items-center">
        <p @class([
            "flex-auto py-0.5 leading-5",
            "text-gold-500" => $amount > 0,
            "text-white" => $amount < 1,
        ])>
            {{$amount_string}}
        </p>
        <p class="flex-auto py-0.5 leading-5 text-gold-500">
        <p @class([
            "flex-auto py-0.5 leading-5",
            "text-gold-500" => $amount > 0,
            "text-white" => $amount < 1,
        ])>
            {{ $text }}
        </p>
        <time class="flex-none py-0.5 text-xs items-center leading-5 text-gray-500">
            {{$time}}
        </time>
    </div>
    <div class="flex gap-x-2">
        <p @class([
            "flex-auto py-0.5 leading-5",
            "text-gold-500" => $score > 0,
            "text-white" => $score < 1,
        ])>
            {{$score}}
        </p>
    </div>
</li>
