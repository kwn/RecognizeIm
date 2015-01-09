<?php

namespace RecognizeIm;

class Configuration
{
    const URL = 'http://clapi.itraff.pl/';

    const SINGLEIR_MAX_FILE_SIZE     = 500;  // KBytes
    const SINGLEIR_MIN_DIMENSION     = 100;  // pix
    const SINGLEIR_MIN_IMAGE_SURFACE = 0.05; // Mpix
    const SINGLEIR_MAX_IMAGE_SURFACE = 0.31; // Mpix

    const MULTIIR_MAX_FILE_SIZE     = 3500; // KBytes
    const MULTIIR_MIN_DIMENSION     = 100;  // pix
    const MULTIIR_MIN_IMAGE_SURFACE = 0.1;  // Mpix
    const MULTIIR_MAX_IMAGE_SURFACE = 5.1;  // Mpix

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