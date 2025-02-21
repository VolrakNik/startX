<?php

namespace App\Exceptions;

use Common\Exceptions\Exception;

class ApplicationException extends Exception
{
    public function report(): void {}
    public function response(): array
    {
        http_response_code(422);
        return ['message' => $this->getMessage(), 'code' => $this->getCode()];
    }
}