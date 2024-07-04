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

$result = $client->search(
    (new Query())
        ->term('red shoes')
        ->where('price', 'gt', 99.99)
        ->where('category', 'eq', 'sport')
);
```

## Advanced search

```php

```

## Run Tests

```sh
composer test
```
