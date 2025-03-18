<?php

namespace App\Example\QueryStream;

use React\EventLoop\Loop;
use React\Mysql\MysqlClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('react-streamed-response')]
/**
 * curl -N http://localhost/query-stream/react-streamed-response
 */
class ReactStreamedResponseController
{
    public function __construct(
        private MysqlClient $mysqlClient,
    )
    {
    }

    public function __invoke(): Response
    {
        return new StreamedResponse($this->stream(...));
    }

    private function stream(): void
    {
        $stream = $this->mysqlClient->queryStream(Helper::SLOW_QUERY);

        $stream->on('data', function ($row) {
            echo json_encode($row).PHP_EOL;
        });

        // if not started explicitly, the loop will start when symfony finishes,
        // and the memory usage will be calculated first
        Loop::run();

        echo json_encode(['peak_memory_usage' => memory_get_usage() * 0.000001.'MB']);
    }
}
