<?php

namespace App\Services;

class Config
{
    /**
     * @return array
     */
    public function getPDOConnection(): array
    {
        $adapter = 'mysql';
        $user = '';
        $pass = '';
        $host = '';
        $db_name = '';
        $env = getenv('APP_ENV');

        switch ($env) {
            case 'develop':
                $user = getenv('DB_USER');
                $pass = getenv('DB_PASS');
                $host = getenv('DB_HOST');
                $db_name = getenv('DB_NAME');

                break;
        }

        $dsn = sprintf('%s:dbname=%s;host=%s', $adapter, $db_name, $host);

        return compact('dsn', 'user', 'pass');
    }
}
