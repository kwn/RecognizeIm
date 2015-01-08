<?php

namespace RecognizeIm;

class RecognizeImAPIResultObject
{
    private $id       = null;
    private $name     = '';
    private $location = array();

    public function __construct($jsonData)
    {
        $this->id     = $jsonData['id'];
        $this->name   = $jsonData['name'];

        foreach ($jsonData['location'] as $point) {
            $this->location[] = (array) $point;
        }
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getLocation()
    {
        return $this->location;
    }
}