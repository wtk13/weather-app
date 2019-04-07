<?php
declare(strict_types=1);

namespace Tests\AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class LocationControllerTest extends WebTestCase
{
    public function testPOSTAddLocation()
    {
        $client = static::createClient();

        $data = [
            'lat' => 52.17,
            'lng' => 20.92
        ];

        $client->request(
            Request::METHOD_POST,
            '/add',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json'
            ],
            json_encode($data)
        );

        $body = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertTrue(
            $client->getResponse()->headers->contains(
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
        $client = static::createClient();

        $data = [
            'lat' => 111,
            'lng' => 199.2
        ];

        $client->request(
            Request::METHOD_POST,
            '/add',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json'
            ],
            json_encode($data)
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());

        $body = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('This value should be less than or equal to "90".', $body['errors']['lat'][0]);
        $this->assertEquals('This value should be less than or equal to "180".', $body['errors']['lng'][0]);
    }

    public function testPOSTValidationErorLatLngLess()
    {
        $client = static::createClient();

        $data = [
            'lat' => -111,
            'lng' => -199.2
        ];

        $client->request(
            Request::METHOD_POST,
            '/add',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json'
            ],
            json_encode($data)
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());

        $body = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('This value should be greater than or equal to "-90".', $body['errors']['lat'][0]);
        $this->assertEquals('This value should be greater than or equal to "-180".', $body['errors']['lng'][0]);
    }

    public function testPOSTValidationErorLatNotBlank()
    {
        $client = static::createClient();

        $data = [
            'lng' => 5
        ];

        $client->request(
            Request::METHOD_POST,
            '/add',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json'
            ],
            json_encode($data)
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());

        $body = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('This value should not be blank.', $body['errors']['lat'][0]);
    }

    public function testPOSTValidationErorLngNotBlank()
    {
        $client = static::createClient();

        $data = [
            'last' => 5
        ];

        $client->request(
            Request::METHOD_POST,
            '/add',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json'
            ],
            json_encode($data)
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());

        $body = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('This value should not be blank.', $body['errors']['lng'][0]);
    }
}
