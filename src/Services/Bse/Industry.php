<?php
namespace IndianShareMarket\Services\Bse;

use IndianShareMarket\DataProviders\Url;
use IndianShareMarket\Exceptions\ExchangeException;
use IndianShareMarket\DataProviders\ExchangeDataObject;

trait Industry
{
    /**
     * Fetches all the industries of Bse.
     * 
     * @return array
     */
    public function industries(): array
    {
        $list = [];
        $fileData = $this->parseDocument->get(Url::$bseIndustries);
        $classElement = $this->parseDocument->findClass('marketstartarea');
        foreach ($classElement as $key => $value) {
            if ($key == 1) {
                foreach ($value->childNodes as $child) {
                    if (strpos($child->textContent, 'Wheelers')) {
                        foreach ($child->childNodes as $i => $lastChild) {
                            if ($i == 7) {
                                foreach ($lastChild->childNodes as $lc) {
                                    if ($lc->nodeName == 'select') {
                                        $list = explode("\n", $lc->textContent);
                                        $list = array_map(function ($item) {
                                            if (trim($item) != '') {
                                                return (trim($item));
                                            }
                                        }, $list);
                                        $list = array_filter($list);
                                        array_shift($list);
                                        array_unshift($list, 'name');
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        ExchangeDataObject::$data['bse'] = $list;

        return ExchangeDataObject::$data;
    }

    /**
     * Generates CSV file filled with BSE industries.
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
     * Returns list of BSE industries in an array.
     * 
     * @return array
     */
    public function industriesInArray(): array
    {
        return [ 'format' => 'array', 'data' => $this->prepareArray() ];
    }
}
