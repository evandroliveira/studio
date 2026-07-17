<?php

namespace App\Support;

use Google\Client as GoogleClient;
use RuntimeException;

class GoogleTokenVerifier
{
    public function verify(string $credential): array
    {
        $clientId = trim((string) config('services.google.client_id'));

        if ($clientId === '') {
            throw new RuntimeException('GOOGLE_CLIENT_ID nao configurado.');
        }

        $payload = (new GoogleClient(['client_id' => $clientId]))->verifyIdToken($credential);

        if (! is_array($payload) || $payload === []) {
            throw new RuntimeException('Token Google invalido.');
        }

        return $payload;
    }
}