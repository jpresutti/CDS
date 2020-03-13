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

    /**
     * Set the user into the object
     * @param User $user
     */
    public function setUser(User $user) : void {
        $this->user = $user;
    }

    /**
     * get the user
     * @return User
     */
    public function getUser() : User {
        return $this->user;
    }

    /**
     * Get user's primary key or null if no user
     * @return int|null
     */
    public function getUserPri() : ?int {
        return isset($this->user) ? $this->user->PRI : null;
    }

    /**
     * UserSession constructor. - Empty constructor protected to prevent outside instantiation
     */
    protected function __construct()
    {
    }
}