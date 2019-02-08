<?php
namespace IndianShareMarket\Exceptions;

class ExchangeException extends \Exception
{
    public function __construct($message, $code = 1, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
