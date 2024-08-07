# OramaCloud Client PHP

[![Package Version](https://img.shields.io/packagist/v/oramacloud/client)](https://packagist.org/packages/oramacloud/client)
[![PHP Version](https://img.shields.io/packagist/php-v/oramacloud/client)](https://packagist.org/packages/oramacloud/client)
[![Tests](https://github.com/askorama/oramacloud-client-php/actions/workflows/tests.yml/badge.svg)](https://github.com/askorama/oramacloud-client-php/actions/workflows/tests.yml)

With the OramaCloud PHP Client SDK, you can [perform search queries](https://docs.orama.com/cloud/performing-search/full-text-search) on your [Orama index](https://cloud.orama.com), or programmatically update your index data and trigger new deployments. Integrate Orama Cloud in your PHP application using the [REST APIs](https://docs.orama.com/cloud/data-sources/custom-integrations/rest-apis) to insert and upsert your index data and trigger deployments when it's convinient for your use case. 

## Install

```sh
composer require oramacloud/client
```

#### Compatibility

PHP: 7.3 or later

## Integrating with Orama Cloud

```php
use OramaCloud\Client;
use OramaCloud\Client\Query;

$client = new Client([
    'api_key' => '<Your Orama Cloud API Key>',
    'endpoint' => '<Your Orama Cloud Endpoint>'
]);

$query = (new Query('red shoes'))
    ->where('price', 'gt', 99.99);

$results = $client->search($query);
```

## Advanced search

```php
use OramaCloud\Client;
use OramaCloud\Client\Query;
use OramaCloud\Client\QueryParams/WhereOperator;
use OramaCloud\Client\QueryParams/SortByOrder;

$client = new Client([
    'api_key' => '<Your Orama Cloud API Key>',
    'endpoint' => '<Your Orama Cloud Endpoint>'
]);

$query = new Query();
$query->term('red shoes')
      ->mode('hybrid')
      ->where('price', WhereOperator::LTE, 9.99)
      ->where('gender', WhereOperator::EQ, 'unisex')
      ->sortBy('price' SortByOrder::DESC)
      ->limit(5)
      ->offset(1);

$results = $client->search($query);
```

## Managing your index

```php
use OramaCloud\Manager\IndexManager;

$index = new IndexManager('<Your Index-ID>', '<Your Private API Key>');

// Empty data
$index->empty();

// Insert records
$index->insert([
    ['id' => '1', 'name' => 'John Doe', 'age' => 20 ],
    ['id' => '2', 'name' => 'Mario Rossi', 'age' => 25 ],
    ['id' => '3', 'name' => 'John Smith', 'age' => 35 ],
]);

// Update records
$index->update([[ 'id' => '1', 'name' => 'Jane Doe', 'age' => 30 ]]);

// Delete records
$index->delete(['1', '2']);

// Trigger deployment
$index->deploy();
```

## Run Tests

```sh
composer test
```

### License

Apache-2.0 license. Please see [License File](LICENSE.md) for more information.
