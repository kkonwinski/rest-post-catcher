<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

interface RestServiceInterface
{
    public function __construct(HttpClientInterface $client);
    public function fetchData(string $data, string $url): array;
}
