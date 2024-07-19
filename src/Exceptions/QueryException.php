<?php

namespace OramaCloud\Exceptions;

class QueryException extends \Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
