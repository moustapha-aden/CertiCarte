<?php

namespace App\Exceptions;

use Exception;

class StudentImportException extends Exception
{
    /**
     * Create a new exception instance.
     */
    public function __construct(
        string $message = 'Student import failed',
        int $code = 500,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
