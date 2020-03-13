<?php

declare(strict_types=1);

namespace CDS\Controllers;

use CDS\DataMappers\CompanyMapper;
use CDS\DataMappers\ContactMapper;
use CDS\DataModels\Company;
use CDS\DataModels\Contact;
use CDS\View;

class Contacts extends BaseController
{
    public function get()
    {
        $companyId = $_GET['company'];
        $contactId = $_GET['contact'] ?? null;
        $includeDeleted = !empty($_GET['deleted']);
        
        $companyMapper = new CompanyMapper();
        $company = $companyMapper->getByPrimary($companyId);
        if ($company == null) {
            header('Location:/companies.php');
            exit;
        }
        if ($contactId != null) {
            return $this->getDetails($company, $contactId);
        }
        $contactMapper = new ContactMapper();
        $contactList = $contactMapper->getByCompany($company, $includeDeleted);
        
        View::showView('contactList.phtml', ['company' => $company, 'contacts' => $contactList]);
        
    }
    
    private function getDetails(Company $company, $contactId)
    {
        
        $contact = (new ContactMapper())->getByPrimary($contactId);
        if ($contact == null || $contact->Company_Key != $company->PRI) {
            header('Location:/companies.php');
            exit;
        }
        View::showView('contactDetail.phtml', ['company' => $company, 'contact' => $contact]);
        
    }
}