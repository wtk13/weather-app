<?php
declare(strict_types=1);

namespace AppBundle\Processor;

use AppBundle\ApiProvider\OpenWeatherApiProvider;
use AppBundle\DTO\LatLngDTO;
use AppBundle\Entity\Weather;
use AppBundle\Manager\WeatherManager;

class LocationProcessor
{
    private $openWeatherApi;
    private $weatherManager;

    public function __construct(OpenWeatherApiProvider $openWeatherApi, WeatherManager $weatherManager)
    {
        $this->openWeatherApi = $openWeatherApi;
        $this->weatherManager = $weatherManager;
    }

    public function process(LatLngDTO $latLngDTO): Weather
    {
        $apiData = $this->openWeatherApi
            ->getWeatherData($latLngDTO);

        return $this->weatherManager
            ->save($apiData);
    }
}
