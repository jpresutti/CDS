<?php

declare(strict_types=1);

use CDS\Controllers\CompanyHistory;

define('PRIVATE',true);
require_once (__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'baseline.php');
(new CompanyHistory())->run();