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

    /** @var string */
    private $lat;

    /** @var string */
    private $lng;

    /** @var int */
    private $cityId;

    /** @var string */
    private $cityName;

    /** @var float */
    private $temp;

    /** @var int */
    private $pressure;

    /** @var int */
    private $humidity;

    /** @var float */
    private $tempMin;

    /** @var float */
    private $tempMax;

    /** @var float */
    private $windSpeed;

    /** @var float */
    private $windDeg;

    /** @var int */
    private $clouds;

    /** @var int */
    private $rainOneH;

    /** @var int */
    private $rainThreeH;

    /** @var int */
    private $snowOneH;

    /** @var int */
    private $snowThreeH;
}
