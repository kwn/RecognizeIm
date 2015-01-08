<?php

namespace RecognizeIm;


class Configuration
{
    const URL = 'http://clapi.itraff.pl/';

    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $clapiKey;

    /**
     * Constructor
     *
     * @param string $clientId
     * @param string $apiKey
     * @param string $clapiKey
     */
    public function __construct($clientId, $apiKey, $clapiKey)
    {
        $this->clientId = $clientId;
        $this->apiKey   = $apiKey;
        $this->clapiKey = $clapiKey;
    }

    /**
     * Get ClientId
     *
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Get ApiKey
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Get ClapiKey
     *
     * @return string
     */
    public function getClapiKey()
    {
        return $this->clapiKey;
    }
}