<?php
namespace IndianShareMarket\Services;

use IndianShareMarket\DataProviders\Url;
use IndianShareMarket\Services\Bse\Equity;
use IndianShareMarket\Services\ParseDocument;
use IndianShareMarket\Exceptions\ExchangeException;

class Bse implements ExchangeInterface
{
    use Sectors, Equity;

    /** @var $parseDocument \IndianShareMarket\Services\ParseDocument */
    private $parseDocument;

    /** @var $csvPath string */
    private $csvPath = 'ism/csv/';

    /** Bse constructor. */
    public function __construct()
    {
        $this->parseDocument = new ParseDocument();
    }
}
