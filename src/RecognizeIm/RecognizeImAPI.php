<?php

namespace RecognizeIm;

use RecognizeIm\Client\RestApi;
use RecognizeIm\Client\SoapApi;

// These are the limits for query images:
// for SingleIR
define("SINGLEIR_MAX_FILE_SIZE", 500);      //KBytes
define("SINGLEIR_MIN_DIMENSION", 100);      //pix
define("SINGLEIR_MIN_IMAGE_SURFACE", 0.05); //Mpix
define("SINGLEIR_MAX_IMAGE_SURFACE", 0.31); //Mpix

// for MultipleIR
define("MULTIIR_MAX_FILE_SIZE", 3500);      //KBytes
define("MULTIIR_MIN_DIMENSION", 100);       //pix
define("MULTIIR_MIN_IMAGE_SURFACE", 0.1);   //Mpix
define("MULTIIR_MAX_IMAGE_SURFACE", 5.1);   //Mpix

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

        $this->soapApiClient = new SoapApi();

        $this->soapApiClient->auth(
            $this->config->getClientId(),
            $this->config->getClapiKey(),
            null
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

    /**
     * Checks image limits, depending on the recognition mode
     * @param $imagePath the path to the file
     * @param $mode the recognition mode
     */
    public static function checkImageLimits($imagePath, $mode = 'single')
    {
        // check the correctness of the selected mode
        if (!in_array($mode, array('single', 'multi'))) {
            throw new \Exception('Wrong "mode" value. Should be "single" or "multi"');
        }

        // fetch image data
        $size       = filesize($imagePath) / 1000.0; //KB
        $dimensions = getimagesize($imagePath);
        $width      = $dimensions[0];
        $height     = $dimensions[1];
        $surface    = ($width * $height) / 1000000.0; //Mpix

        // check image data
        if ($mode == 'single') {
            if ($size > SINGLEIR_MAX_FILE_SIZE
                || $height < SINGLEIR_MIN_DIMENSION
                || $width < SINGLEIR_MIN_DIMENSION
                || $surface < SINGLEIR_MIN_IMAGE_SURFACE
                || $surface > SINGLEIR_MAX_IMAGE_SURFACE
            ) {
                return false;
            }
        } else {
            if ($size > MULTIIR_MAX_FILE_SIZE
                || $height < MULTIIR_MIN_DIMENSION
                || $width < MULTIIR_MIN_DIMENSION
                || $surface < MULTIIR_MIN_IMAGE_SURFACE
                || $surface > MULTIIR_MAX_IMAGE_SURFACE
            ) {
                return false;
            }
        }

        return true;
    }

};
