<?php

namespace RecognizeIm;

class RecognizeImAPIResult
{
    const STATUS_OK       = 0;
    const STATUS_NO_MATCH = 1;

    protected $status  = self::STATUS_OK;
    protected $message = '';
    protected $objects = array();

    public function __construct($jsonData)
    {
        $this->status = $jsonData['status'];

        if ($this->status != self::STATUS_OK) {
            $this->message = $jsonData['message'];
            return;
        }

        foreach ($jsonData['objects'] as $obj) {
            $this->objects[] = new RecognizeImAPIResultObject((array) $obj);
        }

        if (empty($this->objects)) {
            $this->status = self::STATUS_NO_MATCH;
            $this->message = 'No match found';
        }
    }

    public function __toString()
    {
        if (!$this->isOK()) {
            return $this->message;
        }

        return 'Status OK {' . implode("\n", $this->objects) . '}';
    }

    public function isOK()
    {
        return $this->status == self::STATUS_OK;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getObjects()
    {
        return $this->objects;
    }

    public function drawFrames($file)
    {
        $im = imagecreatefromstring($file);
        if (!$im) {
            return false;
        }

        $color = imagecolorallocate($im, 255, 255, 255);

        foreach ($this->objects as $object) {
            $location = $object->getLocation();
            $size = count($location);

            for ($i = 0; $i < $size; ++$i) {
                $p1 = $location[$i];
                $p2 = $location[($i+1)%$size];
                imageline($im, $p1['x'], $p1['y'], $p2['x'], $p2['y'], $color);
            }
        }

        ob_start();
        imagejpeg($im);
        $img = ob_get_clean();
        imagedestroy($im);
        return $img;
    }
}