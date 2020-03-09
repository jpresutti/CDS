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
    echo 'User Admin created.' . PHP_EOL;
} else {
    echo 'User Admin already exists.' . PHP_EOL;
}

$user = $userMapper->getByUsername('Jeremy');

if ( $user == null ) {
    $user = new User();
    $user->Username = 'Jeremy';
    $user->setPassword('passw0rd');
    $user = (new UserMapper())->save($user);
    echo 'User Jeremy created.' . PHP_EOL;
} else {
    echo 'User Jeremy already exists.' . PHP_EOL;
}