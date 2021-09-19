<?php

namespace App\Scraping\HttpLoader;

use GuzzleHttp\Client;

/**
 * Class GuzzleHttpLoader
 *
 * @package App\Scraping\HttpLoader
 */
class GuzzleHttpLoader extends AbstractHttpLoader
{

    /**
     * {@inheritdoc}
     */
    public function sendRequest(Request $request): void
    {
        $response = (new Client())
            ->get(
                $request->getUrl(),
                [
                    'headers' => $this->settings['headers'],
                    'timeout' => $this->settings['request_timeout']
                ]
            );

        if (!is_callable($this->callbackLoadResponse)) {
            throw new \InvalidArgumentException(
                \sprintf('Invalid value for callback: expected "callable", "%s" given.', \get_debug_type($this->callbackLoadResponse))
            );
        }
        call_user_func_array($this->callbackLoadResponse, [$response, $request]);
    }
}
