<?php
namespace IndianShareMarket\Services;

interface ExchangeInterface
{
    public function equities(): array;

    public function sectors(): array;

    public function industries(): array;

    public function topLosers(): array;

    // public function getQuote(): array;
}
