<?php

namespace App\Example\QueryStream;

use App\ReactStreamToGenerator;
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
        $stream = $this->mysqlClient->queryStream(Helper::SLOW_QUERY);

        $data = new ReactStreamToGenerator($stream);

        foreach ($data->getGenerator() as $row) {
            echo json_encode($row) . PHP_EOL;
        }

        echo json_encode(['peak_memory_usage' => Helper::getMemoryUsage()]);
    }
}