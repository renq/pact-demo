<?php

declare(strict_types=1);

use GuzzleHttp\Psr7\Uri;
use PhpPact\Standalone\ProviderVerifier\Model\VerifierConfig;
use PhpPact\Standalone\ProviderVerifier\Verifier;

class ApiContractTest extends TestCase
{
    public function testApi(): void
    {
        $config = new VerifierConfig();
        $config
            ->setProviderName('animals provider') // Providers name to fetch.
            ->setProviderVersion('1.0.0') // Providers version.
            ->setProviderBaseUrl(new Uri(getenv('PACT_PROVIDER_BASE_URL'))) // URL of the Provider.
            ->setBrokerUri(new Uri(getenv('PACT_BROKER_URI')))
            ->setProviderVersion('1.0.1')
            ->setPublishResults(true)
        ;

        $verifier = new Verifier($config);
        $verifier->verifyAll(); // The tag is option. If no tag is set it will just grab the latest.

        $this->assertTrue(true, 'Pact Verification has failed.');
    }
}
