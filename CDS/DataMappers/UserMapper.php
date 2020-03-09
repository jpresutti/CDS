<?php

declare(strict_types=1);

namespace CDS\DataMappers;

use CDS\Database;
use CDS\DataModels\User;

class UserMapper
{
    /**
     * Gets user by username if one exists
     * @param string $username
     * @return User|null
     */
    public function getByUsername(string $username) : ?User
    {
        $connection = Database::getInstance()->getConnection();
        
        $sqlFetch = $connection->prepare('SELECT ID,PRI,Username,Password FROM dbo.tbldat_Users where Username = ? and active = 1');
        $sqlFetch->execute([
            $username
        ]);
        
        while ($user = $sqlFetch->fetchObject(User::class)) {
            return $user;
        }
        
        return null;
    }
    
    /**
     * Save user
     * @param User $user
     * @return User
     */
    public function save(User $user) : User
    {
        $connection = Database::getInstance()->getConnection();
        if (!isset($user->ID)) {
            $sqlUpsert = $connection->prepare('INSERT INTO tbldat_Users (ID,Username,Password) VALUES (NEWID(),?,?)');
            $sqlUpsert->execute([
                $user->Username,
                $user->Password
            ]) or die(var_dump($connection->errorCode()));
            $user = $this->getByUsername($user->Username);
            
        } else {
            $sqlUpsert = $connection->prepare('UPDATE tbldat_Users SET Username = ?,Password = ? WHERE ID = ?');
            $sqlUpsert->execute([
                $user->Username,
                $user->Password,
                $user->ID
            ]);
        }
        
        return $user;
    }
}