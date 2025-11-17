<?php

namespace App\Exceptions;

use Exception;

class StudentNotFoundException extends Exception
{
    /**
     * Create a new exception instance.
     */
    public function __construct(
        string $message = 'Student not found',
        int $code = 404,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
