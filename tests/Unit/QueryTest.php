<?php

use OramaCloud\Client\Query;
use OramaCloud\Client\QueryParams\Where;

test('configure query params', function () {
    $term = 'mock-term';
    $mode = 'mock-mode';

    $query = new Query();
    $query
        ->term('red shoes')
        ->mode('fulltext')
        ->where('price', 'gte', 99.99)
        ->where('category', 'eq', 'shoes');

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
});

test('default query params from array', function () {
    $query = Query::fromArray([]);

    $this->assertEquals($query->toArray(), [
        'term' => '',
        'mode' => 'fulltext',
    ]);
});

test('query params from array', function () {
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
});

test('query params as json', function () {
    $params = [
        'term' => 'mock-term',
        'mode' => 'mock-mode'
    ];

    $query = Query::fromArray($params);

    $this->assertEquals($params, json_decode($query->toJson(), true));
});
