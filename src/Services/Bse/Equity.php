<?php
namespace IndianShareMarket\Services\Bse;

use IndianShareMarket\DataProviders\Url;
use IndianShareMarket\Exceptions\ExchangeException;
use IndianShareMarket\DataProviders\ExchangeDataObject;

trait Equity
{
    /**
     * Currently this feature is not available as BSE does not provide any api to download data.
     * 
     * @return array
     */
    public function equities(): array
    {
        ExchangeDataObject::$data['bse'] = [
            'error' => 'Currently BSE data is not available :(... By the time you can download BSE stocks from here : '
            .Url::$bseStocks
        ];

        return ExchangeDataObject::$data['bse'];
    }

    public function equitiesInArray()
    {
        return [
            'format' => 'array',
            'data' => [],
            'error' => ExchangeDataObject::$data['bse']['error']
        ];
    }

    public function equitiesInCsv()
    {
        return [
            'format' => 'csv',
            'file_path' => '',
            'error' => ExchangeDataObject::$data['bse']['error']
        ];
    }
}
