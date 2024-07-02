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

        $result = $query->toArray();
        
        $this->assertEquals($term, $result['term']);
        $this->assertEquals($mode, $result['mode']);
    }

    public function testDefaultQueryParamsFromArray() {
        $query = Query::fromArray([]);

        $this->assertEquals($query->toArray(), [
            'term' => '',
            'mode' => 'fulltext',
            'limit' => 5,
            'offset' => 0
        ]);
    }

    public function testQueryParamsFromArray() {
        $params = [
            'term' => 'mock-term',
            'mode' => 'mock-mode'
        ];

        $query = Query::fromArray($params);

        $this->assertEquals(array_merge($params, [
            'limit' => 5,
            'offset' => 0
        ]), $query->toArray());
    }

    public function testQueryParamsToJson() {
        $params = [
            'term' => 'mock-term',
            'mode' => 'mock-mode'
        ];

        $query = Query::fromArray($params);
        
        $this->assertEquals(array_merge($params, [
            'limit' => 5,
            'offset' => 0
        ]), json_decode($query->toJson(), true));
    }
}
