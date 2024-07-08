<?php

namespace OramaCloud\Client\QueryParams;

class Where
{
    private $property;
    private $operator;
    private $value;
    private $availableOperators = [
        WhereOperator::GT,
        WhereOperator::GTE,
        WhereOperator::LT,
        WhereOperator::LTE,
        WhereOperator::EQ,
        WhereOperator::BETWEEN,
        WhereOperator::IN,
        WhereOperator::NIN
    ];

    public function __construct(string $property, $operator, $value)
    {
        $this->property = $property;
        $this->operator = $operator;
        $this->value = $value;

        $this->validate();
    }

    public function toArray()
    {
        return [
            $this->property => [
                $this->operator => $this->value
            ]
        ];
    }

    private function validate()
    {
        if (!in_array($this->operator, $this->availableOperators)) {
            throw new \InvalidArgumentException("Invalid operator {$this->operator}");
        }

        if (in_array($this->operator, [
            WhereOperator::BETWEEN,
            WhereOperator::IN,
            WhereOperator::NIN
        ]) && !is_array($this->value)) {
            throw new \InvalidArgumentException('Where $value parameter must be an array');
        }
    }
}
