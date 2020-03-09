<?php

declare(strict_types=1);

namespace CDS\DataModels;

class User {
    public string $ID;
    public string $PRI;
    public string $Username;
    public string $Password;
    public bool $Active;
    
    /**
     * Set password - if set directly, login will not work
     * @param string $password
     */
    public function setPassword(string $password) : void {
        $this->Password = password_hash($password,PASSWORD_DEFAULT);
    }
    
    /**
     * Check if password is correct
     * @param string $password
     * @return bool
     */
    public function checkPassword(string $password) : bool {
        $valid = password_verify($password,$this->Password);
        return $valid;
    }
}