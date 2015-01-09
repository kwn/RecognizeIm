<?php

namespace RecognizeIm\Service;

use RecognizeIm\Configuration;
use RecognizeIm\Exception\RecognizeImException;
use RecognizeIm\Model\Image;

class ImageVerificator
{
    /**
     * Checks image limits, depending on the recognition mode
     *
     * @param Image $image the path to the file
     * @param string $mode the recognition mode
     * @return bool
     */
    public function imageLimitsCorrect(Image $image, $mode = 'single')
    {
        if (!in_array($mode, array('single', 'multi'))) {
            throw new RecognizeImException('Wrong "mode" value. Should be "single" or "multi"');
        }

        $size       = filesize($image->getPath()) / 1000.0; // KB
        $dimensions = getimagesize($image->getPath());
        $width      = $dimensions[0];
        $height     = $dimensions[1];
        $surface    = ($width * $height) / 1000000.0; // Mpix

        if ($mode === 'single') {
            if ($size > Configuration::SINGLEIR_MAX_FILE_SIZE
                || $height < Configuration::SINGLEIR_MIN_DIMENSION
                || $width < Configuration::SINGLEIR_MIN_DIMENSION
                || $surface < Configuration::SINGLEIR_MIN_IMAGE_SURFACE
                || $surface > Configuration::SINGLEIR_MAX_IMAGE_SURFACE
            ) {
                return false;
            }
        } else {
            if ($size > Configuration::MULTIIR_MAX_FILE_SIZE
                || $height < Configuration::MULTIIR_MIN_DIMENSION
                || $width < Configuration::MULTIIR_MIN_DIMENSION
                || $surface < Configuration::MULTIIR_MIN_IMAGE_SURFACE
                || $surface > Configuration::MULTIIR_MAX_IMAGE_SURFACE
            ) {
                return false;
            }
        }

        return true;
    }
}
