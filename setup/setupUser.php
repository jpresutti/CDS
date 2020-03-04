<?php

declare(strict_types=1);

use CDS\DataMappers\UserMapper;
use CDS\DataModels\User;
define('RUNASCLI',true);
require_once (__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'baseline.php');
$userMapper = new UserMapper();
$user = $userMapper->getByUsername('Admin');

if ( $user == null ) {
    $user = new User();
    $user->Username = 'Admin';
    $user->setPassword('Passw0rd');
    $user = (new UserMapper())->save($user);
    echo 'User created.' . PHP_EOL;
} else {
    echo 'User already exists.' . PHP_EOL;
}