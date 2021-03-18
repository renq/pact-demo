<?php

declare(strict_types=1);

namespace App\Animals;

use GuzzleHttp\Client;

use function json_decode;

class AnimalsApiClient
{
    private Client $client;
    private string $animalsApiUrl;

    public function __construct(Client $client, string $animalsApiUrl)
    {
        $this->client = $client;
        $this->animalsApiUrl = $animalsApiUrl;
    }

    public function getAnimals(): Animals
    {
        $result = $this->client->request('GET', $this->animalsApiUrl . '/api/animals', [
            'headers' => [
                'Content-Type' => 'application/json',
            ]
        ]);

        $body = json_decode((string)$result->getBody(), true);

        $animals = [];
        foreach ($body as $animal) {
            $animals[] = new Animal($animal['name'], $animal['species']);
        }

        return new Animals(...$animals);
    }
}
