<?php

declare(strict_types=1);

namespace CDS\Controllers;

use CDS\DataMappers\CompanyMapper;
use CDS\DataMappers\UserMapper;
use CDS\View;

class CompanyHistory extends BaseController
{
    public function get()
    {
        $companyId = $_GET['company'] ?: null;
        
        if ( $companyId == null ) {
            header('Location:/companies.php');
            exit;
        }
        return $this->getHistory($companyId);
    }
    
    private function getHistory($companyId) {
        $companyMapper = new CompanyMapper();
        $company = $companyMapper->getByPrimary($companyId);
        if ( $company == null ) {
            header('Location:/companies.php');
            exit;
        }
        $history = $companyMapper->GetAuditLog($company);
        View::showView('companyHistory.phtml', ['company' => $company, 'companyHistory' => $history]);
    
    }
}