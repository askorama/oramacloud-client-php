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

    public function testBasicFulltextSearch()
    {
        // Create a mock handler and queue a response.
        $mock = new MockHandler([
            // initial request to get the collect URL
            new Response(200, [], json_encode([
                'collectUrl' => 'mock-url',
                'deploymentID' => 'mock-deployment-id',
                'index' => 'mock-index',
            ])),
            // search request
            new Response(200, [], json_encode([
                'hits' => [['id' => 2]],
                'elapsed' => 0.2,
                'count' => 1,
            ])),
            // telemetry data collection
            new Response(200, [], json_encode([
                'message' => 'Telemetry data collected successfully',
            ])),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $mockClient = new GuzzleClient(['handler' => $handlerStack]);

        $client = new Client([
            'api_key' => self::PUBLIC_API_KEY,
            'endpoint' => self::API_ENDPOINT,
        ], $mockClient);

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
    }
}
