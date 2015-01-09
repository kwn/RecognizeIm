<?php

namespace RecognizeIm\Service;

use RecognizeIm\Model\RecognizedImage;
use RecognizeIm\Result\RecognizeResult;

class FrameDrawer
{
    /**
     * @param string $file
     * @return bool|string
     */
    public function drawFrames($file, RecognizeResult $recognizeResult)
    {
        $im = imagecreatefromstring($file);

        if (!$im) {
            return false;
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