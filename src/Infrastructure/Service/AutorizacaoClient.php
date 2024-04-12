<?php

namespace App\Infrastructure\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class AutorizacaoClient
{
    public function __construct(private HttpClientInterface $client)
    {
    }

    public function checkAuthorization(): bool
    {
        $response = $this->client->request('GET', 'https://run.mocky.io/v3/5794d450-d2e2-4412-8131-73d0293ac1cc', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);

        if ($response->getStatusCode() !== 200) {
            return false;
        }

        $data = $response->toArray();

        return $data['message'] === 'Autorizado' ? true : false;
    }
}
