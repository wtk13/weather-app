<?php
declare(strict_types=1);

namespace AppBundle\Repository;

use AppBundle\Entity\Weather;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class WeatherRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Weather::class);
    }

    public function save(Weather $weather): Weather
    {
        $this->_em
            ->persist($weather);

        $this->_em
            ->flush();

        return $weather;
    }
}
