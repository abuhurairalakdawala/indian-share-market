<?php
namespace IndianShareMarket\Services\Nse;

use IndianShareMarket\DataProviders\Url;
use IndianShareMarket\Exceptions\ExchangeException;
use IndianShareMarket\DataProviders\ExchangeDataObject;

trait Industry
{
	/**
	 * Fetches all the industries of Nse.
	 * 
	 * @return array
	 */
    public function industries(): array
    {
        $fileData = $this->parseDocument->pullDataFromRemote(Url::$nseIndustries);
        $fileData = json_decode($fileData, true);
        ExchangeDataObject::$data['nse'] = $fileData['data'];

        return ExchangeDataObject::$data;
    }

    /**
     * Generates CSV file filled with NSE industries.
     * 
     * @return array
     */
    public function industriesInCsv(): array
    {
        $this->generateCsv($this->csvIndustryFilename);

        return [
            'format' => 'csv',
            'file_path' => $this->csvPath.$this->csvIndustryFilename
        ];
    }

    /**
     * Returns list of NSE industries in an array.
     * 
     * @return array
     */
    public function industriesInArray(): array
    {
        return [ 'format' => 'array', 'data' => $this->prepareArray() ];
    }
}
