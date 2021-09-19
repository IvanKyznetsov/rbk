<?php

namespace App\Command;

use App\Scraping\HttpLoader\HttpLoaderInterface;
use App\Scraping\HttpLoader\Request;
use App\Scraping\Persister\PersisterInterface;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Abstract class AbstractScraperCommand
 *
 * @package App\Command
 */
abstract class AbstractScraperCommand extends Command
{

    protected const RUN_MODE_PREPARSE_URLS     = 1;
    protected const RUN_MODE_PREPARSE_PRODUCTS = 2;

    /**
     * @var string
     */
    protected string $bodyResponse;

    /**
     * @var Request
     */
    protected Request $request;

    /**
     * @var \DateTime
     */
    protected \DateTime $time;

    /**
     * @var int
     */
    private int $runMode;

    /**
     * @var HttpLoaderInterface
     */
    private HttpLoaderInterface $httpLoader;

    /**
     * @var PersisterInterface
     */
    private PersisterInterface $persister;

    /**
     * AbstractScraperCommand constructor.
     *
     * @param HttpLoaderInterface $httpLoader
     * @param PersisterInterface  $persister
     * @param string|null         $name
     */
    public function __construct(
        HttpLoaderInterface $httpLoader,
        PersisterInterface $persister,
        string $name = null
    ) {
        if (!isset($this->siteUrl)) {
            throw new \LogicException(sprintf('%s must have a $siteUrl property', static::class));
        }

        $this->httpLoader = $httpLoader;
        $this->persister  = $persister;

        parent::__construct($name);
    }

    /**
     * @param Response $response
     * @param Request  $request
     *
     * @return void
     */
    public function callbackLoadResponse(Response $response, Request $request): void
    {
        $this->bodyResponse = $response->getBody()->getContents();
        $this->request      = $request;

        if ($this->getRunMode() === self::RUN_MODE_PREPARSE_URLS) {
            $this->parseUrls();
        }

        if ($this->getRunMode() === self::RUN_MODE_PREPARSE_PRODUCTS) {
            $this->parseProductPage();
        }
    }

    /**
     * Parse product urls
     */
    abstract protected function parseUrls(): void;

    /**
     * Parse product details
     */
    abstract protected function parseProductPage(): void;

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->addArgument('runMode', InputArgument::REQUIRED, 'Set run mode, 1|2.');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->setArguments($input);
        $this->getHttpLoader()->init($this->getLoaderCallbacks(), $this->getLoaderSettings());
        $this->time = new \DateTime();

        switch ($this->getRunMode()) {
            case self::RUN_MODE_PREPARSE_URLS:
                $this->runModePrepareUrls();
                break;
            case self::RUN_MODE_PREPARSE_PRODUCTS:
                $this->runModePrepareProductPage();
                break;
        }

        $this->getPersister()->flush();

        if ($this->getRunMode() === self::RUN_MODE_PREPARSE_PRODUCTS) {
            $this->deleteUrls();
        }

        return Command::SUCCESS;
    }

    /**
     * @param InputInterface $input
     */
    protected function setArguments(InputInterface $input): void
    {
        if (
            $input->hasArgument('runMode')
            && in_array(
                (int) $input->getArgument('runMode'),
                [self::RUN_MODE_PREPARSE_URLS, self::RUN_MODE_PREPARSE_PRODUCTS]
            )
        ) {
            $this->runMode = (int) $input->getArgument('runMode');
        } else {
            throw new \LogicException(
                sprintf('%s command must have a runMode argument 1|2', static::class)
            );
        }
    }

    /**
     * @return void
     */
    protected function runModePrepareUrls(): void
    {
        $this->getHttpLoader()->sendRequest(
            (new Request())->setUrl($this->formatListingPageUrl())
        );
    }

    /**
     * @return void
     */
    protected function runModePrepareProductPage(): void
    {
        $requests = $this->getPersister()->getProductUrls($this->getUrlClassName());

        foreach ($requests as $request) {
            $this->getHttpLoader()->sendRequest($request);
        }
    }

    /**
     * @return string
     */
    protected function formatListingPageUrl(): string
    {
        return $this->siteUrl;
    }

    /**
     * @return array
     */
    protected function getLoaderCallbacks(): array
    {
        return [
            'callbackLoadResponse' => [$this, 'callbackLoadResponse'],
        ];
    }

    /**
     * @return array
     */
    protected function getLoaderSettings(): array
    {
        return [
            'request_timeout' => 2,
        ];
    }

    /**
     * @param string $html
     *
     * @return Crawler
     */
    protected function getCrawler(string $html): Crawler
    {
        return new Crawler($html);
    }

    /**
     * @return int
     */
    protected function getRunMode(): int
    {
        return $this->runMode;
    }

    /**
     * @return HttpLoaderInterface
     */
    protected function getHttpLoader(): HttpLoaderInterface
    {
        return $this->httpLoader;
    }

    /**
     * @return PersisterInterface
     */
    protected function getPersister(): PersisterInterface
    {
        return $this->persister;
    }

    /**
     * @param string|null $className
     *
     * @return void
     */
    protected function deleteUrls(string $className = null): void
    {
        $className = $className ?? $this->getUrlClassName();
        $this->getPersister()->deleteUrls($className);
    }

    /**
     * @return string
     */
    protected function getUrlClassName(): string
    {
        return str_replace(
            ['ScraperCommand', 'Command'],
            ['Url', 'Entity'],
            static::class
        );
    }
}
