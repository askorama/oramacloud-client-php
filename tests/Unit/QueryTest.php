<?php

use OramaCloud\Client\Query;

describe('Query builder', function () {
    it('should configure query params', function () {
        $query = new Query();
        $query
            ->term('red shoes')
            ->mode('fulltext')
            ->where('price', 'gte', 99.99)
            ->where('category', 'eq', 'shoes')
            ->sortBy('price', 'desc');

        $result = $query->toArray();

        $this->assertEquals($result['term'], 'red shoes');
        $this->assertEquals($result['mode'], 'fulltext');
        $this->assertEquals($result['where'], [
            'category' => [
                'eq' => 'shoes'
            ],
            'price' => [
                'gte' => 99.99
            ]
        ]);
        $this->assertEquals($result['sortBy'], [
            'property' => 'price',
            'order' => 'DESC'
        ]);

        $this->assertEquals($query->toJson(), json_encode($result));
    });

    it('defaults query params from array', function () {
        $query = Query::fromArray([]);

        $this->assertEquals($query->toArray(), [
            'term' => '',
            'mode' => 'fulltext',
        ]);
    });

    it('accepts query params from array', function () {
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
    });
});
