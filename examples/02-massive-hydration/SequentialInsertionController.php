<?php

namespace App\Example\MassiveHydration;

use Amp\Pipeline\Pipeline;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/sequential-insertion')]
class SequentialInsertionController
{
    public function __construct(
        private CustomerRepository $customerRepository,
    )
    {
    }

    public function __invoke(Request $request): Response
    {
        $amount = $request->query->getInt('amount');

        foreach (range(1, $amount) as $i) {
            $customer = new Customer(
                'Foo',
                'foo@example.com',
                '123456789',
                'Foo Street 123',
            );

            $this->customerRepository->save($customer);
        }

        return new Response('<html><body></body></html>');
    }
}