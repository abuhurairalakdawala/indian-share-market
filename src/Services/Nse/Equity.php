<?php
namespace IndianShareMarket\Services\Nse;

use IndianShareMarket\DataProviders\Url;

trait Equity
{
    /**
     * Fetches all the stocks of Nse.
     * 
     * @param  string $format
     * @return array
     */
    public function stockList(): string
    {
        $fileData = $this->parseDocument->pullDataFromRemote(Url::$nseStocks);

        return utf8_encode($fileData);
    }

    /**
     * Generates CSV file filled with nse equities.
     * 
     * @param  array  $data
     * @return array
     */
    public function equityInCsv(string $data): array
    {
        if (!is_dir($this->csvPath)) {
            if (!@mkdir($this->csvPath, 0777, true)) {
                throw new ExchangeException("Unable to create folder: $this->csvPath");
            }
        }

        $file = @fopen($this->csvPath.$this->csvEquitiesFilename, 'w');
        if (!$file) {
            throw new ExchangeException("Unable to create file: $this->csvPath$this->csvEquitiesFilename");
        }

        $data = explode("\n", $data);
        array_pop($data);

        foreach ($data as $row) {
            fputcsv($file, explode(',', $row));
        }
        fclose($file);

        return [
            'format' => 'csv',
            'file_path' => $this->csvPath.$this->csvEquitiesFilename
        ];
    }

    /**
     * Returns list of Nse equities in an array.
     *  
     * @param  array  $data
     * @return array
     */
    public function equityInArray(string $data): array
    {
        $data = explode("\n", $data);
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

        return [ 'format' => 'array', 'data' => $rows ];
    }
}
