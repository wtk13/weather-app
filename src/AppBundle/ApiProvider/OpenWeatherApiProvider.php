<?php
declare(strict_types=1);

namespace AppBundle\ApiProvider;

use AppBundle\Api\ApiProblem;
use AppBundle\Api\ApiProblemException;
use AppBundle\DTO\LatLngDTO;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class OpenWeatherApiProvider
{
    private const URI = 'api.openweathermap.org/data/2.5/';

    private $apiKey;
    private $client;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->client = new Client([
            'base_uri' => self::URI
        ]);
    }

    public function getWeatherData(LatLngDTO $latLngDTO): array
    {
        try {
            $client = $this->client
                ->request('GET', 'weather', [
                    'query' => [
                        'appid' => $this->apiKey,
                        'units' => 'metric',
                        'lat' => $latLngDTO->getLat(),
                        'lon' => $latLngDTO->getLng()
                    ]
                ]);
        } catch (GuzzleException $e) {
            throw new ApiProblemException(new ApiProblem(500, ApiProblem::TYPE_PROCESS_ERROR));
        }

        return json_decode($client->getBody()->getContents(), true);
    }
}
