<?php
namespace IndianShareMarket\Services\Nse;

use IndianShareMarket\DataProviders\Url;
use IndianShareMarket\DataProviders\ExchangeDataObject;

trait Equity
{
    /**
     * Fetches all the stocks of Nse.
     * 
     * @return string
     */
    public function stockList(): string
    {
        $fileData = $this->parseDocument->pullDataFromRemote(Url::$nseStocks);
        ExchangeDataObject::$data = utf8_encode($fileData);

        return ExchangeDataObject::$data;
    }

    /**
     * Generates CSV file filled with nse equities.
     * 
     * @param  string  $data
     * @return array
     */
    public function equityInCsv(): array
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
    public function equityInArray(): array
    {
        return [ 'format' => 'array', 'data' => $this->prepareArray() ];
    }
}
