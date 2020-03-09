<?php

declare(strict_types=1);

namespace CDS\Controllers;

use CDS\DataMappers\CompanyMapper;
use CDS\DataMappers\ContactMapper;
use CDS\DataMappers\UserMapper;
use CDS\DataModels\Contact;
use CDS\View;

class EditContact extends BaseController
{
    public function get()
    {
        $company = (new CompanyMapper())->getByPrimary($_GET['company']);
        $contact = (new ContactMapper())->getByPrimary($_GET['contact']);
        View::showView('editContact.phtml',['company' => $company, 'contact' => $contact]);
        
    }
    
    public function post()
    {
    
        $contact = new Contact();
        $contact->PRI = $_POST['PRI'];
        $contact->ID = $_POST['ID'];
        
        $contact->Archived = isset($_POST['archived']);
        $contact->Active = isset($_POST['active']);
        $contact->Deleted = isset($_POST['deleted']);
        $contact->Company_Key = $_POST['companyPri'];
    
        $contact->Title = $_POST['title'];
        $contact->FName = $_POST['fname'];
        $contact->MName = $_POST['mname'];
        $contact->LName = $_POST['lname'];
        $contact->Suffix = $_POST['suffix'];
        $contact->Address_1 = $_POST['address1'];
        $contact->Address_2 = $_POST['address2'];
        $contact->City = $_POST['city'];
        $contact->State = $_POST['state'];
        $contact->PostalCode = $_POST['postal'];
        $contact->Website = $_POST['website'];
        $contact->Email_Primary = $_POST['primaryemail'];
        $contact->Email_2 = $_POST['email2'];
        $contact->EMail_3 = $_POST['email3'];
        $contact->Email_4 = $_POST['email4'];
        $contact->Phone_Primary = $_POST['primaryphone'];
        $contact->Phone_Mobile = $_POST['mobilephone'];
        $contact->Phone_Land = $_POST['landlinephone'];
        $contact->Phone_Fax = $_POST['fax'];
        $contact->TwitterHandle = $_POST['twitter'];
        $contact->FaceBookName = $_POST['facebook'];
    
        $contact = (new ContactMapper())->save($contact);
        header('Location:/contacts.php?company=' . $_POST['companyPri'] . '&contact='. $contact->PRI);
        exit;
    }
    
}