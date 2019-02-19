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

    public function __call($name, $arguments)
    {
        if (empty($arguments)) {
            $exchange = 'both';
        } else {
            $exchange = $arguments[0];
        }

        if ($exchange != 'nse' && $exchange != 'bse' && $exchange != 'both') {
            throw new ExchangeException('Incorrect parameter value. Only nse, bse or both is allowed.');
        }

        ExchangeDataObject::$serviceType = $name;

        $this->data = null;

        if ($exchange == 'nse' || $exchange == 'both') {
            $this->nse->{$name}();
            $this->data['nse'] = true;
        }

        if ($exchange == 'bse' || $exchange == 'both') {
            $this->bse->{$name}();
            $this->data['bse'] = true;
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
        $files = array();

        if (isset($this->data['nse'])) {
            $files[] = $this->csv()['nse']['file_path'];
        }

        if (isset($this->data['bse'])) {
            $files[] = $this->csv()['bse']['file_path'];
        }

        if (count($files) > 1) {
            $zipname = 'files.zip';
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
}
