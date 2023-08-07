<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class RbkProduct
 *
 * @ORM\Entity
 *
 * @package App\Entity
 */
class RbkProduct implements ProductInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column
     */
    private \DateTime $time;

    /**
     * @var string
     *
     * @ORM\Column
     */
    private string $title;

    /**
     * @var string
     *
     * @ORM\Column(length=30000)
     */
    private string $body;

    /**
     * @var string
     *
     * @ORM\Column
     */
    private string $url;

    /**
     * @var string
     *
     * @ORM\Column
     */
    private string $photo;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getTime(): \DateTime
    {
        return $this->time;
    }

    /**
     * @param \DateTime $time
     *
     * @return RbkProduct
     */
    public function setTime(\DateTime $time): RbkProduct
    {
        $this->time = $time;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return RbkProduct
     */
    public function setTitle(string $title): RbkProduct
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param int $slice
     *
     * @return string
     */
    public function getSliceBody(int $slice = 200): string
    {
        return mb_strimwidth($this->body, 0, 200, '...');
    }

    /**
     * @param string $body
     *
     * @return RbkProduct
     */
    public function setBody(string $body): RbkProduct
    {
        $this->body = $body;

        return $this;
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
     * @return RbkProduct
     */
    public function setUrl(string $url): RbkProduct
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhoto(): string
    {
        return $this->photo;
    }

    /**
     * @param string $photo
     *
     * @return RbkProduct
     */
    public function setPhoto(string $photo): RbkProduct
    {
        $this->photo = $photo;

        return $this;
    }
}
