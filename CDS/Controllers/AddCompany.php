<?php

declare(strict_types=1);

namespace CDS\Controllers;

use CDS\DataMappers\CompanyMapper;
use CDS\DataModels\Company;
use CDS\View;

class AddCompany extends BaseController
{
    public function get()
    {
        
        View::showView('addCompany.phtml',);
        
    }
    
    public function post()
    {
        $company = new Company();
        $company->Archived = isset($_POST['archived']);
        $company->Active = isset($_POST['active']);
        $company->Deleted = isset($_POST['deleted']);
        $company->CompanyName = $_POST['name'];
        $company->MainCountryOfOrigin = $_POST['origincountry'];
        $company->HomeCountry = $_POST['homecountry'];
        $company->PostalCode = $_POST['postal'];
        $company->Address_2 = $_POST['address2'];
        $company->Address_1 = $_POST['address1'];
        $company->NickName = $_POST['nickname'];
        $company->Ticker = $_POST['ticker'];
        $company->City = $_POST['city'];
        $company->State = $_POST['state'];
        $company = (new CompanyMapper())->save($company);
        header('Location:/companies.php?company=' . $company->PRI);
        exit;
    }
    
}