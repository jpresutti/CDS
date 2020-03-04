<?php

declare(strict_types=1);

namespace CDS\Controllers;

use CDS\DataMappers\UserMapper;
use CDS\View;

class Companies extends BaseController
{
    public function get()
    {
        $companyId = $_GET['company'] ?: null;
        if ( $companyId != null ) {
            return $this->getDetails($companyId);
        }
        
    }
    
    private function getDetails($companyId) {
    
    }
}