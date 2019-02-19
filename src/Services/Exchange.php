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
        $data = ExchangeDataObject::$data[ExchangeDataObject::$exchange];

        if (is_array($data)) {
            foreach ($data as $row) {
                fputcsv($file, (is_string($row)) ? [$row] : $row);
            }
        } else {
            $data1 = json_decode($data, true);
            if (!is_array($data1)) {
                $data = explode("\n", $data);
                $data = array_filter($data);
                foreach ($data as $row) {
                    fputcsv($file, explode(',', $row));
                }
            } else {
                $data1 = array_filter($data1);
                fputcsv($file, array_keys(current($data1)));
                foreach ($data1 as $row) {
                    fputcsv($file, $row);
                }
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
        $data = ExchangeDataObject::$data[ExchangeDataObject::$exchange];
        if (!is_array($data)) {
            $data = explode("\n", $data);
        }

        $data = array_filter($data);
        $firstRow = current($data);
        if (!is_array($firstRow)) {
            $firstRow = explode(',', $firstRow);
        }
        $keys = array_keys($firstRow);
        $dataWithKeys = true;

        if ($keys == range(0, count($firstRow) - 1)) {
            $dataWithKeys = false;
            $keys = array_map(function($item) {
                return str_replace(' ', '_', strtolower(trim($item)));
            }, $firstRow);
            array_shift($data);
        }

        $rows = [];
        foreach ($data as $item) {
            $row = $item;
            if (!is_array($row)) {
                $row = explode(',', $item);
            }

            $add = [];
            foreach ($keys as $k => $v) {
                $add[$v] = $row[($dataWithKeys) ? $v : $k];
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
