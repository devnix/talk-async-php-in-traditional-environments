<?php

declare(strict_types=1);

namespace App\Example\ExternalServices;

use React\Http\Browser;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Stopwatch\Stopwatch;
use function React\Async\await;
use function React\Promise\all;

#[Route('/react')]
class ReactRequestsController
{
    public function __construct(
        private Stopwatch $stopwatch,
    )
    {
    }

    public function __invoke(): Response
    {
        $browser = new Browser();

        $this->stopwatch->start('Request 1');
        $responses[] = $browser->get('http://localhost/endpoint'); // async
        $this->stopwatch->stop('Request 1');

        $this->stopwatch->start('Request 2');
        $responses[] = $browser->get('http://localhost/endpoint'); // async
        $this->stopwatch->stop('Request 2');

        $this->stopwatch->start('Request 3');
        $responses[] = $browser->get('http://localhost/endpoint'); // async
        $this->stopwatch->stop('Request 3');

        $responses = all($responses); // wraps the three promises into one, that resolves when all the promises are resolved
        $responses = await($responses); // blocks and unwraps the promise containing the three responses

        foreach ($responses as $response) {
            dump($response);
        }

        return new Response('<html><body></body></html>');
    }
}
