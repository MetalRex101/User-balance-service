<?php

namespace App\Repositories;

class UserRepository extends BaseRepository
{
    /**
     * @param int $id
     * @return null
     */
    public function getUserById(int $id)
    {
        $sth = $this->connection->prepare('SELECT * FROM users where id = :id');
        $sth->execute([':id' => $id]);

        return $sth->fetchObject() ?: null;
    }
}
