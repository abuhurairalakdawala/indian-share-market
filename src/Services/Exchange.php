<?php
namespace IndianShareMarket\Services;

use IndianShareMarket\Exceptions\ExchangeException;
use IndianShareMarket\DataProviders\ExchangeDataObject;

class Exchange
{
    /** @var $csvPath string */
    protected $csvPath = 'ism/csv/';

    /**
     * Generates a directory to store csv.
     * 
     * @return void
     */
    public function createCsvDirectory()
    {
        if (!is_dir($this->csvPath)) {
            if (!@mkdir($this->csvPath, 0777, true)) {
                throw new ExchangeException("Unable to create folder: $this->csvPath");
            }
        }
    }

    /**
     * Creates a resource file.
     * 
     * @param  string $filename
     * @return resource
     */
    public function createCsvFile(string $filename)
    {
        $file = @fopen($this->csvPath.$filename, 'w');
        if (!$file) {
            throw new ExchangeException("Unable to create file: $this->csvPath$filename");
        }

        return $file;
    }

    /**
     * Fills data in Csv.
     * 
     * @param  resource $file
     * @return void
     */
    public function fillCsv($file)
    {
        $data = json_decode(ExchangeDataObject::$data[ExchangeDataObject::$exchange], true);
        if($data) {
            fputcsv($file, array_keys(current($data)));
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
        } else {
            $data = explode("\n", ExchangeDataObject::$data[ExchangeDataObject::$exchange]);
            foreach ($data as $row) {
                fputcsv($file, explode(',', $row));
            }
        }

        fclose($file);
    }

    /**
     * Generates CSV.
     * 
     * @param  string $filename
     * @return void
     */
    public function generateCsv(string $filename)
    {
        $this->createCsvDirectory();
        $file = $this->createCsvFile($filename);
        $this->fillCsv($file);
    }

    /**
     * Prepares array from the data which is stored in ExchangeDataObject.
     * 
     * @return array
     */
    public function prepareArray(): array
    {
        $data = explode("\n", ExchangeDataObject::$data[ExchangeDataObject::$exchange]);
        array_pop($data);
        $firstRow = strtolower(current($data));
        $keys = array_map(function($item) {
            return str_replace(' ', '_', trim($item));
        }, explode(',', $firstRow));
        array_shift($data);

        $rows = [];
        foreach ($data as $item) {
            $row = explode(',', $item);
            $add = [];
            foreach ($keys as $k => $v) {
                $add[$v] = $row[$k];
            }
            array_push($rows, $add);
        }

        return $rows;
    }

    /**
     * Convert data from ExchangeDataObject to array.
     * 
     * @return array
     */
    public function convertJsonToArray(): array
    {
        return json_decode(ExchangeDataObject::$data[ExchangeDataObject::$exchange], true);
    }
}
