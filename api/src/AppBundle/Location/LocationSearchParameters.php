<?php

namespace AppBundle\Location;

class LocationSearchParameters
{
    const UNIT_KM = 6371;
    const UNIT_MILE = 3959;

    /**
     * @var float
     */
    private $latitude;

    /**
     * @var float
     */
    private $longitude;

    /**
     * @var float
     */
    private $radius;

    /**
     * @var int
     */
    private $unit;

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * @return float
     */
    public function getRadius()
    {
        return $this->radius;
    }

    /**
     * @param float $radius
     */
    public function setRadius($radius)
    {
        $this->radius = $radius;
    }

    /**
     * @return int
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @param int $unit
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;
    }
}