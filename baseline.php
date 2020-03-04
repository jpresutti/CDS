<?php
declare(strict_types=1);

use CDS\Autoload;
use CDS\Config;
const PROJECT_ROOT = __DIR__ . DIRECTORY_SEPARATOR;

// Loads autoloader class
require_once PROJECT_ROOT . 'CDS' . DIRECTORY_SEPARATOR . 'Autoload.php';
Autoload::getInstance();

// Load configuration
$config = Config::getInstance();

