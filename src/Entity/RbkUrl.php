<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class RbkUrl
 *
 * @ORM\Entity
 *
 * @package App\Entity
 */
class RbkUrl implements UrlInterface
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @var string
     *
     * @ORM\Column
     */
    private string $url;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

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
     * @return RbkUrl
     */
    public function setUrl(string $url): RbkUrl
    {
        $this->url = $url;

        return $this;
    }
}
