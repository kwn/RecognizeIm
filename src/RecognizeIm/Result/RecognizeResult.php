<?php

namespace RecognizeIm\Result;

use RecognizeIm\Model\RecognizedImage;

class RecognizeResult
{
    const STATUS_SEND_ERROR = -1;
    const STATUS_MATCHED    = 0;
    const STATUS_INVALID    = 1;
    const STATUD_NO_MATCH   = 2;

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
    private $objects;

    /**
     * @param \stdClass $data
     */
    public function __construct(\stdClass $data)
    {
        $this->status  = $data->status;
        $this->message = isset($data->message) ? $data->message : null;
        $this->objects = array();

        if (isset($data->objects) && is_array($data->objects)) {
            foreach ($data->objects as $object) {
                $this->objects[] = new RecognizedImage($object);
            }
        }
    }

    /**
     * Get Status
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get Message
     *
     * @return null|string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Get Objects
     *
     * @return array
     */
    public function getObjects()
    {
        return $this->objects;
    }
}