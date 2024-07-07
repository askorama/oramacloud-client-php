<?php

namespace OramaCloud;

use GuzzleHttp\Client as HttpClient;
use OramaCloud\Client\Cache;
use OramaCloud\Client\Query;
use OramaCloud\Telemetry\Collector;
use OramaCloud\Traits\ValidatesParams;
use Visus\Cuid2\Cuid2;

class Client
{
    use ValidatesParams;

    private $answersApiBaseURL;
    private $apiKey;
    private $endpoint;
    private $http;
    private $id;
    private $collector;
    private $cache = true;

    public function __construct(array $params, HttpClient $http = null)
    {
        $params = $this->validate($params, [
            'api_key' => ['required', 'string'],
            'endpoint' => ['required', 'string'],
            'telemetry' => ['optional'],
            'answersApiBaseURL' => ['optional', 'string'],
            'cache' => ['optional', 'boolean']
        ]);

        $this->id = (new Cuid2())->toString();
        $this->http = !is_null($http) ? $http : new HttpClient();
        $this->apiKey = $params['api_key'];
        $this->endpoint = $params['endpoint'];
        $this->answersApiBaseURL = $params['answersApiBaseURL'];
        $this->cache = $params['cache'];

        // Telemetry is enabled by default
        if (isset($params['telemetry']) && $params['telemetry'] !== false) {
            $this->validate($params, [
                'telemetry' => ['array']
            ]);

            $this->validate($params['telemetry'], [
                'flushInterval' => ['optional', 'integer'],
                'flushSize' => ['optional', 'integer']
            ]);

            $this->collector = Collector::create([
                'id' => $this->id,
                'api_key' => $this->apiKey,
                'flushInterval' => $params['telemetry']['flushInterval'] ?? 5000,
                'flushSize' => $params['telemetry']['flushSize'] ?? 25
            ]);
        }

        // Cache is enabled by default
        if ($this->cache !== false) {
            $this->cache = new Cache();
        }

        $this->init();
    }

    public function search(Query $query)
    {
        $cacheKey = "search-" . $query->toJson();

        if ($this->cache && $this->cache->has($cacheKey)) {
            $roundTripTime = 0;
            $searchResults = $this->cache->get($cacheKey);
            $cached = true;
        } else {
            $startTime = microtime(true);
            $endpoint = "{$this->endpoint}/search?api-key={$this->apiKey}";
            $response = $this->http->request('POST', $endpoint, [
                'form_params' => [
                    'q' => $query->toJson()
                ]
            ]);

            $searchResults = $response->getBody();

            $endTime = microtime(true);
            $roundTripTime = ($endTime - $startTime) * 1000;
            $cached = false;

            $this->cache->set($cacheKey, $searchResults);
        }

        if ($this->collector !== null) {
            $this->collector->add([
                'rawSearchString' => $query->toArray()['term'],
                'resultsCount' => $searchResults->hits ?? 0,
                'roundTripTime' => $roundTripTime,
                'query' => $query->toJson(),
                'cached' => $cached,
                'searchedAt' => time()
            ]);
        }

        return json_decode($searchResults, true);
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
            $this->collector->setParams([
                'endpoint' => $response['collectUrl'],
                'deploymentID' => $response['deploymentID'],
                'index' => $response['index']
            ]);
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
