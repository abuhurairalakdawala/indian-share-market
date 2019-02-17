<?php
namespace IndianShareMarket\Services\Nse;

use IndianShareMarket\DataProviders\Url;
use IndianShareMarket\Exceptions\ExchangeException;

trait Industry
{
    public function industries(): array
    {
        $fileData = $this->parseDocument->pullDataFromRemote(Url::$bseIndustries);
        var_dump($fileData);

        return [];
    }
}
