<?php

namespace App\Example\QueryStream;

use React\EventLoop\Loop;
use React\Mysql\MysqlClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Attribute\Route;
use Fiber;


#[Route('react-fibers-streamed-response')]
/**
 * curl -N http://localhost/query-stream/react-fibers-streamed-response
 */
class ReactFibersStreamedResponseController
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
        foreach ($this->queryStream(Helper::SLOW_QUERY) as $row) {
            echo json_encode($row) . PHP_EOL;
        }

        echo json_encode(['peak_memory_usage' => memory_get_usage() * 0.000001 . 'MB']);
    }

    private function queryStream(string $query): iterable
    {
        // Thanks, Claude!

        $fiber = new Fiber(function (string $query) {
            $stream = $this->mysqlClient->queryStream($query);

            $stream->on('data', function ($row) {
                Fiber::suspend($row);
            });

            $stream->on('end', function () {
                Fiber::suspend(null);
            });

            // if not started explicitly, the loop will start when Symfony finishes,
            // and the memory usage will be calculated first
            Loop::run();
        });

        $row = $fiber->start($query);

        while ($row !== null) {
            yield $row;
            $row = $fiber->resume();
        }
    }
}