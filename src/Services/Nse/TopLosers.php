<?php
namespace IndianShareMarket\Services\Nse;

use IndianShareMarket\DataProviders\Url;
use IndianShareMarket\DataProviders\ExchangeDataObject;

trait TopLosers
{
    /**
     * Fetches top losers list of NSE.
     * 
     * @return array
     */
    public function topLosers(): array
    {
        $topLosersList = $this->parseDocument->pullDataFromRemote(Url::$nseTopLosers);
        $topLosersList = json_decode($topLosersList, true);
        ExchangeDataObject::$data['nse'] = $topLosersList['data'] ?? [];

        return ExchangeDataObject::$data;
    }

    /**
     * Returns list of NSE top losers in an array.
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
     * Generates CSV file filled with NSE top loosers.
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
