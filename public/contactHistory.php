<?php

declare(strict_types=1);

use CDS\Controllers\ContactHistory;

define('PRIVATE',true);
require_once (__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'baseline.php');
(new ContactHistory())->run();