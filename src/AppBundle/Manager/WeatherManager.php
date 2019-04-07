<?php
declare(strict_types=1);

namespace AppBundle\Manager;

use AppBundle\DTO\LocationDTO;
use AppBundle\DTO\SummaryDTO;
use AppBundle\Entity\Weather;
use AppBundle\Repository\WeatherRepository;

class WeatherManager
{
    private $weatherRepository;

    public function __construct(WeatherRepository $weatherRepository)
    {
        $this->weatherRepository = $weatherRepository;
    }

    public function save(array $params): Weather
    {
        $weather = new Weather(
            $params['coord']['lat'],
            $params['coord']['lon'],
            $params['name'],
            $params['main']['temp'],
            $params['main']['pressure'],
            $params['main']['humidity'],
            $params['main']['temp_min'],
            $params['main']['temp_max'],
            $params['wind']['speed'],
            $params['wind']['deg'] ?? null,
            $params['clouds']['all'],
            $params['rain']['1h'] ?? null,
            $params['rain']['3h'] ?? null,
            $params['snow']['1h'] ?? null,
            $params['snow']['3h'] ?? null
        );

        $this->weatherRepository
            ->save($weather);

        return $weather;
    }

    public function list(int $page): LocationDTO
    {
        $allItemsCount = $this->weatherRepository->count();
        $pages = (int) ceil($allItemsCount / WeatherRepository::LIMIT);

        return new LocationDTO(
            $this->weatherRepository->findAllWithPagination($page),
            $allItemsCount,
            $page,
            $pages
        );
    }

    public function summary(): SummaryDTO
    {
        $summaryData = $this->weatherRepository
            ->summary();

        return new SummaryDTO(
            (int) $summaryData['howMany'],
            (float) $summaryData['tempMin'],
            (float) $summaryData['tempMax'],
            (float) $summaryData['tempAvg']
        );
    }
}
