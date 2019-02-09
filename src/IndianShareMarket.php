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

    /** @var $equities */
    private $data = [];

    /** IndianShareMarket constructor. */
    public function __construct()
    {
        $this->nse = new Nse();
        $this->bse = new Bse();
    }

    /**
     * Fetches all the stocks of Nse & Bse.
     * 
     * @param  array  $options
     * @return array
     */
    public function stockList(string $exchange = 'nse'): IndianShareMarket
    {
        if ($exchange != 'nse' && $exchange != 'bse' && $exchange != 'all') {
            throw new ExchangeException('Incorrect parameter value. Only nse and bse is allowed.');
        }

        if ($exchange == 'nse' || $exchange == 'all') {
            $this->data['nse']['data'] = $this->nse->stockList();
            $this->data['nse']['type'] = 'equity';
        }

        if ($exchange == 'bse' || $exchange == 'all') {
            $this->data['bse']['data'] = $this->bse->stockList();
            $this->data['bse']['type'] = 'equity';
        }

        return $this;
    }

    /**
     * Returns data in array format.
     * 
     * @return array
     */
    public function array(): array
    {
        if (isset($this->data['nse'])) {
            return $this->nse->{$this->data['nse']['type']."InArray"}($this->data['nse']['data']);
        }
    }


    /**
     * Generates CSV.
     * 
     * @return array
     */
    public function csv(): array
    {
        if (isset($this->data['nse'])) {
            return $this->nse->{$this->data['nse']['type']."InCsv"}($this->data['nse']['data']);
        }
    }

    /**
     * Returns data in json format
     * 
     * @return string
     */
    public function json(): string
    {
        header('Content-Type: application/json');
        if (isset($this->data['nse'])) {
            $data = $this->nse->{$this->data['nse']['type']."InArray"}($this->data['nse']['data']);
            $data['format'] = 'json';

            return json_encode($data);
        }
    }

    public function download()
    {
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="'.$this->data['nse']['type'].'.csv";');

        readfile($this->csv()['file_path']);
    }
}
