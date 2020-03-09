<?php

namespace CDS;

use CDS\DataModels\User;

class UserSession {
    private static $instance;
    
    private User $user;
    /**
     * Get an instance of the config class
     * @return Config
     */
    public static function getInstance() : self {
        if ( static::$instance == null ) {
            static::$instance = new self();
        }
        return static::$instance;
    }
    
    public function setUser(User $user) : void {
        $this->user = $user;
    }
    
    public function getUser() : User {
        return $this->user;
    }
    
    public function getUserPri() : int {
        return $this->user->PRI;
    }
    
    protected function __construct()
    {
    }
}