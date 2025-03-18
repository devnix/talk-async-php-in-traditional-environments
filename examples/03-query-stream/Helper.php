<?php

namespace App\Example\QueryStream;

class Helper
{
    public const string SLOW_QUERY = 'SELECT *, (SELECT SLEEP(0.00005)) FROM customers LIMIT 100000';
}