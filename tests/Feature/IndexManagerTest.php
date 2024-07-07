<?php

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use OramaCloud\Manager\CloudManager;
use OramaCloud\Manager\IndexManager;

beforeEach(function () {
    // Reset capturedRequests before each test
    $this->capturedRequests = [];

    // Middleware to capture requests
    $captureMiddleware = function (callable $handler) {
        return function ($request, array $options) use ($handler) {
            $this->capturedRequests[] = $request;
            return $handler($request, $options);
        };
    };

    $mockResponse = new MockHandler([
        new Response(200, [], json_encode(['success' => true]))
    ]);

    $handlerStack = HandlerStack::create($mockResponse);
    $handlerStack->push($captureMiddleware, 'capture_middleware');
    $mockClient = new GuzzleClient(['handler' => $handlerStack]);

    $this->manager = new CloudManager('mock-api-key', $mockClient);
    $this->index = new IndexManager('mock-index', $this->manager);
});

describe('Index manager', function () {
    it('should empty the index', function () {
        // Empty index
        $result = $this->index->empty();
        expect($result)->toHaveKey('success');

        $lastRequest = end($this->capturedRequests);
        expect($lastRequest->getMethod())->toBe('POST');
        expect($lastRequest->getUri()->getPath())->toBe('/api/v1/webhooks/mock-index/snapshot');
        expect(json_decode($lastRequest->getBody()->getContents(), true))->toBe(null);
    });

    it('should insert a document', function () {
        $data = ['id' => 1, 'name' => 'John Doe'];
        // Insert document
        $result = $this->index->insert($data);
        expect($result)->toHaveKey('success');

        $lastRequest = end($this->capturedRequests);
        expect($lastRequest->getMethod())->toBe('POST');
        expect($lastRequest->getUri()->getPath())->toBe('/api/v1/webhooks/mock-index/notify');
        expect(json_decode($lastRequest->getBody()->getContents(), true))->toBe(['upsert' => $data]);
    });

    it('should update a document', function () {
        $data = ['id' => 1, 'name' => 'Jane Doe'];
        // Update document
        $result = $this->index->update($data);
        expect($result)->toHaveKey('success');

        $lastRequest = end($this->capturedRequests);
        expect($lastRequest->getMethod())->toBe('POST');
        expect($lastRequest->getUri()->getPath())->toBe('/api/v1/webhooks/mock-index/notify');
        expect(json_decode($lastRequest->getBody()->getContents(), true))->toBe(['upsert' => $data]);
    });

    it('should delete a document', function () {
        // Delete index
        $result = $this->index->delete(['id' => 1]);
        expect($result)->toHaveKey('success');

        $lastRequest = end($this->capturedRequests);
        expect($lastRequest->getMethod())->toBe('POST');
        expect($lastRequest->getUri()->getPath())->toBe('/api/v1/webhooks/mock-index/notify');
        expect(json_decode($lastRequest->getBody()->getContents(), true))->toBe(['remove' => ['id' => 1]]);
    });

    it('should snapshot the index', function () {
        $data = ['id' => 1, 'name' => 'John Doe'];
        // Snapshot index
        $result = $this->index->snapshot($data);
        expect($result)->toHaveKey('success');

        $lastRequest = end($this->capturedRequests);
        expect($lastRequest->getMethod())->toBe('POST');
        expect($lastRequest->getUri()->getPath())->toBe('/api/v1/webhooks/mock-index/snapshot');
        expect(json_decode($lastRequest->getBody()->getContents(), true))->toBe($data);
    });

    it('should deploy the index', function () {
        // Deploy index
        $result = $this->index->deploy();
        expect($result)->toHaveKey('success');

        $lastRequest = end($this->capturedRequests);
        expect($lastRequest->getMethod())->toBe('POST');
        expect($lastRequest->getUri()->getPath())->toBe('/api/v1/webhooks/mock-index/deploy');
        expect(json_decode($lastRequest->getBody()->getContents(), true))->toBe(null);
    });

    it('should check for pending operations', function () {
        // Check for pending operations
        $result = $this->index->hasPendingOperations();
        expect($result)->toBe(false);

        $lastRequest = end($this->capturedRequests);
        expect($lastRequest->getMethod())->toBe('POST');
        expect($lastRequest->getUri()->getPath())->toBe('/api/v1/webhooks/mock-index/has-data');
        expect(json_decode($lastRequest->getBody()->getContents(), true))->toBe(null);
    });
});
