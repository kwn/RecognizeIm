<?php

namespace RecognizeIm\Client;

use RecognizeIm\Configuration;
use RecognizeIm\Exception\SoapApiException;
use RecognizeIm\Result\SoapRequestResult;

class SoapApi
{
    /**
     * @var \SoapClient
     */
    private $soapClient;

    /**
     * Constructor
     *
     * @param $clientId
     * @param $clapiKey
     */
    public function __construct(Configuration $configuration)
    {
        $this->soapClient = new \SoapClient(null, array(
            'location'   => Configuration::URL,
            'uri'        => Configuration::URL,
            'trace'      => 1,
            'cache_wsdl' => WSDL_CACHE_NONE
        ));

        $this->auth(
            $configuration->getClientId(),
            $configuration->getClapiKey(),
            null
        );
    }

    /**
     * @param SoapRequestResult $result
     * @throws SoapApiException
     */
    private function checkRequestResultSuccess(SoapRequestResult $result)
    {
        if ($result->getStatus() !== SoapRequestResult::STATUS_OK) {
            throw new SoapApiException(
                $result->getMessage(),
                $result->getStatus()
            );
        }
    }

    /**
     * @param string $clientId
     * @param string $keyClapi
     * @param string $ip
     * @throws SoapApiException
     */
    public function auth($clientId, $keyClapi, $ip = null)
    {
        $result = new SoapRequestResult(
            $this->soapClient->auth($clientId, $keyClapi, $ip)
        );

        $this->checkRequestResultSuccess($result);
    }

    /**
     * Set url for call after applying changes
     *
     * @param string|null $callbackUrl
     * @throws SoapApiException
     */
    public function callback($callbackUrl = null)
    {
        $result = new SoapRequestResult(
            $this->soapClient->callback($callbackUrl)
        );

        $this->checkRequestResultSuccess($result);
    }

    /**
     * Get total number of images in collection
     *
     * @return int
     * @throws SoapApiException
     */
    public function imageCount()
    {
        $result = new SoapRequestResult($this->soapClient->imageCount());

        $this->checkRequestResultSuccess($result);

        return $result->getData();
    }

    /**
     * Delete image from collection
     *
     * @param string $id
     * @throws SoapApiException
     */
    public function imageDelete($id)
    {
        $result = new SoapRequestResult($this->soapClient->imageDelete($id));

        $this->checkRequestResultSuccess($result);
    }

    /**
     * Get information about an image
     *
     * @param string $id
     * @return array
     * @throws SoapApiException
     */
    public function imageGet($id)
    {
        $result = new SoapRequestResult($this->soapClient->imageGet($id));

        $this->checkRequestResultSuccess($result);

        return $result->getData();
    }

    /**
     * Upload an image to images collection
     *
     * @param string $id
     * @param string $name
     * @param string $data
     * @throws SoapApiException
     */
    public function imageInsert($id, $name, $data)
    {
        $result = new SoapRequestResult(
            $this->soapClient->imageInsert($id, $name, $data)
        );

        $this->checkRequestResultSuccess($result);
    }

    /**
     * Get list of images, for given offset, limit and (?) sorting direction.
     * Available keys and default values:
     *   limit  => 10 (can't be greater than 50)
     *   offset => 0
     *
     * @param array $params
     *
     * @param array $params
     * @return array
     * @throws SoapApiException
     */
    public function imageList(array $params = array())
    {
        $result = new SoapRequestResult(
            $this->soapClient->imageList($params)
        );

        $this->checkRequestResultSuccess($result);

        return $result->getData();
    }

    /**
     * Return some metadata (?)
     *
     * @return array
     * @throws SoapApiException
     */
    public function imageMeta()
    {
        $result = $result = new SoapRequestResult(
            $this->soapClient->imageMeta()
        );

        $this->checkRequestResultSuccess($result);

        return $result->getData();
    }

    /**
     * Update image data. Available keys for $data array:
     *   id   => new id for image
     *   name => new name for image
     *
     * @param string $id
     * @param array $data
     * @throws SoapApiException
     */
    public function imageUpdate($id, array $data = array())
    {
        $result = new SoapRequestResult(
            $this->soapClient->imageUpdate($id, $data)
        );

        $this->checkRequestResultSuccess($result);
    }

    /**
     * Build index for current user
     *
     * @throws SoapApiException
     */
    public function indexBuild()
    {
        $result = new SoapRequestResult($this->soapClient->indexBuild());

        $this->checkRequestResultSuccess($result);
    }

    /**
     * Check status of index-bulding process
     *
     * @return array
     * @throws SoapApiException
     */
    public function indexStatus()
    {
        $result = new SoapRequestResult($this->soapClient->indexStatus());

        $this->checkRequestResultSuccess($result);

        return $result->getData();
    }

    /**
     * Get clapi_key for current user. If param $regenerate is true, generate
     * new key before returning it
     *
     * @param bool $regenerate
     * @return string
     * @throws SoapApiException
     */
    public function keyGet($regenerate = false)
    {
        $result = new SoapRequestResult(
            $this->soapClient->keyGet($regenerate)
        );

        $this->checkRequestResultSuccess($result);

        return $result->getData();
    }

    /**
     * Get available recognition modes for current user. Will return modes only
     * if you images collection is empty.
     *
     * @return array
     * @throws SoapApiException
     */
    public function modeAvailable()
    {
        $result = new SoapRequestResult($this->soapClient->modeAvailable());

        $this->checkRequestResultSuccess($result);

        return $result->getData();
    }

    /**
     * Change recognition mode for current user. Available options (case
     * insensitive): Single, Multi
     *
     * @param string $mode
     * @throws SoapApiException
     */
    public function modeChange($mode)
    {
        $result = new SoapRequestResult($this->soapClient->modeChange($mode));

        $this->checkRequestResultSuccess($result);
    }

    /**
     * Get recognition mode for current user.
     *
     * @return string
     * @throws SoapApiException
     */
    public function modeGet()
    {
        $result = new SoapRequestResult($this->soapClient->modeGet());

        $this->checkRequestResultSuccess($result);

        return $result->getData();
    }

    /**
     * Get payment list
     *
     * @return array
     * @throws SoapApiException
     */
    public function paymentList()
    {
        $result = new SoapRequestResult($this->soapClient->paymentList());

        $this->checkRequestResultSuccess($result);

        return $result->getData();
    }

    /**
     * Delete current user
     *
     * @throws SoapApiException
     */
    public function userDelete()
    {
        $result = new SoapRequestResult($this->soapClient->userDelete());

        $this->checkRequestResultSuccess($result);
    }

    /**
     * Get information about current user
     *
     * @return array
     * @throws SoapApiException
     */
    public function userGet()
    {
        $result = new SoapRequestResult($this->soapClient->userGet());

        $this->checkRequestResultSuccess($result);

        return $result->getData();
    }

    /**
     * Get information about current limits
     *
     * @return array
     * @throws SoapApiException
     */
    public function userLimits()
    {
        $result = new SoapRequestResult($this->soapClient->userLimits());

        $this->checkRequestResultSuccess($result);

        return $result->getData();
    }

    /**
     * Update information about current user
     *
     * @param array $infoBilling
     * @param array $infoInvoice
     * @throws SoapApiException
     */
    public function userUpdate(
        array $infoBilling = array(),
        array $infoInvoice = array()
    ) {
        $result = new SoapRequestResult(
            $this->soapClient->userUpdate($infoBilling, $infoInvoice)
        );

        $this->checkRequestResultSuccess($result);
    }
}