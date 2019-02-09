<?php
namespace IndianShareMarket\Services\Bse;

trait Equity
{
    /**
     * Currently this feature is not available as BSE does not provide any api to download data.
     * 
     * @param  string $format
     * @return array
     */
    public function stockList(): string
    {
        throw new ExchangeException(
            'Currently this feature is not available :(... By the time you can download Bse stocks from here : '
            .Url::$bseStocks
        );

        return '';
    }
}
