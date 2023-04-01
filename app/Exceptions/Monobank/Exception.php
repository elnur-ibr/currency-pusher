<?php

namespace App\Exceptions\Monobank;

use Exception as BaseException;
use Illuminate\Http\Client\Response;

class Exception extends BaseException
{
    public Response $response;

    public function __construct(Response $response,string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->response = $response;
    }
}
