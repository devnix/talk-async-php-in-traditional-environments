<?php

namespace App\Example\ExternalServices;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('async')]
class AsynchronousRequestsController
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private Stopwatch $stopwatch,
    )
    {
    }

    public function __invoke(): Response
    {
        // See: https://symfony.com/doc/current/http_client.html#performance

        $responses = [];

        $this->stopwatch->start('Request 1');
        $responses[] = $this->httpClient->request('GET', 'https://httpbin.org/get'); // async
        $this->stopwatch->stop('Request 1');

        $this->stopwatch->start('Request 2');
        $responses[] = $this->httpClient->request('GET', 'https://httpbin.org/get'); // async
        $this->stopwatch->stop('Request 2');

        $this->stopwatch->start('Request 3');
        $responses[] = $this->httpClient->request('GET', 'https://httpbin.org/get'); // async
        $this->stopwatch->stop('Request 3');

        foreach ($responses as $response) {
            dump($response->getContent()); // blocking (resolved)
        }

        return new Response('<html><body></body></html>');
    }
}