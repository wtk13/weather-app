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
            'lat' => 88.55,
            'lng' => 99.2
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

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"'
        );
    }
}
