<?php

namespace OramaCloud\Manager;

use GuzzleHttp\Client as HttpClient;
use OramaCloud\Manager\IndexManager;

class CloudManager
{
    private $indexId;
    private $apiKey;
    private $http;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
        $this->http = new HttpClient();
    }

    public function index(string $indexId): IndexManager
    {
        return new IndexManager($indexId, $this);
    }

    public function setIndexId(string $id): void
    {
        $this->indexId = $id;
    }

    public function callIndexWebhook(string $endpoint, $payload = null)
    {
        $config = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->apiKey
            ]
        ];

        if ($payload) {
            $config['body'] = json_encode($payload);
        }

        $response = $this->http->request('POST', $this->getEndpoint($endpoint), $config);

        return json_decode($response->getBody()->getContents());
    }

    private function getEndpoint(string $endpoint): string
    {
        return str_replace('{indexID}', $this->indexId, $endpoint);
    }
}
