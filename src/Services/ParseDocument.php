<?php
namespace IndianShareMarket\Services;

class ParseDocument
{
    private $domDocument;

    /**
     * ParseDocument constructor
     */
    public function __construct()
    {
        $this->setDomDocument();
    }

    /**
     * Get domDocument object
     * 
     * @return domDocument
     */
    public function getDomDocument()
    {
        return $this->domDocument;
    }

    /**
     * Set domDocument object
     */
    public function setDomDocument()
    {
        $this->domDocument = new \domDocument();
    }

    /**
     * This function will pull data from remote URLs
     * 
     * @return string
     */
    public function pullDataFromRemote($url): string
    {
        $context = stream_context_create(
            array(
                'http' => array(
                    'header' => array('User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; rv:2.2) Gecko/20110201')
                )
            )
        );

        return @file_get_contents(
            $url,
            false,
            $context
        );
    }

    /**
     * Initialize scrapping websites
     * 
     * @param  string $url
     * @return bool
     */
    public function get($url)
    {
        $file = $this->pullDataFromRemote($url);
        libxml_use_internal_errors(true);

        return ($file) ? $this->domDocument->loadHTML($file) : false;
    }

    /**
     * Search dom by id attribute
     * 
     * @param  string $id
     * @return string
     */
    public function findId($id)
    {
        $element = $this->domDocument->getElementById($id);

        return $element->nodeValue;
    }

    /**
     * Search dom by class attribute
     * 
     * @param  string $class
     * @return DOMNodeList
     */
    public function findClass($class)
    {
        $finder = new \DomXPath($this->domDocument);

        return $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), '$class')]");
    }
}
