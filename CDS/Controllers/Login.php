<?php

declare(strict_types=1);

namespace CDS\Controllers;

use CDS\DataMappers\UserMapper;
use CDS\View;

class Login extends BaseController
{
    public function get()
    {
        $path = 'login.phtml';
        View::showView($path);
    }
    
    public function post()
    {
        $arguments = [];
        /** @var string $username */
        $username = $_POST['username'] ?: null;
        /** @var string $password */
        $password = $_POST['password'] ?: null;
        if ($username === null || $password === null) {
            $arguments['loginFailed'] = true;
        } else {
            $user = (new UserMapper())->getByUsername($username);
            if ($user == null || $user->checkPassword($password) == false) {
                $arguments['loginFailed'] = true;
            } else {
                $_SESSION['username'] = $user->Username;
                header('Location:/companies.php');
                return;
            }
            
        }
        
        View::showView('login.phtml', $arguments);
    }
    
    
}