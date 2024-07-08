<?php

namespace OramaCloud\Client;

class QueryException extends \Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
