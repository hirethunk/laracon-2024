<?php

namespace App\Events;

use App\Support\ConvertKit;
use Thunk\Verbs\Attributes\Hooks\Once;
use Thunk\Verbs\Event;

class UserSubscribedToNewsletter extends Event
{
    public function __construct(
        public string $email,
	    public ?string $first_name,
    ) {}

	#[Once]
    public function handle()
    {
        (new ConvertKit())->addSubscriber($this->email, $this->first_name);
    }
}
