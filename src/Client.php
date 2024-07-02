<?php
namespace OramaCloud;

use GuzzleHttp\Client as HttpClient;

class Client {

    private $answersApiBaseURL;
    private $apiKey;
    private $endpoint;
    private $http;
    private $collector;

    public function __construct($endpoint, $apiKey, $answersApiBaseURL = null) {
        $this->apiKey = $apiKey;
        $this->endpoint = $endpoint;
        $this->answersApiBaseURL = $answersApiBaseURL;

        $this->http = new HttpClient();
    }

    public function search(Query $query) {
        $endpoint = "{$this->endpoint}/search?api-key={$this->apiKey}";
        $options = [
            'form_params' => [
                'q' => $query->toJson()
            ]
        ];
        
        $response = $this->http->request('POST', $endpoint, $options);

        return json_decode($response->getBody(), true);
    }
}
