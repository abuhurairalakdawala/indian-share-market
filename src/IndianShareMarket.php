<?php
namespace IndianShareMarket;

use IndianShareMarket\Services\Nse;
use IndianShareMarket\Services\Bse;
use IndianShareMarket\Exceptions\ExchangeException;

class IndianShareMarket
{
    /** @var $nse \IndianShareMarket\Services\Nse */
    private $nse;

    /** @var $bse \IndianShareMarket\Services\Bse */
    private $bse;

    /** IndianShareMarket constructor. */
    public function __construct()
    {
        $this->nse = new Nse();
        $this->bse = new Bse();
    }

    /**
     * Fetches all the stocks of Nse & Bse in either three formats array, csv or download a file.
     * 
     * @param  array  $options
     * @return array
     */
    public function stockList(array $options = [
        'for' => 'all', // all, nse, bse
        'format' => 'array' // array, csv, download
    ]): array
    {
        if (!isset($options['for'])) {
            $options['for'] = 'all';
        }

        if (!isset($options['format'])) {
            $options['format'] = 'array';
        }

        $data = [];

        if ($options['for'] == 'all' || $options['for'] == 'nse') {
            $data['nse'] = $this->nse->stockList($options['format']);
        }

        return $data;
    }

    private function getQuote($code)
    {
        
    }
}
