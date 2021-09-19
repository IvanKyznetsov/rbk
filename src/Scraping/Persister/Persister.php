<?php

namespace App\Scraping\Persister;

use App\Entity\ProductInterface;
use App\Entity\RbkUrl;
use App\Entity\UrlInterface;
use App\Scraping\HttpLoader\Request;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class Persister
 *
 * @package App\Scraping\Persister
 */
class Persister implements PersisterInterface
{

    /**
     * @var int
     */
    private int $countPersistObjects = 0;

    /**
     * @var int
     */
    private int $maxPersisObjects = 2;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    /**
     * Persister constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param object $object
     *
     * @return void
     */
    public function add(object $object): void
    {
        $this->em->persist($object);
        $this->countPersistObjects++;
        $this->checkPersistObjects();
    }

    /**
     * @return void
     */
    public function flush(): void
    {
        $this->em->flush();
        $this->maxPersisObjects = 0;
    }

    /**
     * @param string $className
     *
     * @return void
     */
    public function deleteUrls(string $className): void
    {
        $this->em->getRepository($className)
            ->createQueryBuilder('urls')
            ->delete()
            ->getQuery()
            ->execute();
    }

    /**
     * @param string $className
     *
     * @return array
     */
    public function getProductUrls(string $className): array
    {
        $urls = $this->em->getRepository($className)->findAll();

        $requests = [];
        foreach ($urls as $url) {
            $requests[] = (new Request())->setUrl($url->getUrl());
        }

        return $requests;
    }

    /**
     * @return void
     */
    private function checkPersistObjects(): void
    {
        if ($this->countPersistObjects >= $this->maxPersisObjects) {
            $this->flush();
        }
    }
}