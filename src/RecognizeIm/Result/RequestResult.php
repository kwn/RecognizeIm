<?php

namespace RecognizeIm\Result;

class RequestResult
{
    const STATUS_OK    = 0;
    const STATUS_ERROR = 1;

    /**
     * @var int
     */
    private $status;

    /**
     * @var string|null
     */
    private $message;

    /**
     * @var array
     */
    private $data;

    /**
     * @param array $result
     */
    public function __construct(array $result = array())
    {
        $this->status  = $result['status'];
        $this->message = isset($result['message']) ? $result['message'] : null;
        $this->data    = isset($result['data']) ? $result['data'] : null;
    }

    /**
     * Get Status
     *
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get Message
     *
     * @return string|null
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Get Data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}