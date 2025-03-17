<?php

namespace App\Example\MassiveHydration;

use Amp\Pipeline\Pipeline;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/parallel-insertion')]
class ParallelInsertionController
{
    public function __construct(
        private CustomerRepository $customerRepository,
    )
    {
    }

    public function __invoke(Request $request): Response
    {
        $amount = $request->query->getInt('amount');
        $concurrency = $request->query->getInt('concurrency');

        assert($amount > 0);
        assert($concurrency > 0);

        Pipeline::fromIterable(range(1, $amount))
            ->concurrent($concurrency)
            ->map(function () {
                $customer = new Customer(
                    'Foo',
                    'foo@example.com',
                    '123456789',
                    'Foo Street 123',
                );

                $this->customerRepository->save($customer);
            })
            ->toArray();

        return new Response('<html><body></body></html>');
    }
}