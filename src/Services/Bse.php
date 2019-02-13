<?php
namespace IndianShareMarket\Services;

use IndianShareMarket\DataProviders\Url;
use IndianShareMarket\Services\Bse\Equity;
use IndianShareMarket\Services\Bse\Sector;
use IndianShareMarket\Services\Bse\Industry;
use IndianShareMarket\Services\ParseDocument;
use IndianShareMarket\Exceptions\ExchangeException;

class Bse extends Exchange implements ExchangeInterface
{
    use Equity, Sector, Industry;

    /** @var $parseDocument \IndianShareMarket\Services\ParseDocument */
    private $parseDocument;

    /** @var $csvSectorsFilename string */
    private $csvSectorsFilename = 'bse_sectors.csv';

    /** @var $csvIndustryFilename string */
    private $csvIndustryFilename = 'bse_industry.csv';

    /** Bse constructor. */
    public function __construct()
    {
        $this->parseDocument = new ParseDocument();
    }
}
