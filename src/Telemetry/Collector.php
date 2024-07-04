<?php

namespace OramaCloud\Telemetry;

use GuzzleHttp\Client;
use GuzzleHttp\Promise\Promise;

class Collector
{
  private $data;
  private $params;
  private $config;
  private $client;

  public function __construct($id, $apiKey, $flushInterval = 5000, $flushSize = 25)
  {
    $this->client = new Client();
    $this->data = [];
    $this->config = [
      'id' => $id,
      'api_key' => $apiKey,
      'flushInterval' => $flushInterval,
      'flushSize' => $flushSize
    ];
  }

  public function setParams($params)
  {
    $this->params = $params;
  }

  public static function create($config)
  {
    $collector = new Collector($config['id'], $config['api_key']);
    $collector->start();

    return $collector;
  }

  public function add($data)
  {
    $this->data[] = $data;

    if ($this->params != null && count($this->data) >= $this->config['flushSize']) {
      $this->flush();
    }
  }

  public function flush()
  {
    if ($this->params == null || count($this->data) === 0) {
      return;
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

    $this->sendBeacon($body);
  }

  private function start()
  {
    $this->flush();
  }

  private function sendBeacon($body)
  {
    $url = $this->params['endpoint'] . '?api-key=' . $this->config['api_key'];

    $this->client->requestAsync('POST', $url, [
      'json' => $body
    ]);
  }
}
