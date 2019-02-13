<?php
namespace IndianShareMarket;

use IndianShareMarket\Services\Nse;
use IndianShareMarket\Services\Bse;
use IndianShareMarket\Exceptions\ExchangeException;
use IndianShareMarket\DataProviders\ExchangeDataObject;

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
     * Returns data in array format.
     * 
     * @return array
     */
    public function array(): array
    {
        $array = [];

        if (isset($this->data['nse'])) {
            ExchangeDataObject::$exchange = 'nse';
            $array['nse'] = $this->nse->{ExchangeDataObject::$serviceType."InArray"}();
        }

        if (isset($this->data['bse'])) {
            ExchangeDataObject::$exchange = 'bse';
            $array['bse'] = $this->bse->{ExchangeDataObject::$serviceType."InArray"}();
        }

        return $array;
    }

    /**
     * Generates CSV.
     * 
     * @return array
     */
    public function csv(): array
    {
        $array = [];

        if (isset($this->data['nse'])) {
            ExchangeDataObject::$exchange = 'nse';
            $array['nse'] = $this->nse->{ExchangeDataObject::$serviceType."InCsv"}();
        }

        if (isset($this->data['bse'])) {
            ExchangeDataObject::$exchange = 'bse';
            $array['bse'] = $this->bse->{ExchangeDataObject::$serviceType."InCsv"}();
        }

        return $array;
    }

    /**
     * Returns data in json format
     * 
     * @return string
     */
    public function json(): string
    {
        header('Content-Type: application/json');

        $array = [];

        if (isset($this->data['nse'])) {
            ExchangeDataObject::$exchange = 'nse';
            $array['nse'] = $this->nse->{ExchangeDataObject::$serviceType."InArray"}();
            $array['nse']['format'] = 'json';
        }

        if (isset($this->data['bse'])) {
            ExchangeDataObject::$exchange = 'bse';
            $array['bse'] = $this->bse->{ExchangeDataObject::$serviceType."InArray"}();
            $array['bse']['format'] = 'json';
        }

        return json_encode($array);
    }

    /**
     * Download Stocks list in CSV.
     * 
     * @return void
     */
    public function download()
    {
        $zipname = 'files.zip';

        $files = array();

        if (isset($this->data['nse'])) {
            $files[] = $this->csv()['nse']['file_path'];
        }

        if (isset($this->data['bse'])) {
            $files[] = $this->csv()['bse']['file_path'];
        }

        if (count($files) > 1) {
            header('Content-Type: application/zip');
            header('Content-disposition: attachment; filename='. $zipname);
            $zip = new \ZipArchive;
            $zip->open($zipname, \ZipArchive::CREATE);
            foreach ($files as $file) {
                $newFilename = substr($file, strrpos($file, '/') + 1);
                $zip->addFile($file, $file);
            }
            $zip->close();
            readfile($zipname);
            unlink($zipname);
        } else {
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename='. ExchangeDataObject::$serviceType.'.csv');
            readfile($files[0]);
        }
    }

    /**
     * Fetches all the stocks of Nse & Bse.
     * 
     * @param  string  $exchange
     * @return IndianShareMarket
     */
    public function stockList(string $exchange = 'nse'): IndianShareMarket
    {
        if ($exchange != 'nse' && $exchange != 'bse' && $exchange != 'both') {
            throw new ExchangeException('Incorrect parameter value. Only nse, bse or both is allowed.');
        }

        $this->data = null;

        ExchangeDataObject::$serviceType = 'equity';

        if ($exchange == 'nse' || $exchange == 'both') {
            $this->nse->stockList();
            $this->data['nse'] = true;
        }

        if ($exchange == 'bse' || $exchange == 'both') {
            $this->bse->stockList();
            $this->data['bse'] = true;
        }

        return $this;
    }

    /**
     * Fetches all the sectors of Nse & Bse.
     * 
     * @param  string $exchange
     * @return IndianShareMarket
     */
    public function sectorList(string $exchange = 'both'): IndianShareMarket
    {
        if ($exchange != 'nse' && $exchange != 'bse' && $exchange != 'both') {
            throw new ExchangeException('Incorrect parameter value. Only nse, bse or both is allowed.');
        }

        $this->data = null;

        ExchangeDataObject::$serviceType = 'sector';

        if ($exchange == 'nse' || $exchange == 'both') {
            $this->nse->sectorList();
            $this->data['nse'] = true;
        }

        if ($exchange == 'bse' || $exchange == 'both') {
            $this->bse->sectorList();
            $this->data['bse'] = true;
        }

        return $this;
    }

    /**
     * Fetches all the industries of Nse & Bse.
     * 
     * @return IndianShareMarket
     */
    public function industryList(): IndianShareMarket
    {
        ExchangeDataObject::$serviceType = 'industry';
        $this->data['bse'] = true;
        $this->bse->industryList();

        return $this;
    }
}
