<?php
declare(strict_types=1);

use CDS\Autoload;
use CDS\Config;
use CDS\DataMappers\UserMapper;
const PROJECT_ROOT = __DIR__ . DIRECTORY_SEPARATOR;

// Loads autoloader class
require_once PROJECT_ROOT . 'CDS' . DIRECTORY_SEPARATOR . 'Autoload.php';
Autoload::getInstance();

// Load configuration
$config = Config::getInstance();

session_start();
$user = null;
if ( !defined('RUNASCLI') && isset($_SESSION['username'])) {
    $user = (new UserMapper())->getByUsername($_SESSION['username']);
}
if ( defined('PRIVATE') && $user == null ) {
    header('Location:/');
    exit;
}
