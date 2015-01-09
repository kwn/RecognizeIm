<?php

namespace RecognizeIm\Model;

use RecognizeIm\Exception\RecognizeImException;

class Image
{
    /**
     * @var string
     */
    private $path;

    /**
     * Constructor
     *
     * @param $path
     */
    public function __construct($path)
    {
        if (!is_file($path)) {
            throw new RecognizeImException(
                sprintf('File: "%s" does not exists.', $path)
            );
        }

        if (!is_readable($path)) {
            throw new RecognizeImException(
                sprintf('Cannot read file: "%s". Check permissions.', $path)
            );
        }

        $this->path = $path;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getFileContents();
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getFileContents()
    {
        return file_get_contents($this->path);
    }
}