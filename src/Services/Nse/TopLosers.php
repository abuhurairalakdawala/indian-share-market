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
        $context = stream_context_create(
            [
                "http" => [
                    "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                ]
            ]
        );
        $topLosersList = file_get_contents(Url::$nseTopLosers, false, $context);
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
