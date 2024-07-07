<?php

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use OramaCloud\Client;
use OramaCloud\Client\Query;

const API_ENDPOINT = 'mock-endpoint';
const PUBLIC_API_KEY = 'mock-api-key';

test('basic fulltext search', function () {
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
        'api_key' => PUBLIC_API_KEY,
        'endpoint' => API_ENDPOINT,
    ], $mockClient);

    $result = $client->search(
        (new Query())
            ->term('red shoes')
            ->mode('fulltext')
            ->limit(10)
    );

    expect($result)->toHaveKey('hits');
    expect($result)->toHaveKey('elapsed');
    expect($result)->toHaveKey('count');

    expect($result['count'])->toBeGreaterThan(0);
    expect(count($result['hits']))->toBeLessThanOrEqual(10);
});
