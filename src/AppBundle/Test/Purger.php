<?php
/**
 * Created by PhpStorm.
 * User: wtk
 * Date: 08.11.2016
 * Time: 11:33
 */
namespace AppBundle\Test;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;

class Purger extends ORMPurger
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ORMPurger
     */
    private $purger;
    /**
     * @param EntityManagerInterface $entityManager
     * @param ORMPurger              $purger
     */
    public function __construct(EntityManagerInterface $entityManager, ORMPurger $purger)
    {
        $this->entityManager = $entityManager;
        $this->purger        = $purger;
    }
    /**
     * {@inheritDoc}
     */
    public function setPurgeMode($mode)
    {
        $this->purger->setPurgeMode($mode);
    }
    /**
     * {@inheritDoc}
     */
    public function getPurgeMode()
    {
        return $this->purger->getPurgeMode();
    }
    /**
     * {@inheritDoc}
     */
    public function setEntityManager(EntityManagerInterface $em)
    {
        $this->purger->setEntityManager($em);
        $this->entityManager = $em;
    }
    /**
     * {@inheritDoc}
     */
    public function getObjectManager()
    {
        return $this->purger->getObjectManager();
    }
    /**
     * {@inheritDoc}
     */
    function purge()
    {
        $connection = $this->entityManager->getConnection();
        try {
            $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 0');
            $this->purger->purge();
        } finally {
            $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 1');
        }
    }
}