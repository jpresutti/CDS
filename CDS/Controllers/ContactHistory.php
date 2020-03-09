<?php

declare(strict_types=1);

namespace CDS\Controllers;

use CDS\DataMappers\CompanyMapper;
use CDS\DataMappers\ContactMapper;
use CDS\DataMappers\UserMapper;
use CDS\View;

class ContactHistory extends BaseController
{
    public function get()
    {
        $companyId = $_GET['company'] ?: null;
        $contactId = $_GET['contact'] ?: null;
    
        if ( $companyId == null ) {
            header('Location:/companies.php');
            exit;
        }
        if ( $contactId == null ) {
            header('Location:/companies.php?company=' . $companyId);
            exit;
        }
        return $this->getHistory($companyId,$contactId);
    }
    
    private function getHistory($companyId,$contactId) {
        $companyMapper = new CompanyMapper();
        $company = $companyMapper->getByPrimary($companyId);
        $contactMapper = new ContactMapper();
        $contact = $contactMapper->getByPrimary($contactId);
        if ( $company == null || $contact == null || $contact->Company_Key != $companyId) {
            header('Location:/companies.php');
            exit;
        }
        $history = $contactMapper->GetAuditLog($contact);
        View::showView('contactHistory.phtml', ['company' => $company, 'contact' => $contact, 'contactHistory' => $history]);
    
    }
}