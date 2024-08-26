<?php

namespace App\Events;

use App\Support\ConvertKit;
use Illuminate\Support\Facades\App;
use Thunk\Verbs\Attributes\Hooks\Once;
use Thunk\Verbs\Event;

class UserSubscribedToNewsletter extends Event
{
    public function __construct(
        public string $email,
    ) {}

    #[Once]
    public function handle()
    {
        if (! App::isProduction()) {
            return;
        }

        dispatch(fn () => (new ConvertKit)->addSubscriber($this->email, config('services.convertkit.form_id')));
    }
}
