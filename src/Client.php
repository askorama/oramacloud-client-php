<?php

namespace OramaCloud;

use GuzzleHttp\Client as HttpClient;
use OramaCloud\Client\Query;
use OramaCloud\Telemetry\Collector;
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

    public function __construct(array $params, HttpClient $http = null)
    {
        $params = $this->validate($params, [
            'api_key' => ['required', 'string'],
            'endpoint' => ['required', 'string'],
            'telemetry' => ['optional', 'boolean'],
            'answersApiBaseURL' => ['optional', 'string']
        ]);

        $this->id = $this->generateUniqueId();
        $this->http = !is_null($http) ? $http : new HttpClient();
        $this->apiKey = $params['api_key'];
        $this->endpoint = $params['endpoint'];
        $this->answersApiBaseURL = $params['answersApiBaseURL'];

        // Telemetry is enabled by default
        if ($params['telemetry'] !== false) {
            $this->collector = Collector::create([
                'id' => $this->id,
                'api_key' => $this->apiKey
            ], $this->http);
        }

        $this->init();
    }

    public function search(Query $query)
    {
        $startTime = microtime(true);
        $endpoint = "{$this->endpoint}/search?api-key={$this->apiKey}";
        $response = $this->http->request('POST', $endpoint, [
            'form_params' => [
                'q' => $query->toJson()
            ]
        ]);

        $results = json_decode($response->getBody()->getContents(), true);

        $endTime = microtime(true);
        $roundTripTime = ($endTime - $startTime) * 1000;

        if ($this->collector !== null) {
            $this->collector->add([
                'rawSearchString' => $query->toArray()['term'],
                'resultsCount' => $results['count'] ?? 0,
                'roundTripTime' => $roundTripTime,
                'query' => $query->toJson(),
                'cached' => false,
                'searchedAt' => time()
            ]);
        }

        return $results;
    }

    private function init()
    {
        $response = $this->fetch('init', 'POST');
        $response = json_decode($response, true);

        if (
            $this->collector !== null &&
            isset($response['collectUrl']) &&
            isset($response['deploymentID']) &&
            isset($response['index'])
        ) {
            $this->collector->setParams(
                $response['collectUrl'],
                $response['deploymentID'],
                $response['index']
            );
        }
    }

    private function fetch($path, $method, $body = [])
    {
        $endpoint = "{$this->endpoint}/{$path}?api-key={$this->apiKey}";

        $requestOptions = [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ]
        ];

        if ($method === 'POST' && $body !== []) {
            $b = $body;
            $b['version'] = 'php-sdk-1.0.0';
            $b['id'] = $this->id;

            $requestOptions['body'] = http_build_query($body);
        }

        $response = $this->http->request('POST', $endpoint, $requestOptions);

        return $response->getBody();
    }
}
