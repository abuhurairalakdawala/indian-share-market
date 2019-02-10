<?php
namespace IndianShareMarket\Services;

interface ExchangeInterface
{
    public function stockList(): array;

    public function sectorList();
}
