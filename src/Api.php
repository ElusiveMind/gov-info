<?php

namespace GovInfo;

use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Client;
use LogicException;
use Psr\Http\Message\ResponseInterface;

final class Api
{
    private const URL = 'api.govinfo.gov';
    private $objHttp;
    private $strApiKey;
    private $objUri;

    // We want to keep track on the number of requests we have left.
    public $rateLimitRemaining;

    // Keep track of the number of requests we have in total.
    public $rateLimit;

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
      $this->rateLimitRemaining = NULL;
      $this->rateLimit = NULL;
    }

    /**
     * HTTP GET
     * 
     * @param Uri $objUri
     * @return Response
     */
    public function get(Uri $objUri) : ResponseInterface
    {
        if (empty($objUri->getPath())) {
            throw new LogicException('Uri must contain a valid path');
        }

        if (empty($this->strApiKey)) {
            throw new LogicException('Api Key is required');
        }

        $objUri = $objUri->withHost(self::URL)->withScheme('https');
        $objUri = $objUri->withQueryValue($objUri, 'api_key', $this->strApiKey);
        $this->objUri =$objUri;

        $objResponse = $this->objHttp->get($this->objUri);
        $rateLimitRemaining = $objResponse->getHeader('X-RateLimit-Remaining');
        $rateLimit = $objResponse->getHeader('X-RateLimit-Limit');

        if (!empty($rateLimit[0]) && (!empty($rateLimitRemaining[0]))) {
          $this->setRateLimit($rateLimit[0]);
          $this->setRateLimitRemaining($rateLimitRemaining[0]);
        }

        return $objResponse;
    }

    public function getData(Uri $objUri) : string {
        $objResponse = $this->get($objUri);
        $contentLength = $objResponse->getHeader('Content-Length');
        $body = $objResponse->getBody();
        $body->seek(0);
        return $body->read($contentLength[0]);
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

    public function getObjUri() : Uri
    {
        return $this->objUri;
    }

    public function setRateLimit($rateLimit) : self
    {
        $this->rateLimit = $rateLimit;
        return $this;
    }

    public function getRateLimit() : int
    {
        return (int) $this->rateLimit;
    }

    public function setRateLimitRemaining($rateLimitRemaining) : self
    {
        $this->rateLimitRemaining = $rateLimitRemaining;
        return $this;
    }

    public function getRateLimitRemaining() : int
    {
        return (int) $this->rateLimitRemaining;
    }
}