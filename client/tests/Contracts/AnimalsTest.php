<?php

declare(strict_types=1);

namespace App\Tests\Contracts;

use App\Animals\Animal;
use App\Animals\Animals;
use App\Animals\AnimalsApiClient;
use GuzzleHttp\Client;
use PhpPact\Consumer\InteractionBuilder;
use PhpPact\Consumer\Model\ConsumerRequest;
use PhpPact\Consumer\Model\ProviderResponse;
use PhpPact\Standalone\MockService\MockServerEnvConfig;
use PHPUnit\Framework\TestCase;

class AnimalsTest extends TestCase
{
    public function testGetHelloString(): void
    {
//        $matcher = new Matcher();
        $request = new ConsumerRequest();
        $request
            ->setMethod('GET')
            ->setPath('/api/animals')
            ->addHeader('Content-Type', 'application/json');

        // Create your expected response from the provider.
        $response = new ProviderResponse();
        $response
            ->setStatus(200)
            ->addHeader('Content-Type', 'application/json')
            ->setBody([
                [
                    'species' => 'Penguin',
                    'name' => 'Kowalski',
                ],
                [
                    'species' => 'Lion',
                    'name' => 'Simba',
                ],
            ]);

        // Create a configuration that reflects the server that was started. You can create a custom MockServerConfigInterface if needed.
        $config  = new MockServerEnvConfig();
        $builder = new InteractionBuilder($config);
        $builder
            ->uponReceiving('A get request to /api/animals')
            ->with($request)
            ->willRespondWith($response);

        $guzzle = new Client();
        $apiClient = new AnimalsApiClient($guzzle, 'http://localhost:7200');

        $result = $apiClient->getAnimals();
        $builder->verify(); // This will verify that the interactions took place.

        $this->assertEquals(new Animals(
            new Animal('Kowalski', 'Penguin'),
            new Animal('Simba', 'Lion'),
        ), $result);
    }
}
