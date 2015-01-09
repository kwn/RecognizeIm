<?php

namespace RecognizeIm;

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
        
        $result = $this->soapApiClient->auth(
            $this->config->getClientId(),
            $this->config->getClapiKey(),
            null
        );

        if (is_object($result)) {
            $result = (array) $result;
        }

        if ($result['status']) {
            throw new \Exception("Cannot authenticate");
        } 
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

    /**
     * Recognize object using image in single mode
     * @param $image query
     * @param $mode Recognition mode. Should be 'single' or 'multi'. Default is 'single'.
     * @param $getAll if TRUE returns all recognized objects in 'single' mode, otherwize only the best one; in 'multi' it enables searching for multiple instances of each object
     * @returns associative array containg recognition result
     */
    public function recognize($image, $mode = 'single', $getAll = false)
    {
        $hash = md5($this->config->getApiKey().$image);
        $url  = Configuration::URL.'v2/recognize/'.$mode.'/';

        if ($getAll) {
            $url .= 'all/';
        }

        $url .= $this->config->getClientId();

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('x-itraff-hash: '.$hash, 'Content-type: image/jpeg'));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $image);

        $obj    = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $res = array();

        if ($status != '200') {
            //throw new \Exception('Cannot upload photo');
            $res = array('status' => -1, 'message' => 'Cannot upload photo');
        } else {
            $res = (array) json_decode($obj);
        }

        return new RecognizeImAPIResult($res);
    }
};
