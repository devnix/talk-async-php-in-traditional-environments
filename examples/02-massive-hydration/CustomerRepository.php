<?php

namespace App\Example\MassiveHydration;

use Amp\Mysql\MysqlConnectionPool;
use Doctrine\DBAL\Connection;

class CustomerRepository
{
    public function __construct(private MysqlConnectionPool $mysqlConnectionPool)
    {
    }

    public function save(Customer $customer): void
    {
        $this->mysqlConnectionPool->execute(<<<SQL
            INSERT INTO customers (name, email, phone, address) VALUES (:name, :email, :phone, :address)
            SQL,
            (array)$customer,
        );
    }
}