<?php
declare(strict_types=1);

namespace AppBundle\DTO;

class SummaryDTO
{
    /** @var int */
    private $count = 0;

    /** @var float */
    private $tempMin = 0;

    /** @var float */
    private $tempMax = 0;

    /** @var float */
    private $tempAvg = 0;

    public function __construct(int $count, float $tempMin, float $tempMax, float $tempAvg)
    {
        $this->count = $count;
        $this->tempMin = $tempMin;
        $this->tempMax = $tempMax;
        $this->tempAvg = $tempAvg;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
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
}
