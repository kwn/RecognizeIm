<?php

namespace RecognizeIm\Service;

use RecognizeIm\Exception\RecognizeImException;
use RecognizeIm\Model\Image;
use RecognizeIm\Model\RecognizedImage;
use RecognizeIm\Result\RecognizeResult;

class FrameGenerator
{
    /**
     * @param string $file
     * @return bool|string
     */
    public function drawFrames(Image $file, RecognizeResult $recognizeResult)
    {
        $im = imagecreatefromstring($file->getFileContents());

        if (false === $im) {
            throw new RecognizeImException(
                'Image type is unsupported, the data is not in a recognised '.
                'format, or the image is corrupt and cannot be loaded.'
            );
        }

        $color = imagecolorallocate($im, 255, 255, 255);

        /** @var RecognizedImage $object */
        foreach ($recognizeResult->getObjects() as $object) {
            $location = $object->getLocation();
            $size     = count($location);

            for ($i = 0; $i < $size; ++$i) {
                $p1 = $location[$i];
                $p2 = $location[($i + 1) % $size];
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