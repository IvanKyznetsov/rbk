<?php

namespace App\Scraping\HttpLoader;

/**
 * Class Request
 *
 * @package App\Scraping\HttpLoader
 */
class Request
{

    /**
     * @var string
     */
    private string $url;

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return Request
     */
    public function setUrl(string $url): Request
    {
        $this->url = $url;

        return $this;
    }
}
