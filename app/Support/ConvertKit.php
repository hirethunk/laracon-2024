<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;

class ConvertKit
{
    public function __construct(
        public ?string $apiKey = null,
    ) {
        $this->apiKey ??= config('services.convertkit.api_secret');
    }

    public function addSubscriber(string $email, ?string $first_name, ?int $formId = null): ?array
    {
        $formId ??= config('services.convertkit.form_id');

        return Http::post("https://api.convertkit.com/v3/forms/{$formId}/subscribe", array_filter([
            'api_key' => $this->apiKey,
            'email' => $email,
	        'first_name' => $first_name,
        ]))->json();
    }
}
