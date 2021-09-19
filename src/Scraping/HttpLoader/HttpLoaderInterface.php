<?php

namespace App\Scraping\HttpLoader;

/**
 * Interface HttpLoaderInterface
 *
 * @package App\Scraping\HttpLoader
 */
interface HttpLoaderInterface
{

    /**
     * @param Request $request
     */
    public function sendRequest(Request $request): void;
}
