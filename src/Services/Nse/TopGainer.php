<?php
namespace IndianShareMarket\Services\Nse;

use IndianShareMarket\DataProviders\Url;
use IndianShareMarket\DataProviders\ExchangeDataObject;

trait TopGainer
{
    /**
     * Fetches top gainers list of NSE.
     * 
     * @return array
     */
    public function topGainers(): array
    { 
        $topGainersList = $this->parseDocument->pullDataFromRemote(Url::$bseTopGainers);
        $topGainersList = json_decode($topGainersList, true);
        ExchangeDataObject::$data['nse'] = $topGainersList['data'] ?? [];

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
