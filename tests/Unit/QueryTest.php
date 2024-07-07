<?php

namespace Tests\Unit;

use OramaCloud\Client\Query;
use Tests\TestCase;

class QueryTest extends TestCase
{
    public function testQueryBuilder()
    {
        $query = new Query();
        $query
            ->term('red shoes')
            ->mode('fulltext')
            ->where('price', 'gte', 99.99)
            ->where('category', 'eq', 'shoes')
            ->sortBy('price', 'desc');

        $result = $query->toArray();

        $this->assertEquals('red shoes', $result['term']);
        $this->assertEquals('fulltext', $result['mode']);
        $this->assertEquals([
            'category' => [
                'eq' => 'shoes'
            ],
            'price' => [
                'gte' => 99.99
            ]
        ], $result['where']);
        $this->assertEquals([
            'property' => 'price',
            'order' => 'DESC'
        ], $result['sortBy']);

        $this->assertEquals(json_encode($result), $query->toJson());
    }

    public function testDefaultQueryParams()
    {
        $query = Query::fromArray([]);

        $this->assertEquals([
            'term' => '',
            'mode' => 'fulltext',
        ], $query->toArray());
    }

    public function testQueryParamsFromArray()
    {
        $params = [
            'term' => 'mock-term',
            'mode' => 'mock-mode',
            'where' => [
                'foo' => [
                    'eq' => 99
                ],
                'bar' => [
                    'gt' => 10
                ]
            ]
        ];

        $query = Query::fromArray($params);

        $this->assertEquals($params, $query->toArray());
        $this->assertEquals($params, json_decode($query->toJson(), true));
    }
}
