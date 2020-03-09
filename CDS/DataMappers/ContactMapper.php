<?php

declare(strict_types=1);

namespace CDS\DataMappers;

use CDS\Database;
use CDS\DataModels\Company;
use CDS\DataModels\Contact;
use CDS\DataModels\ContactHistory;
use CDS\DataModels\User;
use CDS\UserSession;

class ContactMapper
{
    public function getByPrimary($id) : ?Contact
    {
        $connection = Database::getInstance()->getConnection();
        
        $sqlFetch = $connection->prepare('SELECT ID, PRI, Company_Key, Title, FName, MName, LName, Suffix, Address_1, Address_2, City, State, PostalCode, Website, Email_Primary, Email_2, EMail_3, Email_4, Phone_Primary, Phone_Mobile, Phone_Land, Phone_Fax, TwitterHandle, FaceBookName, Active, Deleted, Archived FROM tbldat_BusinessContacts where PRI = ?');
        $sqlFetch->execute([
            $id
        ]);
        
        while ($contact = $sqlFetch->fetchObject(Contact::class)) {
            return $contact;
        }
        
        return null;
    }
    
    public function getByCompany(Company $company, bool $includeDeleted = false) : array
    {
        $connection = Database::getInstance()->getConnection();
        $extra = $includeDeleted ? '' : ' and Deleted = 0';
    
        $sqlFetch = $connection->prepare('SELECT ID, PRI, Company_Key, Title, FName, MName, LName, Suffix, Address_1, Address_2, City, State, PostalCode, Website, Email_Primary, Email_2, EMail_3, Email_4, Phone_Primary, Phone_Mobile, Phone_Land, Phone_Fax, TwitterHandle, FaceBookName, Active, Deleted, Archived FROM tbldat_BusinessContacts where Company_Key = ?' . $extra . ' ORDER BY LName');
        $sqlFetch->execute([
            $company->PRI
        ]);
        $return = [];
        while ($company = $sqlFetch->fetchObject(Contact::class)) {
            $return[] = $company;
        }
        
        return $return;
    }
    
    public function save(Contact $contact) : Contact
    {
        $connection = Database::getInstance()->getConnection();
        if (!isset($contact->ID)) {
            $sqlUpsert = $connection->prepare('INSERT INTO tbldat_BusinessContacts (ID, Company_Key, Title, FName, MName, LName, Suffix, Address_1, Address_2, City, State, PostalCode, Website, Email_Primary, Email_2, EMail_3, Email_4, Phone_Primary, Phone_Mobile, Phone_Land, Phone_Fax, TwitterHandle, FaceBookName, Active, Deleted, Archived) VALUES (NEWID(),?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $sqlUpsert->execute([
                $contact->Company_Key,
                $contact->Title,
                $contact->FName,
                $contact->MName,
                $contact->LName,
                $contact->Suffix,
                $contact->Address_1,
                $contact->Address_2,
                $contact->City,
                $contact->State,
                $contact->PostalCode,
                $contact->Website,
                $contact->Email_Primary,
                $contact->Email_2,
                $contact->EMail_3,
                $contact->Email_4,
                $contact->Phone_Primary,
                $contact->Phone_Mobile,
                $contact->Phone_Land,
                $contact->Phone_Fax,
                $contact->TwitterHandle,
                $contact->FaceBookName,
                $contact->Active,
                $contact->Deleted,
                $contact->Archived
                
            ]) or die(var_dump($connection->errorCode()));
            $contact = $this->getByPrimary($connection->lastInsertId());
            $this->AddAuditLog( $contact);
    
        } else {
            $originalContact = $this->getByPrimary($contact->PRI);
            $sqlUpsert = $connection->prepare('UPDATE tbldat_BusinessContacts SET Company_Key = ?, Title = ?, FName = ?, MName = ?, LName = ?, Suffix = ?, Address_1 = ?, Address_2 = ?, City = ?, State = ?, PostalCode = ?, Website = ?, Email_Primary = ?, Email_2 = ?, EMail_3 = ?, Email_4 = ?, Phone_Primary = ?, Phone_Mobile = ?, Phone_Land = ?, Phone_Fax = ?, TwitterHandle = ?, FaceBookName = ?, Active = ?, Deleted = ?, Archived = ?  WHERE ID = ?');
            $sqlUpsert->execute([
                $contact->Company_Key,
                $contact->Title,
                $contact->FName,
                $contact->MName,
                $contact->LName,
                $contact->Suffix,
                $contact->Address_1,
                $contact->Address_2,
                $contact->City,
                $contact->State,
                $contact->PostalCode,
                $contact->Website,
                $contact->Email_Primary,
                $contact->Email_2,
                $contact->EMail_3,
                $contact->Email_4,
                $contact->Phone_Primary,
                $contact->Phone_Mobile,
                $contact->Phone_Land,
                $contact->Phone_Fax,
                $contact->TwitterHandle,
                $contact->FaceBookName,
                $contact->Active,
                $contact->Deleted,
                $contact->Archived,
                $contact->ID
            ]);
            $this->AddAuditLog($originalContact, $contact);
        }
        
        return $contact;
    }
    
    private function AddAuditLog(Contact $newContact, Contact $oldContact = null)
    {
        $old = new \stdClass();
        $new = new \stdClass();
        $changed = false;
        $oldContact = $oldContact != null ? $oldContact : new Contact();
        foreach ($newContact as $key => $val) {
            
            if (!isset($oldContact->$key) || $val != $oldContact->$key) {
                $changed = true;
                $new->$key = $val;
                $old->$key = isset($oldContact->$key) ? $oldContact->$key : null;
            }
        }
        if ( !$changed ) {
            return;
        }
        $connection = Database::getInstance()->getConnection();
        $sqlAudit = $connection->prepare('INSERT INTO tbldat_ContactAuditLog (ID, ContactId, UserId,Old, New) VALUES (NEWID(),?,?,?,?)');
        $sqlAudit->execute([
            $newContact->PRI,
            UserSession::getInstance()->getUserPri(),
            json_encode($old),
            json_encode($new)
        ]);
    }
    
    public function GetAuditLog(Contact $contact) : array
    {
        $return = [];
        
        $sqlFetch = Database::getInstance()->getConnection()->prepare('
SELECT
    tbldat_ContactAuditLog.ID,
    tbldat_ContactAuditLog.PRI,
    tbldat_ContactAuditLog.Old,
    tbldat_ContactAuditLog.New,
    tbldat_ContactAuditLog.Timestamp,
    tbldat_Users.ID as UserID,
    tbldat_Users.PRI as UserPRI,
    tbldat_Users.Username as UserUsername

    
    

FROM tbldat_ContactAuditLog
LEFT JOIN tbldat_Users ON UserId = tbldat_Users.PRI
WHERE ContactId = ? ORDER BY tbldat_ContactAuditLog.PRI desc');
        $sqlFetch->execute([$contact->PRI]);
        while ( $row = $sqlFetch->fetchObject()) {
            $record = new ContactHistory();
            $record->ID = $row->ID;
            $record->PRI = $row->PRI;
            $record->New = json_decode($row->New);
            $record->Old = json_decode($row->Old);
            $record->Timestamp = \DateTime::createFromFormat('Y-m-d H:i:s',$row->Timestamp);
            
            // Populate user info
            $record->user = new User();
            $record->user->PRI = $row->UserPRI;
            $record->user->ID = $row->UserID;
            $record->user->Username = $row->UserUsername;
            
            // Populate contact info
            $record->contact = $contact;
            
            $return[] = $record;
        }
        
        return $return;
        
    }
}