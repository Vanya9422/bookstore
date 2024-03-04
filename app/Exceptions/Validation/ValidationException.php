<?php

namespace App\Exceptions\Validation;

class ValidationException extends \Exception
{
    protected array $errors;

    public function __construct(array $errors, $message = "Validation errors", $code = 0, \Exception $previous = null)
    {
        $this->errors = $errors;
        parent::__construct($message, $code, $previous);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}