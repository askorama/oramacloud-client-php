<?php

namespace OramaCloud\QueryParams;

class Where {

    private $property;
    private $operator;
    private $value;
    
    public function __construct($property, $operator, $value) {
        $this->property = $property;
        $this->operator = $operator;
        $this->value = $value;
    }

    public function toArray() {
        return [
            $this->property => [
                $this->operator => $this->value
            ]
        ];
    }
}
