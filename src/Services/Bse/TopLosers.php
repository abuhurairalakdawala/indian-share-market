<?php
namespace IndianShareMarket\Services\Bse;

use IndianShareMarket\DataProviders\Url;
use IndianShareMarket\DataProviders\ExchangeDataObject;

trait TopLosers
{
    /**
     * Fetches top losers list of BSE.
     * 
     * @return array
     */
    public function topLosers(): array
    {
        $topLosersList = $this->parseDocument->pullDataFromRemote(Url::$bseTopLosers);
        $topLosersList = json_decode($topLosersList, true);
        $topLosersList = $topLosersList['Table'] ?? [];

        $onlyKeyArray = [
            'scripName' => 'ScripName',
            'lastTradedPrice' => 'Ltradert',
            'changeValue' => 'change_val',
            'changePercent' => 'change_percent',
            'scriptId' => 'scrip_id',
            'scripCD' => 'scrip_cd'
        ];

        $result = array_map(function ($value) use ($onlyKeyArray) {
            return array_combine(
                array_keys($onlyKeyArray),
                array_intersect_key(
                    $value,
                    array_flip((array) $onlyKeyArray)
                )
            );
        }, $topLosersList);
        ExchangeDataObject::$data['bse'] = $result ?? [];

        return ExchangeDataObject::$data;
    }

    /**
     * Returns list of BSE top losers in an array.
     * 
     * @return array
     */
    public function topLosersInArray(): array
    {
        return [
            'format' => 'array',
            'data' => $this->prepareArray()
        ];
    }

    /**
     * Generates CSV file filled with BSE top loosers.
     * 
     * @return array
     */
    public function topLosersInCsv(): array
    {
        $this->generateCsv($this->csvTopLosersFilename);
        
        return [
            'format' => 'csv',
            'file_path' => $this->csvPath.$this->csvTopLosersFilename
        ];
    }
}
