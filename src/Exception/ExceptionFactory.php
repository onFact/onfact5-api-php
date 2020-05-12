<?php


namespace OnFact\Exception;


use GuzzleHttp\Exception\ClientException;

class ExceptionFactory
{
    static function create(ClientException $exception)
    {
        $body = json_decode($exception->getResponse()->getBody());

        switch ($body->code) {
            case "Unauthorized":
                return new UnauthorizedException($exception->getMessage());
            default:
                return $exception;
        }
    }
}
