<?php

namespace App\Scraping\HttpLoader;

/**
 * Class AbstractHttpLoader
 *
 * @package App\Scraping\HttpLoader
 */
abstract class AbstractHttpLoader implements HttpLoaderInterface
{

    /**
     * @var callable
     */
    protected $callbackLoadResponse;

    /**
     * @var array
     */
    protected array $settings = [
        'request_timeout' => 2,
    ];

    /**
     * @param Request $request
     */
    abstract public function sendRequest(Request $request): void;

    /**
     * @param array $callbacks
     * @param array $settings
     *
     * @return void
     */
    public function init(array $callbacks, array $settings): void
    {
        $this->setCallbacks($callbacks);
        $this->setSettings($settings);
    }

    /**
     * @param array $callbacks
     *
     * @return void
     */
    private function setCallbacks(array $callbacks): void
    {
        $this->callbackLoadResponse = $callbacks['callbackLoadResponse'] ?? null;
    }

    /**
     * @param array $settings
     */
    private function setSettings(array $settings): void
    {
        $this->settings['headers']         = $settings['headers'] ?? [];
        $this->settings['request_timeout'] = $settings['request_timeout'] ?? $this->settings['request_timeout'];
    }
}
