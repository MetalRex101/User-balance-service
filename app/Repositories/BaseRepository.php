<?php

namespace App\Repositories;

use PDO;

class BaseRepository
{
    /**
     * @var PDO
     */
    protected $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }
}
