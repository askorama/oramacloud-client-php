<?php

use OramaCloud\Client;
use OramaCloud\Query;

const API_ENDPOINT = 'https://cloud.orama.run/v1/indexes/askorama-ai-development-uc6oxa';
const PUBLIC_API_KEY = 'P9buEfpy8rWvT265McikCG1tP4pT6cBg';

test('basic fulltext search', function () {
    $client = new Client(API_ENDPOINT, PUBLIC_API_KEY);
    $query = new Query('install sdk');
    
    $result = $client->search($query);
    
    $this->assertArrayHasKey('hits', $result);
    $this->assertArrayHasKey('elapsed', $result);
    $this->assertArrayHasKey('count', $result);
    
    $this->assertTrue($result['count'] > 0);
});
