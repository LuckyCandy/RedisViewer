<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class LoginFailedException extends Exception
{
    /**
     * LoginFailedException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "账号和密码不匹配", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
