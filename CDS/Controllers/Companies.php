<?php

declare(strict_types=1);

namespace CDS\Controllers;

use CDS\DataMappers\CompanyMapper;
use CDS\View;

class Companies extends BaseController
{
    public function get()
    {
        $companyId = $_GET['company'] ?? null;
        $includeDeleted = !empty($_GET['deleted']);
        if ( $companyId != null ) {
            return $this->getDetails($companyId);
        }
        $companyMapper = new CompanyMapper();
        $companyList = $companyMapper->getCompanyList($includeDeleted);
        View::showView('companies.phtml',['companies' => $companyList]);
    
    }
    
    private function getDetails($companyId) {
        $companyMapper = new CompanyMapper();
        $company = $companyMapper->getByPrimary($companyId);
        if ( $company == null ) {
            header('Location:/company.php');
            exit;
        }
        View::showView('companyDetail.phtml', ['company' => $company]);
    
    }
}