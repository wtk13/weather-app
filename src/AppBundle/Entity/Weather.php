<?php
declare(strict_types=1);

namespace AppBundle\Entity;

/**
 * Weather
 */
class Weather
{
    /** @var int */
    private $id;

    /** @var float */
    private $lat;

    /** @var float */
    private $lng;

    /** @var string */
    private $name;

    /** @var float */
    private $temp;

    /** @var float */
    private $pressure;

    /** @var int */
    private $humidity;

    /** @var float */
    private $tempMin;

    /** @var float */
    private $tempMax;

    /** @var float */
    private $windSpeed;

    /** @var float|null */
    private $windDeg;

    /** @var int */
    private $clouds;

    /** @var float|null */
    private $rainOneH;

    /** @var float|null */
    private $rainThreeH;

    /** @var float|null */
    private $snowOneH;

    /** @var float|null */
    private $snowThreeH;

    public function __construct(
        float $lat,
        float $lng,
        string $name,
        float $temp,
        float $pressure,
        int $humidity,
        float $tempMin,
        float $tempMax,
        float $windSpeed,
        ?float $windDeg,
        int $clouds,
        ?float $rainOneH,
        ?float $rainThreeH,
        ?float $snowOneH,
        ?float $snowThreeH
    ) {
        $this->lat = $lat;
        $this->lng = $lng;
        $this->name = $name;
        $this->temp = $temp;
        $this->pressure = $pressure;
        $this->humidity = $humidity;
        $this->tempMin = $tempMin;
        $this->tempMax = $tempMax;
        $this->windSpeed = $windSpeed;
        $this->windDeg = $windDeg;
        $this->clouds = $clouds;
        $this->rainOneH = $rainOneH;
        $this->rainThreeH = $rainThreeH;
        $this->snowOneH = $snowOneH;
        $this->snowThreeH = $snowThreeH;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getLat(): float
    {
        return $this->lat;
    }

    /**
     * @return float
     */
    public function getLng(): float
    {
        return $this->lng;
    }

    /**
     * @return string
     */
    public function getname(): string
    {
        return $this->name;
    }

    /**
     * @return float
     */
    public function getTemp(): float
    {
        return $this->temp;
    }

    /**
     * @return float
     */
    public function getPressure(): float
    {
        return $this->pressure;
    }

    /**
     * @return int
     */
    public function getHumidity(): int
    {
        return $this->humidity;
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
    public function getWindSpeed(): float
    {
        return $this->windSpeed;
    }

    /**
     * @return float|null
     */
    public function getWindDeg(): ?float
    {
        return $this->windDeg;
    }

    /**
     * @return int
     */
    public function getClouds(): int
    {
        return $this->clouds;
    }

    /**
     * @return float|null
     */
    public function getRainOneH(): ?float
    {
        return $this->rainOneH;
    }

    /**
     * @return float|null
     */
    public function getRainThreeH(): ?float
    {
        return $this->rainThreeH;
    }

    /**
     * @return float|null
     */
    public function getSnowOneH(): ?float
    {
        return $this->snowOneH;
    }

    /**
     * @return float|null
     */
    public function getSnowThreeH(): ?float
    {
        return $this->snowThreeH;
    }
}
