<?php

use OramaCloud\Client\QueryParams\SortBy;
use OramaCloud\Client\QueryParams\SortByOrder;

describe('Sort By', function () {
    it('should create a sort by object', function () {
        $sortBy = new SortBy('name', SortByOrder::ASC);

        expect($sortBy->toArray())->toBe([
            'property' => 'name',
            'order' => 'ASC'
        ]);
    });

    it('should throw an exception when invalid order is passed', function () {
        $closure = function () {
            new SortBy('name', 'INVALID');
        };

        expect($closure)->toThrow(new \InvalidArgumentException('Invalid $order parameter in SortBy'));
    });

    it('should create a sort by object with default order', function () {
        $sortBy = new SortBy('metadata.title');

        expect($sortBy->toArray())->toBe([
            'property' => 'metadata.title',
            'order' => 'ASC'
        ]);
    });

    it('should create a sort by object with DESC order', function () {
        $sortBy = new SortBy('name', SortByOrder::DESC);

        expect($sortBy->toArray())->toBe([
            'property' => 'name',
            'order' => 'DESC'
        ]);
    });
});
