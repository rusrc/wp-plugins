<?php

namespace C4lPartners\C4lException;

use Exception;

class C4lException extends Exception
{
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
