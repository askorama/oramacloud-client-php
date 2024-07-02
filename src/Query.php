<?php
namespace OramaCloud;

class Query {

    private $term;
    private $mode;

    public function __construct($term = '', $mode = 'fulltext') {
        $this->term = $term;
        $this->mode = $mode;
    }

    public function getTerm() {
        return $this->term;
    }

    public function setTerm($term) {
        $this->term = $term;
        return $this;
    }

    public function getMode() {
        return $this->mode;
    }

    public function setMode($mode) {
        $this->mode = $mode;
        return $this;
    }

    public static function fromArray($array) {
        extract($array);
        return new Query($term, $mode);
    }

    public function toArray() {
        return [
            'term' => $this->term,
            'mode' => $this->mode
        ];
    }

    public function toJson() {
        return json_encode($this->toArray());
    }
}
