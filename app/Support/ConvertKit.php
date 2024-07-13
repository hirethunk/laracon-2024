<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;

class ConvertKit
{
    public function __construct(
        public ?string $apiSecret = null,
    ) {
        $this->apiSecret = $apiSecret ?? env('CONVERT_KIT_API_SECRET');
    }

    public function addSubscriber(string $email, ?int $formId = null): ?array
    {
        $formId = $formId ?? env('CONVERTKIT_FORM_ID');

        return Http::post("https://api.convertkit.com/v3/forms/{$formId}/subscribe", [
            'api_secret' => $this->apiSecret,
            'email' => $email,
        ])->json();
    }
}
