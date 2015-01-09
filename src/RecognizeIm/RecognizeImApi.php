<?php

namespace RecognizeIm;

use RecognizeIm\Client\RestApi;
use RecognizeIm\Client\SoapApi;
use RecognizeIm\Service\ImageVerificator;

class RecognizeImApi
{
    /**
     * @var SoapApi
     */
    private $soapApiClient;

    /**
     * @var RestApi
     */
    private $restApiClient;

    /**
     * @var Configuration
     */
    private $config;

    /**
     * @param array $config
     * @throws \Exception
     */
    public function __construct(SoapApi $soapApi, RestApi $restApi)
    {
        $this->soapApiClient = $soapApi;
        $this->restApiClient = $restApi;
    }

    /**
     * Get SoapApiClient
     *
     * @return SoapApi
     */
    public function getSoapApiClient()
    {
        return $this->soapApiClient;
    }

    /**
     * Get RestApiClient
     *
     * @return RestApi
     */
    public function getRestApiClient()
    {
        return $this->restApiClient;
    }
}
