<?php

namespace OramaCloud\Manager;

use GuzzleHttp\Client as HttpClient;
use OramaCloud\Exceptions\IndexManagerException;
use OramaCloud\Manager\IndexManager;

class CloudManager
{
    private $indexId;
    private $apiKey;
    private $http;

    public function __construct($apiKey, $http = null)
    {
        $this->apiKey = $apiKey;
        $this->http = is_null($http) ? new HttpClient() : $http;
    }

    public function setIndexId(string $id): void
    {
        $this->indexId = $id;
    }

    public function callIndexWebhook(string $endpoint, $payload = null)
    {
        if (!$this->indexId) {
            throw new IndexManagerException('Index ID is not set');
        }

        try {
            $config['headers'] = [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->apiKey
            ];

            if (!is_null($payload)) {
                $config['body'] = json_encode($payload);
            }

            $response = $this->http->request('POST', $this->getEndpoint($endpoint), $config);

            if ($response->getStatusCode() !== 200 && $response->getStatusCode() !== 201) {
                throw new IndexManagerException('Error calling webhook: ' . $response->getBody()->getContents());
            }

            return json_decode($response->getBody()->getContents());
        } catch (\Exception $e) {
            throw new IndexManagerException($e->getMessage());
        }
    }

    private function getEndpoint(string $endpoint): string
    {
        return str_replace('{indexID}', $this->indexId, $endpoint);
    }
}
