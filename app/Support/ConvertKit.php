<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;

class ConvertKit
{
    public function __construct(
        public ?string $apiSecret = null,
    ) {
        $this->apiSecret = $apiSecret ?? config('services.convertkit.api_secret');
    }

    public function addSubscriber(string $email, ?int $formId = null): ?array
    {
        $formId = $formId ?? config('services.convertkit.form_id');

        return Http::post("https://api.convertkit.com/v3/forms/{$formId}/subscribe", [
            'api_secret' => $this->apiSecret,
            'email' => $email,
        ])->json();
    }
}
