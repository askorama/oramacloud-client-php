<?php

use OramaCloud\Client;
use OramaCloud\Client\Query;

const API_ENDPOINT = 'https://cloud.orama.run/v1/indexes/askorama-ai-development-uc6oxa';
const PUBLIC_API_KEY = 'P9buEfpy8rWvT265McikCG1tP4pT6cBg';

test('basic fulltext search', function () {
    $client = new Client([
        'api_key' => PUBLIC_API_KEY,
        'endpoint' => API_ENDPOINT
    ]);

    $result = $client->search(
        (new Query())
            ->term('red shoes')
            ->mode('fulltext')
            ->limit(10)
    );

    $this->assertArrayHasKey('hits', $result);
    $this->assertArrayHasKey('elapsed', $result);
    $this->assertArrayHasKey('count', $result);

    $this->assertTrue($result['count'] > 0);
    $this->assertTrue(sizeof($result['hits']) <= 10);
});
