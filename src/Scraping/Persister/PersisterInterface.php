<?php

namespace App\Scraping\Persister;

/**
 * Interface PersisterInterface
 *
 * @package App\Scraping\Persister
 */
interface PersisterInterface
{

    /**
     * @param object $object
     *
     * @return void
     */
    public function add(object $object): void;

    /**
     * @return void
     */
    public function flush(): void;

    /**
     * @param string $className
     *
     * @return void
     */
    public function deleteUrls(string $className): void;

    /**
     * @param string $className
     *
     * @return array
     */
    public function getProductUrls(string $className): array;
}