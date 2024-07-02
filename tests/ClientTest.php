<?php

use PHPUnit\Framework\TestCase;
use OramaCloud\Client;
use OramaCloud\Query;

const ENDPOINT = 'https://cloud.orama.run/v1/indexes/askorama-ai-development-uc6oxa';
const API_KEY = 'P9buEfpy8rWvT265McikCG1tP4pT6cBg';

class ClientTest extends TestCase {

    private $client;

    protected function setUp(): void {
        parent::setUp();

        $this->client = new Client(ENDPOINT, API_KEY);
    }

    public function testBasicFullTextSearch() {
        $query = (new Query())
            ->setTerm('install sdk')
            ->setMode('fulltext');

        $result = $this->client->search($query);
        
        $this->assertArrayHasKey('hits', $result);
        $this->assertArrayHasKey('elapsed', $result);
        $this->assertArrayHasKey('count', $result);
        
        $this->assertTrue($result['count'] > 0);
    }
}
