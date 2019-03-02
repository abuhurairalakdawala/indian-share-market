<?php
namespace IndianShareMarket\Services\Bse;

use IndianShareMarket\DataProviders\Url;
use IndianShareMarket\DataProviders\ExchangeDataObject;

trait GetQuote
{
    public function getQuote(string $symbol)
    {
        $fileData = $this->parseDocument->pullDataFromRemote(Url::$getBseQuote.$symbol);
        $fileData = json_decode($fileData, true);
        unset($fileData['Data']);
        ExchangeDataObject::$data['bse'] = $fileData;

        return ExchangeDataObject::$data;
    }

    public function getQuoteInArray()
    {
        return [ 'format' => 'array', 'data' => $this->prepareArray() ];
    }

    public function getQuoteInCsv()
    {
        $this->generateCsv($this->csvQuotesFilename);

        return [
            'format' => 'csv',
            'file_path' => $this->csvPath.$this->csvQuotesFilename
        ];
    }
}
