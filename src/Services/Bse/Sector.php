<?php
namespace IndianShareMarket\Services\Bse;

use IndianShareMarket\DataProviders\Url;
use IndianShareMarket\DataProviders\ExchangeDataObject;

trait Sector
{
    /**
     * Fetches all the sectors of Bse.
     * 
     * @return array
     */
    public function sectorList(): array
    {
        $fileData = $this->parseDocument->pullDataFromRemote(Url::$bseSectors);
        ExchangeDataObject::$data['bse'] = (json_encode(json_decode($fileData, true)['Index']));

        return ExchangeDataObject::$data;
    }

    /**
     * Generates CSV file filled with nse sectors.
     *
     * @return array
     */
    public function sectorInCsv(): array
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
    public function sectorInArray(): array
    {
        return [ 'format' => 'array', 'data' => $this->convertJsonToArray() ];
    }
}
