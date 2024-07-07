<?php

use OramaCloud\Client\QueryParams\Where;
use OramaCloud\Client\QueryParams\WhereOperator;

describe('Where filter', function () {
    it('should create a where filter object', function () {
        $where = new Where('name', WhereOperator::EQ, 'mock-expected-value');

        expect($where->toArray())->toBe([
            'name' => [
                'eq' => 'mock-expected-value'
            ]
        ]);
    });

    it('should throw an exception when invalid operator is passed', function () {
        $closure = function () {
            new Where('name', 'INVALID', 'mock-expected-value');
        };

        expect($closure)->toThrow(new \InvalidArgumentException('Invalid operator INVALID'));
    });

    it('should throw an exception when value is not an array for BETWEEN operator', function () {
        $closure = function () {
            new Where('age', WhereOperator::BETWEEN, 20);
        };

        expect($closure)->toThrow(new \InvalidArgumentException('Where $value parameter must be an array'));
    });

    it('should throw an exception when value is not an array for IN operator', function () {
        $closure = function () {
            new Where('age', WhereOperator::IN, 20);
        };

        expect($closure)->toThrow(new \InvalidArgumentException('Where $value parameter must be an array'));
    });

    it('should throw an exception when value is not an array for NIN operator', function () {
        $closure = function () {
            new Where('age', WhereOperator::NIN, 20);
        };

        expect($closure)->toThrow(new \InvalidArgumentException('Where $value parameter must be an array'));
    });

    it('should return the expected array for BETWEEN operator', function () {
        $where = new Where('age', WhereOperator::BETWEEN, [20, 30]);

        expect($where->toArray())->toBe([
            'age' => [
                'between' => [20, 30]
            ]
        ]);
    });

    it('should return the expected array for IN operator', function () {
        $where = new Where('years', WhereOperator::IN, [1984, 1994, 2004]);

        expect($where->toArray())->toBe([
            'years' => [
                'in' => [1984, 1994, 2004]
            ]
        ]);
    });

    it('should return the expected array for NIN operator', function () {
        $where = new Where('age', WhereOperator::NIN, [20, 30]);

        expect($where->toArray())->toBe([
            'age' => [
                'nin' => [20, 30]
            ]
        ]);
    });

    it('should return the expected array for GT operator', function () {
        $where = new Where('age', WhereOperator::GT, 20);

        expect($where->toArray())->toBe([
            'age' => [
                'gt' => 20
            ]
        ]);
    });

    it('should return the expected array for EQ operator', function () {
        $where = new Where('year', WhereOperator::EQ, 2024);

        expect($where->toArray())->toBe([
            'year' => [
                'eq' => 2024
            ]
        ]);
    });
});
