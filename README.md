# OramaCloud Client PHP

OramaCloud PHP Client SDK

## Install

```sh
composer require orama/oramacloud-client
```

## Integrating with Orama Cloud

```php
use OramaCloud\Client;
use OramaCloud\Query;

$client = new Client("<Your Orama Cloud Endpoint>", "<Your Orama Cloud API Key>");

$results = $client->search(
    (new Query())
        ->term('red shoes')
        ->where('price', 'gt', 99.99)
);
```

## Advanced search

```php
$query = (new Query())
    ->term('red leather shoes')
    ->where('price', 'lte', 9.99)
    ->where('gender', 'eq', 'unisex')
    ->limit(5)
    ->offset(1);

$results = $client->search($query);
```

## Run Tests

```sh
composer test
```
