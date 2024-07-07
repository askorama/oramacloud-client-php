<?php

namespace Tests\Feature;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use OramaCloud\Manager\CloudManager;
use OramaCloud\Manager\IndexManager;
use Tests\TestCase;

class IndexManagerTest extends TestCase
{
    protected $manager;
    protected $index;
    protected $capturedRequests;

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
            new Response(200, [], json_encode(['success' => true]))
        ]);

        $handlerStack = HandlerStack::create($mockResponse);
        $handlerStack->push($captureMiddleware, 'capture_middleware');
        $mockClient = new GuzzleClient(['handler' => $handlerStack]);

        $this->manager = new CloudManager('mock-api-key', $mockClient);
        $this->index = new IndexManager('mock-index', $this->manager);
    }

    public function testShouldEmptyTheIndex()
    {
        // Empty index
        $this->index->empty();
        $lastRequest = end($this->capturedRequests);
        $this->assertEquals('POST', $lastRequest->getMethod());
        $this->assertEquals('/api/v1/webhooks/mock-index/snapshot', $lastRequest->getUri()->getPath());
        $this->assertNull(json_decode($lastRequest->getBody()->getContents(), true));
    }

    public function testShouldInsertADocument()
    {
        $data = ['id' => 1, 'name' => 'John Doe'];
        // Insert document
        $this->index->insert($data);
        $lastRequest = end($this->capturedRequests);
        $this->assertEquals('POST', $lastRequest->getMethod());
        $this->assertEquals('/api/v1/webhooks/mock-index/notify', $lastRequest->getUri()->getPath());
        $this->assertEquals(['upsert' => $data], json_decode($lastRequest->getBody()->getContents(), true));
    }

    public function testShouldUpdateADocument()
    {
        $data = ['id' => 1, 'name' => 'Jane Doe'];
        // Update document
        $this->index->update($data);
        $lastRequest = end($this->capturedRequests);
        $this->assertEquals('POST', $lastRequest->getMethod());
        $this->assertEquals('/api/v1/webhooks/mock-index/notify', $lastRequest->getUri()->getPath());
        $this->assertEquals(['upsert' => $data], json_decode($lastRequest->getBody()->getContents(), true));
    }

    public function testShouldDeleteADocument()
    {
        // Delete index
        $this->index->delete(['id' => 1]);
        $lastRequest = end($this->capturedRequests);
        $this->assertEquals('POST', $lastRequest->getMethod());
        $this->assertEquals('/api/v1/webhooks/mock-index/notify', $lastRequest->getUri()->getPath());
        $this->assertEquals(['remove' => ['id' => 1]], json_decode($lastRequest->getBody()->getContents(), true));
    }

    public function testShouldSnapshotTheIndex()
    {
        $data = ['id' => 1, 'name' => 'John Doe'];
        // Snapshot index
        $this->index->snapshot($data);
        $lastRequest = end($this->capturedRequests);
        $this->assertEquals('POST', $lastRequest->getMethod());
        $this->assertEquals('/api/v1/webhooks/mock-index/snapshot', $lastRequest->getUri()->getPath());
        $this->assertEquals($data, json_decode($lastRequest->getBody()->getContents(), true));
    }

    public function testShouldDeployTheIndex()
    {
        // Deploy index
        $this->index->deploy();
        $lastRequest = end($this->capturedRequests);
        $this->assertEquals('POST', $lastRequest->getMethod());
        $this->assertEquals('/api/v1/webhooks/mock-index/deploy', $lastRequest->getUri()->getPath());
        $this->assertNull(json_decode($lastRequest->getBody()->getContents(), true));
    }

    public function testShouldCheckForPendingOperations()
    {
        // Check for pending operations
        $this->index->hasPendingOperations();
        $lastRequest = end($this->capturedRequests);
        $this->assertEquals('POST', $lastRequest->getMethod());
        $this->assertEquals('/api/v1/webhooks/mock-index/has-data', $lastRequest->getUri()->getPath());
        $this->assertNull(json_decode($lastRequest->getBody()->getContents(), true));
    }
}
