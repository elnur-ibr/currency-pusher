<?php

namespace App\Exceptions\Monobank;
use Illuminate\Http\Client\Response;

class ExceptionHandler
{
    public static array $statuses = [
        429 => TooManyRequestException::class
    ];
    public static function make(Response $response)
    {
        $exceptionCLass = static::$statuses[$response->status()] ?? Exception::class;

        return new $exceptionCLass($response);
    }
}
