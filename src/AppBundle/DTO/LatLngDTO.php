<?php
declare(strict_types=1);

namespace AppBundle\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class LatLngDTO
{
    /**
     * @var float
     *
     * @Assert\NotBlank()
     * @Assert\Type(type="float")
     * @Assert\LessThanOrEqual(value="90")
     * @Assert\GreaterThanOrEqual(value="-90")
     */
    private $lat;

    /**
     * @var float
     *
     * @Assert\NotBlank()
     * @Assert\Type(type="float")
     * @Assert\LessThanOrEqual(value="180")
     * @Assert\GreaterThanOrEqual(value="-180")
     */
    private $lng;

    /**
     * @return float
     */
    public function getLat(): ?float
    {
        return $this->lat;
    }

    /**
     * @param float $lat
     */
    public function setLat(float $lat): void
    {
        $this->lat = $lat;
    }

    /**
     * @return float
     */
    public function getLng(): ?float
    {
        return $this->lng;
    }

    /**
     * @param float $lng
     */
    public function setLng(float $lng): void
    {
        $this->lng = $lng;
    }
}
