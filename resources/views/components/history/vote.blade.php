@props([
    'type',
    'amount',
    'voter_name',
    'score',
    'time',
    'isLast' => false,
])

@php
    $text = match($type) {
        'inherited' => 'Inherited from '.$voter_name,
        'buddy-system-reward' => 'Buddy System Reward',
        'referred' => 'Bonus for being referred',
        'got-referred' => 'Bonus for referring new player',
        'invalid-secret-code' => 'Invalid Secret Code',
        'reused-secret-code' => 'Tried to re-use a secret code',
        'secret-code-reward' => 'Played a secret code',
        'ally-connection' => 'Connected with a secret ally',
        'prisoners-dilemma-nice-nice' => 'Cooperated with ally',
        'prisoners-dilemma-nasty-nasty' => 'Failed to cooperate with ally',
        'prisoners-dilemma-nasty-nice' => 'Double crossed their ally',
        'resigned' => 'Resigned and gave their score to '.$voter_name,
        default => $amount > 0
        ? $text = 'Upvoted by '.$voter_name
        : $text = 'Downvoted by '.$voter_name
    };

    $amount > 0
        ? $amount_string = '+'.$amount
        : $amount_string = $amount;
@endphp

<li class="relative flex gap-x-2 justify-between items-start text-sm py-2">
    <!-- Left -->
    <div class="flex items-start gap-x-2">
        <p @class([
            "flex-auto py-0.5 leading-5 min-w-5",
            "text-gold-500" => $amount > 0,
            "text-white" => $amount < 1,
        ])>
            <span class="tabular-nums flex justify-center">
                {{ $amount_string }}
            </span>
        </p>

        <!-- Center (with two rows) -->
        <div class="flex flex-col">
            <p @class([
                "flex-auto py-0.5 leading-5",
                "text-gold-500" => $amount > 0,
                "text-white" => $amount < 1,
            ])>
                {{ $text }}
            </p>
            <time class="py-0.5 text-sm leading-5 text-neutral-500">
                {{ $time }}
            </time>
        </div>
    </div>

    <!-- Right -->
    <div class="flex gap-x-2">
        <p @class([
            "flex-auto py-0.5 leading-5",
            "text-gold-500" => $score > 0,
            "text-white" => $score < 1,
        ])>
            <span class="tabular-nums">
                {{ $score }}
            </span>
        </p>
    </div>
</li>
