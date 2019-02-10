<?php
namespace IndianShareMarket\Services;

use IndianShareMarket\DataProviders\Url;
use IndianShareMarket\Services\Bse\Equity;
use IndianShareMarket\Services\Bse\Sector;
use IndianShareMarket\Services\ParseDocument;
use IndianShareMarket\Exceptions\ExchangeException;

class Bse implements ExchangeInterface
{
    use Equity, Sector;

    /** @var $parseDocument \IndianShareMarket\Services\ParseDocument */
    private $parseDocument;

    /** Bse constructor. */
    public function __construct()
    {
        $this->parseDocument = new ParseDocument();
    }
}
