<?php
namespace IndianShareMarket\Services\Nse;

use IndianShareMarket\DataProviders\Url;
use IndianShareMarket\DataProviders\ExchangeDataObject;

trait GetQuote
{
    public function getQuote(string $symbol)
    {
        $fileData = $this->parseDocument->get(Url::$getQuote.$symbol);
        $div = $this->parseDocument->findId('responseDiv');
        ExchangeDataObject::$data['nse'] = json_decode(trim($div), true)['data'][0];

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
