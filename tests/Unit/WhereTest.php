<?php

namespace Tests\Unit;

use OramaCloud\Client\QueryParams\Where;
use OramaCloud\Client\QueryParams\WhereOperator;
use Tests\TestCase;

class WhereTest extends TestCase
{
    public function testCreateWhereFilterObject()
    {
        $where = new Where('name', WhereOperator::EQ, 'mock-expected-value');

        $this->assertEquals([
            'name' => [
                'eq' => 'mock-expected-value'
            ]
        ], $where->toArray());
    }

    public function testThrowExceptionWhenInvalidOperatorIsPassed()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid operator INVALID');

        new Where('name', 'INVALID', 'mock-expected-value');
    }

    public function testThrowExceptionWhenValueIsNotAnArrayForBetweenOperator()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Where $value parameter must be an array');

        new Where('age', WhereOperator::BETWEEN, 20);
    }

    public function testThrowExceptionWhenValueIsNotAnArrayForInOperator()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Where $value parameter must be an array');

        new Where('age', WhereOperator::IN, 20);
    }

    public function testThrowExceptionWhenValueIsNotAnArrayForNinOperator()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Where $value parameter must be an array');

        new Where('age', WhereOperator::NIN, 20);
    }

    public function testReturnExpectedArrayForBetweenOperator()
    {
        $where = new Where('age', WhereOperator::BETWEEN, [20, 30]);

        $this->assertEquals([
            'age' => [
                'between' => [20, 30]
            ]
        ], $where->toArray());
    }

    public function testReturnExpectedArrayForInOperator()
    {
        $where = new Where('years', WhereOperator::IN, [1984, 1994, 2004]);

        $this->assertEquals([
            'years' => [
                'in' => [1984, 1994, 2004]
            ]
        ], $where->toArray());
    }

    public function testReturnExpectedArrayForNinOperator()
    {
        $where = new Where('age', WhereOperator::NIN, [20, 30]);

        $this->assertEquals([
            'age' => [
                'nin' => [20, 30]
            ]
        ], $where->toArray());
    }

    public function testReturnExpectedArrayForGtOperator()
    {
        $where = new Where('age', WhereOperator::GT, 20);

        $this->assertEquals([
            'age' => [
                'gt' => 20
            ]
        ], $where->toArray());
    }

    public function testReturnExpectedArrayForEqOperator()
    {
        $where = new Where('year', WhereOperator::EQ, 2024);

        $this->assertEquals([
            'year' => [
                'eq' => 2024
            ]
        ], $where->toArray());
    }
}
