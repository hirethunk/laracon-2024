<div>
    <h2 class="w-full pt-8 text-xl">
        {{ $this->player->user->name }}
    </h2>

    <div class="flex w-full flex-row items-center pt-8 justify-between">
        <p class="w-full text-md">
            Score History
        </p>
        <p class="w-full text-right">
            Total
        </p>
    </div>

    <div class="mt-4">
        <x-history.feed :state="$this->player->state()" subHistory="default" />
    </div>
</div>
