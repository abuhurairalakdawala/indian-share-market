<?php
namespace IndianShareMarket\Services\Bse;

use IndianShareMarket\DataProviders\Url;
use IndianShareMarket\DataProviders\ExchangeDataObject;

trait TopGainer
{
    /**
     * Fetches top gainers list of BSE.
     * 
     * @return array
     */
    public function topGainers(): array
    {
        $topGainersList = $this->parseDocument->pullDataFromRemote(Url::$bseTopGainers);
        $topGainersList = json_decode($topGainersList, true);
        $topGainersList = $topGainersList['Table'] ?? [];

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
        }, $topGainersList);

        ExchangeDataObject::$data['bse'] = $result ?? [];

        return ExchangeDataObject::$data;
    }

    /**
     * Returns list of NSE top gainers in an array.
     * 
     * @return array
     */
    public function topGainersInArray(): array
    {
        return [
            'format' => 'array',
            'data' => $this->prepareArray()
        ];
    }

    /**
     * Generates CSV file filled with NSE top gainers.
     * 
     * @return array
     */
    public function topGainersInCsv(): array
    {
        $this->generateCsv($this->csvTopGainersFilename);
        
        return [
            'format' => 'csv',
            'file_path' => $this->csvPath.$this->csvTopGainersFilename
        ];
    }
}
