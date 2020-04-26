<?php

namespace Jefferson\Lima;

use RuntimeException;
use Throwable;

class AutoWiredMockException extends RuntimeException
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}