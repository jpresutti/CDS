<?php

declare(strict_types=1);

use CDS\Controllers\Login;

require_once (__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'baseline.php');

(new Login())->run();