<?php

declare(strict_types=1);

namespace CDS\DataModels;

class User {
    public string $ID;
    public string $PRI;
    public string $Username;
    public string $Password;
    public bool $Active;
    
    public function setPassword(string $password) : void {
        $this->Password = password_hash($password,PASSWORD_DEFAULT);
    }
    
    public function checkPassword(string $password) : bool {
        $valid = password_verify($password,$this->Password);
        return $valid;
    }
}