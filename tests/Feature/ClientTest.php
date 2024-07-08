<?php

namespace Tests\Feature;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use OramaCloud\Client;
use OramaCloud\Client\Query;
use Tests\TestCase;

class ClientTest extends TestCase
{
    const API_ENDPOINT = 'mock-endpoint';
    const PUBLIC_API_KEY = 'mock-api-key';

    protected $capturedRequests;
    protected $httpClient;

    protected function setUp(): void
    {
        // Reset capturedRequests before each test
        $this->capturedRequests = [];

        // Middleware to capture requests
        $captureMiddleware = function ($handler) {
            return function ($request, $options) use ($handler) {
                $this->capturedRequests[] = $request;
                return $handler($request, $options);
            };
        };

        $mockResponse = new MockHandler([
            // search request
            new Response(200, [], json_encode([
                'hits' => [['id' => 2]],
                'elapsed' => 0.2,
                'count' => 1,
            ]))
        ]);

        $handlerStack = HandlerStack::create($mockResponse);
        $handlerStack->push($captureMiddleware, 'capture_middleware');
        $this->httpClient = new GuzzleClient(['handler' => $handlerStack]);
    }

    public function testBasicFulltextSearch()
    {
        $client = new Client([
            'api_key' => self::PUBLIC_API_KEY,
            'endpoint' => self::API_ENDPOINT,
        ], $this->httpClient);

        $result = $client->search(
            (new Query())
                ->term('red shoes')
                ->mode('fulltext')
                ->limit(10)
        );

        $this->assertArrayHasKey('hits', $result);
        $this->assertArrayHasKey('elapsed', $result);
        $this->assertArrayHasKey('count', $result);

        $this->assertGreaterThan(0, $result['count']);
        $this->assertLessThanOrEqual(10, count($result['hits']));

        $lastRequest = end($this->capturedRequests);
        $this->assertEquals('POST', $lastRequest->getMethod());
        $this->assertEquals(self::API_ENDPOINT . '/search', $lastRequest->getUri()->getPath());
    }
}
