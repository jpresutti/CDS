<?php

declare(strict_types=1);

namespace CDS\DataMappers;

use CDS\Database;
use CDS\DataModels\Company;
use CDS\DataModels\CompanyHistory;
use CDS\DataModels\User;
use CDS\UserSession;

class CompanyMapper
{
    public function getByPrimary($id) : ?Company
    {
        $connection = Database::getInstance()->getConnection();
        
        $sqlFetch = $connection->prepare('SELECT ID, PRI, CompanyName, Ticker, NickName, Address_1, Address_2, City, State, PostalCode, HomeCountry, MainCountryOfOrigin, Active, Deleted, Archived FROM tbldat_Companies where PRI = ?');
        $sqlFetch->execute([
            $id
        ]);
        
        while ($company = $sqlFetch->fetchObject(Company::class)) {
            return $company;
        }
        
        return null;
    }
    
    /**
     * Get full list of companies
     * @param bool $includeDeleted
     * @return array
     */
    public function getCompanyList(bool $includeDeleted = false) : array
    {
        $return = [];
        $connection = Database::getInstance()->getConnection();
        
        $extra = $includeDeleted ? '' : ' WHERE Deleted = 0';
        $sqlFetch = $connection->prepare('SELECT ID, PRI, CompanyName, Active, Deleted, Archived FROM tbldat_Companies' . $extra . ' ORDER BY CompanyName');
        $sqlFetch->execute();
        
        while ($company = $sqlFetch->fetchObject(Company::class)) {
            $return[] = $company;
        }
        
        return $return;
    }
    
    public function save(Company $company) : Company
    {
        $connection = Database::getInstance()->getConnection();
        if (!isset($company->ID)) {
            $sqlUpsert = $connection->prepare('INSERT INTO tbldat_Companies (ID, CompanyName, Ticker, NickName, Address_1, Address_2, City, State, PostalCode, HomeCountry, MainCountryOfOrigin, Active, Deleted, Archived) VALUES (NEWID(),?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $sqlUpsert->execute([
                $company->CompanyName,
                $company->Ticker,
                $company->NickName,
                $company->Address_1,
                $company->Address_2,
                $company->City,
                $company->State,
                $company->PostalCode,
                $company->HomeCountry,
                $company->MainCountryOfOrigin,
                $company->Active,
                $company->Deleted,
                $company->Archived
            ]) or die(var_dump($connection->errorCode()));
            $company = $this->getByPrimary($connection->lastInsertId());
            
        } else {
            $originalCompany = $this->getByPrimary($company->PRI);
            $sqlUpsert = $connection->prepare('UPDATE tbldat_Companies SET CompanyName = ?,Ticker = ?, NickName = ?, Address_1 = ?, Address_2 = ?, City = ?, State = ?, PostalCode = ?, HomeCountry = ?, MainCountryOfOrigin = ?, Active = ?, Deleted = ?, Archived = ? WHERE ID = ?');
            $sqlUpsert->execute([
                $company->CompanyName,
                $company->Ticker,
                $company->NickName,
                $company->Address_1,
                $company->Address_2,
                $company->City,
                $company->State,
                $company->PostalCode,
                $company->HomeCountry,
                $company->MainCountryOfOrigin,
                $company->Active,
                $company->Deleted,
                $company->Archived,
                $company->ID
            ]);
            $this->AddAuditLog($originalCompany, $company);
        }
        
        return $company;
    }
    
    public function GetAuditLog(Company $company) : array
    {
        $return = [];
        
        $sqlFetch = Database::getInstance()->getConnection()->prepare('
SELECT
    tbldat_CompanyAuditLog.ID,
    tbldat_CompanyAuditLog.PRI,
    tbldat_CompanyAuditLog.Old,
    tbldat_CompanyAuditLog.New,
    tbldat_CompanyAuditLog.Timestamp,
    tbldat_Users.ID as UserID,
    tbldat_Users.PRI as UserPRI,
    tbldat_Users.Username as UserUsername

    
       

FROM tbldat_CompanyAuditLog
LEFT JOIN tbldat_Users ON UserId = tbldat_Users.PRI
WHERE CompanyId = ? ORDER BY tbldat_CompanyAuditLog.PRI desc');
        $sqlFetch->execute([$company->PRI]);
        while ( $row = $sqlFetch->fetchObject()) {
            $record = new CompanyHistory();
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
            
            // Populate company info
            $record->company = $company;
    
            $return[] = $record;
        }
        
        return $return;
        
    }
    
    private function AddAuditLog(Company $newCompany, Company $oldCompany = null)
    {
        $old = new \stdClass();
        $new = new \stdClass();
        $changed = false;
        $oldCompany = $oldCompany != null ? $oldCompany : new Company();
        foreach ($newCompany as $key => $val) {
            if ($val != $oldCompany->$key) {
                $changed = true;
                $new->$key = $val;
                $old->$key = isset($oldCompany->$key) ? $oldCompany->$key : null;
            }
        }
        if ( !$changed ) {
            return;
        }
        $connection = Database::getInstance()->getConnection();
        $sqlAudit = $connection->prepare('INSERT INTO tbldat_CompanyAuditLog (ID, CompanyId, UserId,Old, New) VALUES (NEWID(),?,?,?,?)');
        $sqlAudit->execute([
            $newCompany->PRI,
            UserSession::getInstance()->getUserPri(),
            json_encode($old),
            json_encode($new)
        ]);
    }
}