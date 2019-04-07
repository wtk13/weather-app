<?php
declare(strict_types=1);

namespace Tests\AppBundle\Controller;


use AppBundle\Entity\Weather;
use AppBundle\Test\Purger;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class LocationControllerTest extends WebTestCase
{
    private $client;

    protected function setUp()
    {
        $this->client = static::createClient();

        $ormPurger = new ORMPurger(self::$kernel->getContainer()
            ->get('doctrine')->getManager());
        $purger = new Purger(self::$kernel->getContainer()
            ->get('doctrine.orm.entity_manager'), $ormPurger);
        $purger->purge();
    }

    public function testPOSTAddLocation()
    {
        $data = [
            'lat' => 52.17,
            'lng' => 20.92
        ];

        $this->client->request(
            Request::METHOD_POST,
            '/add',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json'
            ],
            json_encode($data)
        );

        $body = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"'
        );
        $this->assertEquals(52.17, $body['lat']);
        $this->assertEquals(20.92, $body['lng']);
        $this->assertEquals('Załuski', $body['name']);
        $this->assertArrayHasKey('temp', $body);
        $this->assertArrayHasKey('pressure', $body);
        $this->assertArrayHasKey('humidity', $body);
        $this->assertArrayHasKey('tempMin', $body);
        $this->assertArrayHasKey('tempMax', $body);
        $this->assertArrayHasKey('windSpeed', $body);
        $this->assertArrayHasKey('windDeg', $body);
        $this->assertArrayHasKey('clouds', $body);
        $this->assertArrayHasKey('rainOneH', $body);
        $this->assertArrayHasKey('rainThreeH', $body);
        $this->assertArrayHasKey('snowOneH', $body);
        $this->assertArrayHasKey('snowThreeH', $body);
    }

    public function testPOSTValidationErorLatLngGreater()
    {
        $data = [
            'lat' => 111,
            'lng' => 199.2
        ];

        $this->client->request(
            Request::METHOD_POST,
            '/add',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json'
            ],
            json_encode($data)
        );

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());

        $body = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('This value should be less than or equal to "90".', $body['errors']['lat'][0]);
        $this->assertEquals('This value should be less than or equal to "180".', $body['errors']['lng'][0]);
    }

    public function testPOSTValidationErorLatLngLess()
    {
        $data = [
            'lat' => -111,
            'lng' => -199.2
        ];

        $this->client->request(
            Request::METHOD_POST,
            '/add',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json'
            ],
            json_encode($data)
        );

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());

        $body = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('This value should be greater than or equal to "-90".', $body['errors']['lat'][0]);
        $this->assertEquals('This value should be greater than or equal to "-180".', $body['errors']['lng'][0]);
    }

    public function testPOSTValidationErorLatNotBlank()
    {
        $data = [
            'lng' => 5
        ];

        $this->client->request(
            Request::METHOD_POST,
            '/add',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json'
            ],
            json_encode($data)
        );

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());

        $body = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('This value should not be blank.', $body['errors']['lat'][0]);
    }

    public function testPOSTValidationErorLngNotBlank()
    {
        $data = [
            'last' => 5
        ];

        $this->client->request(
            Request::METHOD_POST,
            '/add',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json'
            ],
            json_encode($data)
        );

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());

        $body = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('This value should not be blank.', $body['errors']['lng'][0]);
    }

    public function testGETList()
    {
        $this->createLocations();

        $this->client->request(
            Request::METHOD_GET,
            '/list'
        );

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $body = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertCount(10, $body['items']);
        $this->assertEquals(13, $body['count']);
        $this->assertEquals(1, $body['page']);
        $this->assertEquals(2, $body['pages']);
        $this->assertEquals(52.17, $body['items'][0]['lat']);
        $this->assertEquals(20.92, $body['items'][0]['lng']);
        $this->assertEquals(52.23, $body['items'][9]['lat']);
        $this->assertEquals(21.21, $body['items'][9]['lng']);
    }

    public function testGETListSecondPage()
    {
        $this->createLocations();

        $this->client->request(
            Request::METHOD_GET,
            '/list',
            [
                'page' => 2
            ]
        );

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $body = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertCount(3, $body['items']);
        $this->assertEquals(13, $body['count']);
        $this->assertEquals(2, $body['page']);
        $this->assertEquals(2, $body['pages']);
        $this->assertEquals(52.23, $body['items'][0]['lat']);
        $this->assertEquals(21.01, $body['items'][0]['lng']);
        $this->assertEquals(55, $body['items'][2]['lat']);
        $this->assertEquals(44, $body['items'][2]['lng']);
    }

    public function testGETListThirdPageNoItems()
    {
        $this->createLocations();

        $this->client->request(
            Request::METHOD_GET,
            '/list',
            [
                'page' => 3
            ]
        );

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $body = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertCount(0, $body['items']);
        $this->assertEquals(13, $body['count']);
        $this->assertEquals(3, $body['page']);
        $this->assertEquals(2, $body['pages']);
    }

    private function createLocations()
    {
        $test =
<<<EOD
[
  {
    "lat": 55.00,
    "lng": 44.00,
    "name": "Lesogorsk",
    "temp": 0.61,
    "pressure": 1025,
    "humidity": 93,
    "temp_min": 0.61,
    "temp_max": 0.61,
    "wind_speed": 1.67,
    "wind_deg": 125.501,
    "clouds": 92,
    "rain_one_h": null,
    "rain_three_h": 0,
    "snow_one_h": null,
    "snow_three_h": null
  },
  {
    "lat": 55.00,
    "lng": 44.00,
    "name": "Lesogorsk",
    "temp": 0.61,
    "pressure": 1025,
    "humidity": 93,
    "temp_min": 0.61,
    "temp_max": 0.61,
    "wind_speed": 1.67,
    "wind_deg": 125.501,
    "clouds": 92,
    "rain_one_h": null,
    "rain_three_h": 0,
    "snow_one_h": null,
    "snow_three_h": null
  },
  {
    "lat": 52.23,
    "lng": 21.01,
    "name": "Warsaw",
    "temp": 9.91,
    "pressure": 1009,
    "humidity": 50,
    "temp_min": 7.22,
    "temp_max": 12.22,
    "wind_speed": 4.6,
    "wind_deg": 80,
    "clouds": 0,
    "rain_one_h": null,
    "rain_three_h": null,
    "snow_one_h": null,
    "snow_three_h": null
  },
  {
    "lat": 52.23,
    "lng": 21.21,
    "name": "Sulejowek",
    "temp": 9.85,
    "pressure": 1009,
    "humidity": 50,
    "temp_min": 7.22,
    "temp_max": 12.22,
    "wind_speed": 4.6,
    "wind_deg": 80,
    "clouds": 0,
    "rain_one_h": null,
    "rain_three_h": null,
    "snow_one_h": null,
    "snow_three_h": null
  },
  {
    "lat": 52.23,
    "lng": 21.05,
    "name": "Warszawa",
    "temp": 9.85,
    "pressure": 1009,
    "humidity": 50,
    "temp_min": 7.22,
    "temp_max": 12.22,
    "wind_speed": 4.6,
    "wind_deg": 80,
    "clouds": 0,
    "rain_one_h": null,
    "rain_three_h": null,
    "snow_one_h": null,
    "snow_three_h": null
  },
  {
    "lat": 52.23,
    "lng": 21.06,
    "name": "Warszawa",
    "temp": 9.85,
    "pressure": 1009,
    "humidity": 50,
    "temp_min": 7.22,
    "temp_max": 12.22,
    "wind_speed": 4.6,
    "wind_deg": 80,
    "clouds": 0,
    "rain_one_h": null,
    "rain_three_h": null,
    "snow_one_h": null,
    "snow_three_h": null
  },
  {
    "lat": 52.28,
    "lng": 20.90,
    "name": "Marymont",
    "temp": 14.67,
    "pressure": 1008,
    "humidity": 58,
    "temp_min": 13.33,
    "temp_max": 16.11,
    "wind_speed": 1.5,
    "wind_deg": null,
    "clouds": 0,
    "rain_one_h": null,
    "rain_three_h": null,
    "snow_one_h": null,
    "snow_three_h": null
  },
  {
    "lat": 52.28,
    "lng": 20.90,
    "name": "Marymont",
    "temp": 14.31,
    "pressure": 1008,
    "humidity": 58,
    "temp_min": 12.22,
    "temp_max": 15.56,
    "wind_speed": 1.5,
    "wind_deg": null,
    "clouds": 0,
    "rain_one_h": null,
    "rain_three_h": null,
    "snow_one_h": null,
    "snow_three_h": null
  },
  {
    "lat": 52.28,
    "lng": 20.90,
    "name": "Marymont",
    "temp": 14.31,
    "pressure": 1008,
    "humidity": 58,
    "temp_min": 12.22,
    "temp_max": 15.56,
    "wind_speed": 1.5,
    "wind_deg": null,
    "clouds": 0,
    "rain_one_h": null,
    "rain_three_h": null,
    "snow_one_h": null,
    "snow_three_h": null
  },
  {
    "lat": 52.17,
    "lng": 20.92,
    "name": "Załuski",
    "temp": 14.46,
    "pressure": 1008,
    "humidity": 58,
    "temp_min": 12.22,
    "temp_max": 16.11,
    "wind_speed": 1.5,
    "wind_deg": null,
    "clouds": 0,
    "rain_one_h": null,
    "rain_three_h": null,
    "snow_one_h": null,
    "snow_three_h": null
  },
  {
    "lat": 52.17,
    "lng": 20.92,
    "name": "Załuski",
    "temp": 14.46,
    "pressure": 1008,
    "humidity": 58,
    "temp_min": 12.22,
    "temp_max": 16.11,
    "wind_speed": 1.5,
    "wind_deg": null,
    "clouds": 0,
    "rain_one_h": null,
    "rain_three_h": null,
    "snow_one_h": null,
    "snow_three_h": null
  },
  {
    "lat": 52.17,
    "lng": 20.92,
    "name": "Załuski",
    "temp": 14.56,
    "pressure": 1008,
    "humidity": 58,
    "temp_min": 12.22,
    "temp_max": 16.11,
    "wind_speed": 1.5,
    "wind_deg": null,
    "clouds": 0,
    "rain_one_h": null,
    "rain_three_h": null,
    "snow_one_h": null,
    "snow_three_h": null
  },
  {
    "lat": 52.17,
    "lng": 20.92,
    "name": "Załuski",
    "temp": 14.46,
    "pressure": 1008,
    "humidity": 58,
    "temp_min": 12.22,
    "temp_max": 16.11,
    "wind_speed": 1.5,
    "wind_deg": null,
    "clouds": 0,
    "rain_one_h": null,
    "rain_three_h": null,
    "snow_one_h": null,
    "snow_three_h": null
  }
]   
EOD;
        $locations = json_decode($test, true);


        $normalizers = [new ObjectNormalizer(null, new CamelCaseToSnakeCaseNameConverter())];
        $serializer = new Serializer($normalizers);

        foreach ($locations as $location) {
            self::$kernel->getContainer()
                ->get('doctrine.orm.entity_manager')->persist($serializer->denormalize($location,Weather::class));
        }

        self::$kernel->getContainer()
            ->get('doctrine.orm.entity_manager')->flush();
    }
}
