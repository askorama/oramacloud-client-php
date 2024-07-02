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

$query = Query::fromArray([
    'term' => 'red shoes'
]);

$result = $client->search($query);
```

## Advanced search

```php

```

## Run Tests

```sh
composer test
```
