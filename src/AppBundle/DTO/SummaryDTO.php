<?php
declare(strict_types=1);

namespace AppBundle\DTO;

class SummaryDTO
{
    /** @var int */
    private $howMany = 0;

    /** @var float */
    private $tempMin = 0;

    /** @var float */
    private $tempMax = 0;

    /** @var float */
    private $tempAvg = 0;

    /** @var string */
    private $mostSearchPlace;

    public function __construct(int $count, float $tempMin, float $tempMax, float $tempAvg, string $mostSearchPlace)
    {
        $this->howMany = $count;
        $this->tempMin = $tempMin;
        $this->tempMax = $tempMax;
        $this->tempAvg = $tempAvg;
        $this->mostSearchPlace = $mostSearchPlace;
    }

    /**
     * @return int
     */
    public function getHowMany(): int
    {
        return $this->howMany;
    }

    /**
     * @return float
     */
    public function getTempMin(): float
    {
        return $this->tempMin;
    }

    /**
     * @return float
     */
    public function getTempMax(): float
    {
        return $this->tempMax;
    }

    /**
     * @return float
     */
    public function getTempAvg(): float
    {
        return $this->tempAvg;
    }

    /**
     * @return string
     */
    public function getMostSearchPlace(): string
    {
        return $this->mostSearchPlace;
    }
}
