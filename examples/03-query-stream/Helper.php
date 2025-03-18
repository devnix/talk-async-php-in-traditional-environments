<?php

namespace App\Example\QueryStream;

class Helper
{
    public const string SLOW_QUERY = 'SELECT *, (SELECT SLEEP(0.00005)) FROM customers LIMIT 100000';

    public static function getMemoryUsage(): string
    {
        return memory_get_usage() * 0.000001.'MB';
    }
}