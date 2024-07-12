<?php

namespace OramaCloud;

use GuzzleHttp\Client as HttpClient;
use OramaCloud\Client\Query;
use OramaCloud\Traits\GeneratesUniqueId;
use OramaCloud\Traits\ValidatesParams;

class Client
{
    use ValidatesParams;
    use GeneratesUniqueId;

    private $answersApiBaseURL;
    private $apiKey;
    private $endpoint;
    private $http;
    private $id;
    private $collector;
    private $headers;

    public function __construct(array $params, HttpClient $http = null)
    {
        $params = $this->validate($params, [
            'api_key' => ['required', 'string'],
            'endpoint' => ['required', 'string']
        ]);

        $this->id = $this->generateUniqueId();
        $this->http = !is_null($http) ? $http : new HttpClient();
        $this->apiKey = $params['api_key'];
        $this->endpoint = $params['endpoint'];

        $this->headers = (isset($_SERVER['HTTP_USER_AGENT'])) ? [
            'User-Agent' => $_SERVER['HTTP_USER_AGENT']
        ] : [];
    }

    public function search(Query $query)
    {
        $endpoint = "{$this->endpoint}/search?api-key={$this->apiKey}";
        $response = $this->http->request('POST', $endpoint, [
            'headers' => $this->headers,
            'form_params' => [
                'q' => $query->toJson(),
                'version' => 'php 1.0.0',
                'id' => $this->id
            ]
        ]);

        $results = json_decode($response->getBody()->getContents(), true);

        return $results;
    }
}
