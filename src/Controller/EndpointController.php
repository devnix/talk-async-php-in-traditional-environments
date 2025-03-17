<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/endpoint')]
class EndpointController
{
    public function __invoke(): JsonResponse
    {
        sleep(1);

        return new JsonResponse(['status' => 'ok']);
    }
}