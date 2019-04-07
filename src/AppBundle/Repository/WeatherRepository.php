<?php
declare(strict_types=1);

namespace AppBundle\Repository;

use AppBundle\Entity\Weather;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class WeatherRepository extends ServiceEntityRepository
{
    public const LIMIT = 10;

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

    public function findAllWithPagination(int $page): array
    {
        return $this->_em
            ->getConnection()
            ->createQueryBuilder()
            ->select('*')
            ->from('weather')
            ->setFirstResult(($page - 1) * self::LIMIT)
            ->setMaxResults(self::LIMIT)
            ->orderBy('id', 'DESC')
            ->execute()
            ->fetchAll();
    }

    public function count(): int
    {
        return (int) $this->_em
            ->getConnection()
            ->createQueryBuilder()
            ->select('count(id)')
            ->from('weather')
            ->execute()
            ->fetchColumn();
    }

    public function summary(): array
    {
        return $this->_em
            ->getConnection()
            ->createQueryBuilder()
            ->select('count(id) as howMany, round(min(temp_min), 2) as tempMin, round(max(temp_max), 2) as tempMax, round(avg(temp), 2) as tempAvg')
            ->from('weather')
            ->execute()
            ->fetch();
    }
}
