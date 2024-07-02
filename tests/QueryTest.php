<?php

use PHPUnit\Framework\TestCase;
use OramaCloud\Query;

class QueryTest extends TestCase {

    public function testQueryParams() {
        $term = 'mock-term';
        $mode = 'mock-mode';

        $query = new Query();
        $query->setTerm($term);
        $query->setMode($mode);

        $this->assertEquals($term, $query->getTerm());
        $this->assertEquals($mode, $query->getMode());
    }

    public function testDefaultQueryParamsFromArray() {
        $query = Query::fromArray([]);

        $this->assertEquals($query->toArray(), [
            'term' => '',
            'mode' => 'fulltext'
        ]);
    }

    public function testQueryParamsFromArray() {
        $params = [
            'term' => 'mock-term',
            'mode' => 'mock-mode'
        ];

        $query = Query::fromArray($params);

        $this->assertEquals($params, $query->toArray());
    }

    public function testQueryParamsToJson() {
        $params = [
            'term' => 'mock-term',
            'mode' => 'mock-mode'
        ];

        $query = Query::fromArray($params);
        
        $this->assertEquals($params, json_decode($query->toJson(), true));
    }
}
