# OramaCloud Client PHP

OramaCloud PHP Client SDK

### Usage

```php
use OramaCloud\Client;
use OramaCloud\Query;

$client = new Client(ENDPOINT, API_KEY);

$query = (new Query())
    ->setTerm('hello world')
    ->setMode('fulltext');

$result = $client->search($query);
```

### Run Tests

```sh
composer test
```
