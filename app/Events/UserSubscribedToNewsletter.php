<?php

namespace App\Events;

use App\Support\ConvertKit;
use Thunk\Verbs\Event;

class UserSubscribedToNewsletter extends Event
{
    public function __construct(
        public string $email,
    ) {}

    public function handle()
    {
        (new ConvertKit())->addSubscriber($this->email, env('CONVERTKIT_FORM_ID'));
    }
}
