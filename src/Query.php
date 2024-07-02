<?php

namespace OramaCloud;

class Query {

    private $term;
    private $mode;
    private $limit = 5;
    private $offset = 0;

    public function __construct($term = '', $mode = 'fulltext') {
        $this->term = $term;
        $this->mode = $mode;
    }

    public function setTerm($term) {
        $this->term = $term;
        return $this;
    }

    public function setMode($mode) {
        $this->mode = $mode;
        return $this;
    }

    public function setLimit($limit) {
        $this->limit = $limit;
        return $this;
    }

    public function setOffset($offset) {
        $this->offset = $offset;
        return $this;
    }

    public function toArray() {
        return [
            'term' => $this->term,
            'mode' => $this->mode,
            'limit' => $this->limit,
            'offset' => $this->offset
        ];
    }

    public function toJson() {
        return json_encode($this->toArray());
    }

    public static function fromArray($array) {
        $query = new Query();
        $query
            ->setTerm(isset($array['term']) ? $array['term'] : '')
            ->setMode(isset($array['mode']) ? $array['mode'] : 'fulltext');

        return $query;
    }
}
