<?php

namespace App\Exceptions;

use Exception;

class ImageProcessingException extends Exception
{
    /**
     * Create a new exception instance.
     */
    public function __construct(
        string $message = 'Image processing failed',
        int $code = 500,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
