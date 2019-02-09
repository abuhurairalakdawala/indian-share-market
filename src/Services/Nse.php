<?php
namespace IndianShareMarket\Services;

use IndianShareMarket\Services\Nse\Equity;
use IndianShareMarket\Services\ParseDocument;
use IndianShareMarket\Exceptions\ExchangeException;

class Nse implements ExchangeInterface
{
    use Sectors, Equity;

    /** @var $parseDocument \IndianShareMarket\Services\ParseDocument */
    private $parseDocument;

    /** @var $csvPath string */
    private $csvPath = 'ism/csv/';

    /** @var $csvEquitiesFilename string */
    private $csvEquitiesFilename = 'nse_equities.csv';

    /** Nse constructor. */
    public function __construct()
    {
        $this->parseDocument = new ParseDocument();
    }
}
