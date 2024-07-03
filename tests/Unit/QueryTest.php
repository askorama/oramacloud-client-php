<?php

use OramaCloud\Query;

test('query params', function () {
    $term = 'mock-term';
    $mode = 'mock-mode';

    $query = new Query();
    $query->setTerm($term);
    $query->setMode($mode);

    $result = $query->toArray();
    
    $this->assertEquals($term, $result['term']);
    $this->assertEquals($mode, $result['mode']);
});

test('default query params from array', function () {
    $query = Query::fromArray([]);

    $this->assertEquals($query->toArray(), [
        'term' => '',
        'mode' => 'fulltext',
        'limit' => 5,
        'offset' => 0
    ]);
});

test('query params from array', function () {
    $params = [
        'term' => 'mock-term',
        'mode' => 'mock-mode'
    ];

    $query = Query::fromArray($params);

    $this->assertEquals(array_merge($params, [
        'limit' => 5,
        'offset' => 0
    ]), $query->toArray());
});

test('query params as json', function () {
    $params = [
        'term' => 'mock-term',
        'mode' => 'mock-mode'
    ];

    $query = Query::fromArray($params);
    
    $this->assertEquals(array_merge($params, [
        'limit' => 5,
        'offset' => 0
    ]), json_decode($query->toJson(), true));
});