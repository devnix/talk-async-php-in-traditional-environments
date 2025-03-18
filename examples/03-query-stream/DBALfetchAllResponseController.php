<?php

namespace App\Example\QueryStream;

use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedJsonResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('dbal-fetch-all-response')]
/**
 * curl -N http://localhost/query-stream/dbal-fetch-all-response
 */
class DBALfetchAllResponseController
{
    public function __construct(
        private Connection $connection,
    )
    {
    }

    public function __invoke(): Response
    {
        return new StreamedResponse($this->stream(...));
    }

    private function stream(): void
    {
        $this->connection->getConfiguration()->setSQLLogger(null);

        $rows = $this->connection->executeQuery(Helper::SLOW_QUERY);

        foreach ($rows->fetchAllAssociative() as $row) {
            echo json_encode($row).PHP_EOL;
        }

        echo json_encode(['peak_memory_usage' => Helper::getMemoryUsage()]);
    }
}