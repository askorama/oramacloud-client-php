<?php

namespace OramaCloud\Client;

use OramaCloud\Client\QueryParams\SortBy;
use OramaCloud\Client\QueryParams\SortByOrder;
use OramaCloud\Client\QueryParams\Where;

class Query
{
    private $term;
    private $mode;
    private $limit;
    private $offset;
    private $sortBy;
    private $where = [];

    public function __construct($term = '', $mode = 'fulltext')
    {
        $this->term = $term;
        $this->mode = $mode;
    }

    public function term(string $term)
    {
        $this->term = $term;
        return $this;
    }

    public function mode(string $mode)
    {
        if (!in_array($mode, ['fulltext', 'vector', 'hybrid'])) {
            throw new QueryException('Invalid search mode. Must be one of: fulltext, vector, hybrid.');
        }

        $this->mode = $mode;
        return $this;
    }

    public function where(string $property, $operator, $value)
    {
        $this->where[] = new Where($property, $operator, $value);
        return $this;
    }

    public function sortBy(string $property, $order = SortByOrder::ASC)
    {
        $this->sortBy = new SortBy($property, $order);
        return $this;
    }

    public function limit(int $limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset(int $offset)
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

        if (!is_null($this->sortBy)) {
            $array['sortBy'] = $this->sortBy->toArray();
        }

        return $array;
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

        if (isset($array['sortBy']) && !is_null($array['sortBy'])) {
            $query->sortBy($array['sortBy']['property'], $array['sortBy']['order']);
        }

        if (isset($array['limit']) && !is_null($array['limit'])) {
            $query->limit($array['limit']);
        }

        if (isset($array['offset']) && !is_null($array['offset'])) {
            $query->offset($array['offset']);
        }

        return $query;
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }

    public static function fromJson($json)
    {
        return Query::fromArray(json_decode($json, true));
    }

    public function toQueryString()
    {
        return http_build_query($this->toArray());
    }
}
