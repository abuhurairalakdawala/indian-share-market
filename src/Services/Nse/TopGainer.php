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
        $context = stream_context_create(
            [
                "http" => [
                    "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                ]
            ]
        );
        $topGainersList = file_get_contents(Url::$nseTopGainers, false, $context);
        $topGainersList = json_decode($topGainersList, true);
        // echo "<pre>"; print_r($topGainersList['data']); echo "</pre>"; exit();
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
