<?php

namespace App\Tests;

use App\Gateway\CacheableNasaGateway;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class DangerControllerTest extends WebTestCase
{
    public function testThePageWarnsWhenEarthIsInDanger()
    {
        $client = static::createClient();

        $this->injectMockClientWithResponse(
            file_get_contents(__DIR__.'/fixtures/nasa_api_in_danger.json')
        );

        $client->request('GET', '/danger');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('body', 'Run you fools');
    }

    public function testThePageDoesNotWarnWhenEarthIsNotInDanger()
    {
        $client = static::createClient();

        $this->injectMockClientWithResponse(
            file_get_contents(__DIR__.'/fixtures/nasa_api_no_danger.json')
        );

        $client->request('GET', '/danger');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('body', 'Netflix & chill');
    }

    private function injectMockClientWithResponse(string $responseContent): void
    {
        static::$container->get(CacheInterface::class)
            ->delete(CacheableNasaGateway::CACHE_KEY);

        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse
            ->method('getContent')
            ->willReturn($responseContent);

        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        $mockHttpClient
            ->method('request')
            ->willReturn($mockResponse);

        self::$container->set(HttpClientInterface::class, $mockHttpClient);
    }
}
