<?php
namespace IndianShareMarket\Services;

use IndianShareMarket\Services\Nse\Equity;
use IndianShareMarket\Services\Nse\Sector;
use IndianShareMarket\Services\Nse\Industry;
use IndianShareMarket\Services\Nse\GetQuote;
use IndianShareMarket\Services\Nse\TopGainer;
use IndianShareMarket\Services\ParseDocument;
use IndianShareMarket\Exceptions\ExchangeException;

class Nse extends Exchange implements ExchangeInterface
{
    use Equity, Sector, Industry, GetQuote, TopGainer;

    /** @var $parseDocument \IndianShareMarket\Services\ParseDocument */
    private $parseDocument;

    /** @var $csvEquitiesFilename string */
    private $csvEquitiesFilename = 'nse_equities.csv';

    /** @var $csvSectorsFilename string */
    private $csvSectorsFilename = 'nse_sectors.csv';

    /** @var $csvIndustryFilename string */
    private $csvIndustryFilename = 'nse_industry.csv';

    /** @var $csvQuotesFilename string */
    private $csvQuotesFilename = 'nse_stock.csv';

    /** @var $csvTopGainersFilename string */
    private $csvTopGainersFilename = 'nse_top_gainers.csv';

    /** Nse constructor. */
    public function __construct()
    {
        $this->parseDocument = new ParseDocument();
    }
}
