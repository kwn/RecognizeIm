<?php

namespace RecognizeIm;

use RecognizeIm\Client\RestApi;
use RecognizeIm\Client\SoapApi;

class RecognizeImAPI
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
    public function __construct(array $config)
    {
        $this->config = new Configuration(
            $config['CLIENT_ID'],
            $config['API_KEY'],
            $config['CLAPI_KEY']
        );

        $this->soapApiClient = new SoapApi(
            $this->config->getClientId(),
            $this->config->getClapiKey()
        );

        $this->restApiClient = new RestApi(
            $this->config->getClientId(),
            $this->config->getApiKey()
        );
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
