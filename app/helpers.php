<?php

use Glhd\Bits\Bits;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Uid\AbstractUid;
use Thunk\Verbs\Event;
use Thunk\Verbs\State;

function id(Event|State|Model|Bits|UuidInterface|AbstractUid|int|string|null $target): int|string|null
{
    return match (true) {
        $target instanceof Event, $target instanceof State => $target->id,
        $target instanceof Model => $target->getKey(),
        $target instanceof Bits => $target->id(),
        $target instanceof UuidInterface => $target->toString(),
        $target instanceof AbstractUid => $target->toString(),
        is_int($target), is_string($target), is_null($target) => $target,
    };
}

/**
 * @template TState of \Thunk\Verbs\State
 *
 * @param  class-string<TState>  $fqcn
 * @return TState
 */
function state(State|int|null $target, string $fqcn): ?State
{
    return match (true) {
        is_int($target) => $fqcn::load($target),
        $target instanceof $fqcn => $target,
        is_null($target) => null,
        default => throw new InvalidArgumentException("State must be an integer or of type '{$fqcn}'."),
    };
}
