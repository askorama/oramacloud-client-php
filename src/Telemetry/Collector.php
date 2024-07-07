<?php

namespace OramaCloud\Telemetry;

use GuzzleHttp\Client;

class Collector
{
  private $data;
  private $params;
  private $config;
  private $client;

  public function __construct(string $id, string $apiKey, Client $http)
  {
    $this->client = $http;
    $this->data = [];
    $this->config = [
      'id' => $id,
      'api_key' => $apiKey
    ];
  }

  public function setParams($endpoint, $deploymentID, $indexID)
  {
    $this->params = [
      'endpoint' => $endpoint,
      'deploymentID' => $deploymentID,
      'index' => $indexID
    ];
  }

  public static function create($config, Client $http)
  {
    if (!isset($config['id']) || !isset($config['api_key'])) {
      throw new \InvalidArgumentException('The id and api_key parameters are required.');
    }

    $collector = new Collector($config['id'], $config['api_key'], $http);

    $collector->start();

    return $collector;
  }

  public function add($data)
  {
    $this->data[] = $data;

    $this->flush();
  }

  public function flush()
  {
    if ($this->params == null || count($this->data) === 0) {
      return null;
    }

    $data = $this->data;
    $this->data = [];

    $body = [
      'source' => 'be',
      'deploymentID' => $this->params['deploymentID'],
      'index' => $this->params['index'],
      'oramaId' => $this->config['id'],
      'oramaVersion' => 'php-sdk-1.0.0',
      'userAgent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null,
      'events' => $data
    ];

    return $this->sendBeacon($body);
  }

  private function start()
  {
    $this->flush();
  }

  private function sendBeacon($body)
  {
    if ($this->params == null || !isset($this->params['endpoint'])) {
      return null;
    }

    $url = $this->params['endpoint'] . '?api-key=' . $this->config['api_key'];

    return $this->client->requestAsync('POST', $url, [
      'form_params' => $body
    ]);
  }
}
