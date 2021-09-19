<?php

namespace App\Command;

use App\Entity\RbkProduct;
use App\Entity\RbkUrl;
use http\Exception\InvalidArgumentException;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class RbkScraperCommand
 *
 * @package App\Command
 */
class RbkScraperCommand extends AbstractScraperCommand
{

    /**
     * {@inheritdoc}
     */
    protected static $defaultName = 'rbkScraper';

    /**
     * @var string
     */
    protected string $siteUrl = 'https://www.rbc.ru/v10/ajax/get-news-feed/project/rbcnews/lastDate/{time}/limit/15';

    /**
     * {@inheritdoc}
     */
    protected function getLoaderSettings(): array
    {
        $settings = parent::getLoaderSettings();

        $settings['headers'] = [
            'accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'accept-encoding' => 'gzip, deflate, br',
            'user-agent'      => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/93.0.4577.63 Safari/537.36',
        ];

        return $settings;
    }

    /**
     * {@inheritdoc}
     */
    protected function parseUrls(): void
    {
        $data = json_decode($this->bodyResponse, true);

        foreach ($data['items'] ?? [] as $item) {
            $crawler = $this->getCrawler($item['html'] ?? '');
            $href    = $crawler->filterXPath('//a[contains(@class, "js-news-feed-item")]')->attr('href');

            if ($href === null) {
                throw new InvalidArgumentException(
                    sprintf('Invalid xpath, scraper: %s, url: %s', self::class, $this->request->getUrl())
                );
            }

            $this->getPersister()->add($this->createUrlEntity($href));
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function parseProductPage(): void
    {
        $crawler = $this->getCrawler($this->bodyResponse);

        $this->getPersister()->add($this->createProductEntity($crawler));
    }

    /**
     * {@inheritdoc}
     */
    protected function formatListingPageUrl(): string
    {
        return str_replace('{time}', time(), $this->siteUrl);
    }

    /**
     * @param string $href
     *
     * @return RbkUrl
     */
    private function createUrlEntity(string $href): RbkUrl
    {
        return (new RbkUrl())->setUrl($href);
    }

    /**
     * @param Crawler $crawler
     *
     * @return RbkProduct
     */
    private function createProductEntity(Crawler $crawler): RbkProduct
    {
        return (new RbkProduct())
            ->setTitle($this->parseTitle($crawler))
            ->setBody($this->parseBody($crawler))
            ->setUrl($this->request->getUrl())
            ->setPhoto($this->parsePhoto($crawler))
            ->setTime($this->time);
    }

    /**
     * @param Crawler $crawler
     *
     * @return string
     */
    private function parseTitle(Crawler $crawler): string
    {
        $titleNode = $crawler->filterXPath('//h1[contains(@class, "article__header__title-in")]');
        if ($titleNode->count() === 0) {
            return '';
        }

        return $titleNode->first()->text();
    }

    /**
     * @param Crawler $crawler
     *
     * @return string
     */
    private function parseBody(Crawler $crawler): string
    {
        $bodyNodes = $crawler->filterXPath('//div[contains(@class, "article__content")]//p');
        $body      = '';
        foreach ($bodyNodes as $node) {
            $body .= $body === '' ? $node->textContent : ' '.$node->textContent;
        }

        return $body;
    }

    /**
     * @param Crawler $crawler
     *
     * @return string
     */
    private function parsePhoto(Crawler $crawler): string
    {
        $photoNode = $crawler->filterXPath('//div[@class="article__main-image__wrap"]//img');
        if ($photoNode->count() === 0) {
            return '';
        }

        return $photoNode->first()->attr('src');
    }
}
