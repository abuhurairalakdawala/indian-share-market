<?php
namespace IndianShareMarket\Services;

interface ExchangeInterface
{
    public function stockList(string $options): array;
}
