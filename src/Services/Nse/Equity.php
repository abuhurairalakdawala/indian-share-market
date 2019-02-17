<?php
namespace IndianShareMarket\Services\Nse;

use IndianShareMarket\DataProviders\Url;
use IndianShareMarket\DataProviders\ExchangeDataObject;

trait Equity
{
    /**
     * Fetches all the stocks of Nse.
     * 
     * @return array
     */
    public function equities(): array
    {
        $fileData = $this->parseDocument->pullDataFromRemote(Url::$nseStocks);
        ExchangeDataObject::$data['nse'] = utf8_encode($fileData);

        return ExchangeDataObject::$data;
    }

    /**
     * Generates CSV file filled with nse equities.
     * 
     * @param  string  $data
     * @return array
     */
    public function equitiesInCsv(): array
    {
        $this->generateCsv($this->csvEquitiesFilename);

        return [
            'format' => 'csv',
            'file_path' => $this->csvPath.$this->csvEquitiesFilename
        ];
    }

    /**
     * Returns list of Nse equities in an array.
     *  
     * @return array
     */
    public function equitiesInArray(): array
    {
        return [ 'format' => 'array', 'data' => $this->prepareArray() ];
    }
}
