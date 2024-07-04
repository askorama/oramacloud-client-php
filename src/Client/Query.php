<?php

namespace OramaCloud\Client;

use OramaCloud\Client\QueryParams\Where;

class Query
{
    private $term;
    private $mode;
    private $limit;
    private $offset;
    private $where = [];

    public function __construct($term = '', $mode = 'fulltext')
    {
        $this->term = $term;
        $this->mode = $mode;
    }

    public function term($term)
    {
        $this->term = $term;
        return $this;
    }

    public function mode($mode)
    {
        $this->mode = $mode;
        return $this;
    }

    public function where($property, $operator, $value)
    {
        $this->where[] = new Where($property, $operator, $value);
        return $this;
    }

    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    public function toArray()
    {
        $array = [];

        if (!is_null($this->term)) {
            $array['term'] = $this->term;
        }

        if (!is_null($this->mode)) {
            $array['mode'] = $this->mode;
        }

        if (!is_null($this->limit)) {
            $array['limit'] = $this->limit;
        }

        if (!is_null($this->offset)) {
            $array['offset'] = $this->offset;
        }

        if (isset($this->where) && is_array($this->where) && count($this->where) > 0) {
            foreach ($this->where as $where) {
                foreach ($where->toArray() as $key => $value) {
                    $array['where'][$key] = $value;
                }
            }
        }

        return $array;
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }

    public static function fromArray($array)
    {
        $query = new Query();

        $query->term(isset($array['term']) ? $array['term'] : '');
        $query->mode(isset($array['mode']) ? $array['mode'] : 'fulltext');

        if (isset($array['where']) && !is_null($array['where'])) {
            foreach ($array['where'] as $property => $value) {
                $query->where($property, key($value), $value[key($value)]);
            }
        }

        if (isset($array['limit']) && !is_null($array['limit'])) {
            $query->limit($array['limit']);
        }

        if (isset($array['offset']) && !is_null($array['offset'])) {
            $query->offset($array['offset']);
        }

        return $query;
    }
}
