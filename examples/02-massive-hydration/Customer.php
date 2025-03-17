<?php

namespace App\Example\MassiveHydration;

final class Customer
{
    public function __construct(
        private(set) string $name,
        private(set) string $email,
        private(set) string $phone,
        private(set) string $address,
    )
    {
    }
}