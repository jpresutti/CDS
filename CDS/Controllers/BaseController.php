<?php

declare(strict_types=1);

namespace CDS\Controllers;

abstract class BaseController {
    public function get() {
        throw new \Exception('Get not implemented');
    }
    
    public function post() {
        throw new \Exception('Post not implemented');
    }
    public function run()
    {
        switch ($_SERVER['REQUEST_METHOD'] ) {
            case 'GET':
                static::get();
            break;
            case 'POST':
                static::post();
            break;
            default:
                throw new \Exception($_SERVER['REQUEST_METHOD'] . ' not implemented');
        }
    }
}