<?php

namespace OramaCloud\Exceptions;

class IndexManagerException extends \Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
