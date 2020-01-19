<?php

namespace GovInfo;

use \GuzzleHttp\Psr7\Uri;
use \GuzzleHttp\Client;
use \LogicException;

final class Api
{
    private const URL = 'api.govinfo.gov';
    private $objHttp;
    private $strApiKey;
    
    /**
     * Construct an instance
     * 
     * @param Client $objHttp
     * @param string $strApiKey
     */
    public function __construct(Client $objHttp, string $strApiKey = '')
    {
        $this->objHttp = $objHttp;
        $this->strApiKey = $strApiKey;
    }
    
    /**
     * HTTP GET
     * 
     * @param Uri $objUri
     * @return \GuzzleHttp\Psr7\Response
     */
    public function get(Uri $objUri) : \GuzzleHttp\Psr7\Response
    {
        if (empty($objUri->getPath())) {
            throw new LogicException('Uri must contain a valid path');
        }

        if (empty($this->strApiKey)) {
            throw new LogicException('Api Key is required');
        }

        $objUri = $objUri->withHost(self::URL)->withScheme('https');
        $objUri = $objUri->withQueryValue($objUri, 'api_key', $this->strApiKey);

        return $this->objHttp->get($objUri);
    }
    
    /**
     * Performs HTTP GET and returns as an object
     * 
     * @param Uri $objUri
     * @return array
     */
    public function getArray(Uri $objUri) : array
    {
        $objResponse = $this->get($objUri);
        return json_decode($objResponse->getBody()->getContents(), true);
    }

    public function getObjHttp() : Client
    {
        return $this->objHttp;
    }

    public function setStrApiKey(string $key) : self
    {
        $this->strApiKey = $key;
        return $this;
    }

    public function getStrApiKey() : string
    {
        return $this->strApiKey;
    }    
}