<?php

namespace Tests\Unit;

use OramaCloud\Client\QueryParams\SortBy;
use OramaCloud\Client\QueryParams\SortByOrder;
use OramaCloud\Exceptions\QueryException;
use Tests\TestCase;

class SortByTest extends TestCase
{
    public function testCreateSortByObject()
    {
        $sortBy = new SortBy('name', SortByOrder::ASC);

        $this->assertEquals([
            'property' => 'name',
            'order' => 'ASC'
        ], $sortBy->toArray());
    }

    public function testThrowExceptionForInvalidOrder()
    {
        $this->expectException(QueryException::class);
        $this->expectExceptionMessage('Invalid $order parameter in SortBy');

        new SortBy('name', 'INVALID');
    }

    public function testCreateSortByObjectWithDefaultOrder()
    {
        $sortBy = new SortBy('metadata.title');

        $this->assertEquals([
            'property' => 'metadata.title',
            'order' => 'ASC'
        ], $sortBy->toArray());
    }

    public function testCreateSortByObjectWithDESCOrder()
    {
        $sortBy = new SortBy('name', SortByOrder::DESC);

        $this->assertEquals([
            'property' => 'name',
            'order' => 'DESC'
        ], $sortBy->toArray());
    }
}
