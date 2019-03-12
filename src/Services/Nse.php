<?php
namespace IndianShareMarket\Services;

use IndianShareMarket\Services\Nse\Equity;
use IndianShareMarket\Services\Nse\Sector;
use IndianShareMarket\Services\Nse\Industry;
use IndianShareMarket\Services\Nse\GetQuote;
use IndianShareMarket\Services\Nse\TopLosers;
use IndianShareMarket\Services\ParseDocument;
use IndianShareMarket\Exceptions\ExchangeException;

class Nse extends Exchange implements ExchangeInterface
{
    use Equity, Sector, Industry, GetQuote, TopLosers;

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

    /** @var $csvTopLosersFilename string */
    private $csvTopLosersFilename = 'nse_top_losers.csv';

    /** Nse constructor. */
    public function __construct()
    {
        $this->parseDocument = new ParseDocument();
    }
}
