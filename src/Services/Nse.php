<?php
namespace IndianShareMarket\Services;

use IndianShareMarket\DataProviders\Url;
use IndianShareMarket\Services\ParseDocument;
use IndianShareMarket\Exceptions\ExchangeException;

class Nse implements ExchangeInterface
{
    /** @var $parseDocument \IndianShareMarket\Services\ParseDocument */
    private $parseDocument;

    /** @var $csvPath string */
    private $csvPath = 'ism/csv/';

    /** @var $csvEquitiesFilename string */
    private $csvEquitiesFilename = 'nse_equities.csv';

    /** Nse constructor. */
    public function __construct()
    {
        $this->parseDocument = new ParseDocument();
    }

    /**
     * Fetches all the stocks of Nse in three formats array, csv or download a file.
     * 
     * @return array
     */
    public function stockList(string $format = 'array'): array
    {
    	if($format != 'csv' && $format != 'array') {
            throw new ExchangeException('Invalid value for format key. It should be either array, csv or download.');
        }

        $fileData = $this->parseDocument->pullDataFromRemote(Url::$nseStocks);
        $fileData = explode("\n", $fileData);
        array_pop($fileData);

        if ($format == 'csv') {
        	return $this->stockListCsv($fileData);
        }

        if ($format == 'array') {
        	return $this->stockListArray($fileData);
        }
    }

    /**
     * Generates CSV file filled with nse equities.
     * 
     * @param  array  $data
     * @return void
     */
    private function stockListCsv(array $data): array
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
    private function stockListArray(array $data): array
    {
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
