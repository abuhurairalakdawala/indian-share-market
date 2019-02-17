<?php
namespace IndianShareMarket\Services\Bse;

use IndianShareMarket\DataProviders\Url;
use IndianShareMarket\Exceptions\ExchangeException;

trait Equity
{
    /**
     * Currently this feature is not available as BSE does not provide any api to download data.
     * 
     * @return array
     */
    public function equities(): array
    {
        throw new ExchangeException(
            'Currently this feature is not available :(... By the time you can download Bse stocks from here : '
            .Url::$bseStocks
        );

        return [];
    }
}
