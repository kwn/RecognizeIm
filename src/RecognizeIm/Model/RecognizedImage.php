<?php

namespace RecognizeIm\Model;

class RecognizedImage
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $location;

    /**
     * Constructor
     *
     * @param \stdClass $object
     */
    public function __construct(\stdClass $object)
    {
        $this->id       = $object->id;
        $this->name     = $object->name;
        $this->location = array();

        foreach ($object->location as $point) {
            $this->location[] = (array) $point;
        }
    }

    /**
     * Get Id
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get Name
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get Location
     *
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }
}