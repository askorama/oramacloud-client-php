# OramaCloud Client PHP

[![Tests](https://github.com/askorama/oramacloud-client-php/actions/workflows/tests.yml/badge.svg)](https://github.com/askorama/oramacloud-client-php/actions/workflows/tests.yml)

OramaCloud PHP Client SDK

## Install

```sh
composer require orama/oramacloud-client
```

## Integrating with Orama Cloud

```php
use OramaCloud\Client;
use OramaCloud\Client\Query;

$client = new Client([
    'api_key' => '<Your Orama Cloud API Key>',
    'endpoint' => '<Your Orama Cloud Endpoint>'
]);

$query = new Query('red shoes');
$query->where('price', 'gt', 99.99);

$results = $client->search($query);
```

## Advanced search

```php
$query = (new Query('red leather shoes', 'fulltext'))
    ->where('price', 'lte', 9.99)
    ->where('gender', 'eq', 'unisex')
    ->limit(5)
    ->offset(1);

$results = $client->search($query);
```

## Managing your index

```php
use OramaCloud\Manager\CloudManager;
use OramaCloud\Manager\IndexManager;

$apiKey = 'HDks8Hkao82-ha9daj';
$manager = new CloudManager($apiKey);

$indexId = 'h7asdjk9d12kdlofabsha123';
$index = new IndexManager($indexId, $manager);

// Empty data
$index->empty();

// Insert records
$index->insert([ 'id' => 1, 'name' => 'John Doe', 'age' => 20 ]);

// Update record
$index->update([ 'id' => 1, 'name' => 'Jane Doe', 'age' => 30 ]);

// Delete record
$index->delete([ 'id' => 1 ]);

// Trigger deployment
$index->deploy();
```

## Run Tests

```sh
composer test
```
