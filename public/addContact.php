<?php

declare(strict_types=1);

use CDS\Controllers\AddContact;

define('PRIVATE',true);
require_once (__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'baseline.php');
(new AddContact())->run();