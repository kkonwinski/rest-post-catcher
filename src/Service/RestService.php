<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;


class RestService implements RestServiceInterface
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Get data from rest url
     * @param string $data
     * @return array
     */
    public function fetchData(string $data, string $url): array
    {
        $response = $this->client->request(
            'GET',
            $url . "/" . $data
        );

        return $response->toArray();
    }
}