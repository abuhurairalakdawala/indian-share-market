<?php
namespace IndianShareMarket\Services\Nse;

use IndianShareMarket\DataProviders\Url;
use IndianShareMarket\Exceptions\ExchangeException;
use IndianShareMarket\DataProviders\ExchangeDataObject;

trait Industry
{
	/**
	 * Fetches all the industries of NSE.
	 * 
	 * @return array
	 */
    public function industries(): array
    {
        $fileData = $this->parseDocument->get(Url::$nseIndustries);
        $classElement = $this->parseDocument->findClass('sec_comp_nm');

        $industries = ['name'];
        foreach ($classElement as $key => $element) {
            if ($key == 2) {
                foreach ($element->childNodes as $value) {
                    if(is_a($value, 'DOMElement')) {
                        array_push($industries, trim($value->textContent));
                    }
                }
            }
        }
        ExchangeDataObject::$data['nse'] = $industries;

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
