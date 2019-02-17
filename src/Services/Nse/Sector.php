<?php
namespace IndianShareMarket\Services\Nse;

use IndianShareMarket\DataProviders\Url;
use IndianShareMarket\DataProviders\ExchangeDataObject;

trait Sector
{
    /**
     * Fetches all the sectors of Nse.
     * 
     * @return array
     */
    public function sectors(): array
    {
        $day = date("d");
        $month = date("m");
        $year = date("Y");

        $url = Url::$nseSectors;

        for ($i=0; $i<=7; $i++) {
            $date = date('dmY', mktime(0, 0, 0, $month, ($day - $i), $year));
            $newUrl = str_replace('ddmmyyyy', $date, $url);
            $fileData = $this->parseDocument->pullDataFromRemote($newUrl);
            if ($fileData) {
                break;
            }
        }
        ExchangeDataObject::$data['nse'] = utf8_encode($fileData);

        return ExchangeDataObject::$data;
    }

    /**
     * Generates CSV file filled with nse sectors.
     *
     * @return array
     */
    public function sectorsInCsv(): array
    {
        $this->generateCsv($this->csvSectorsFilename);

        return [
            'format' => 'csv',
            'file_path' => $this->csvPath.$this->csvSectorsFilename
        ];
    }

    /**
     * Returns list of Nse sectors in an array.
     * 
     * @return array
     */
    public function sectorsInArray(): array
    {
        return [ 'format' => 'array', 'data' => $this->prepareArray() ];
    }
}
