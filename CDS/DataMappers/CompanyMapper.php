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
    /**
     * Get Company by primary key
     * @param $id
     * @return Company|null
     */
    public function getByPrimary($id) : ?Company
    {
        $connection = Database::getInstance()->getConnection();
        
        $sqlFetch = $connection->prepare('EXEC GetCompanyByPri @PRI = ?');
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
        
        $statement = $includeDeleted ? 'EXEC GetAllCompaniesWithDeleted' :'EXEC GetAllCompaniesWithoutDeleted';
        $sqlFetch = $connection->prepare($statement);
        $sqlFetch->execute();
        
        while ($company = $sqlFetch->fetchObject(Company::class)) {
            $return[] = $company;
        }
        
        return $return;
    }
    
    /**
     * Save a company - performs insert or update depending on if primary key is present
     * @param Company $company
     * @return Company
     */
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
            $this->addAuditLog( $company);
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
            $this->addAuditLog($originalCompany, $company);
        }
        
        return $company;
    }
    
    /**
     * Get company's audit log
     * @param Company $company
     * @return array
     */
    public function getAuditLog(Company $company) : array
    {
        $return = [];
        
        $sqlFetch = Database::getInstance()->getConnection()->prepare('
Exec GetCompanyAuditLog @Company=?');
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
    
    /**
     * Add audit log - performed on all saves
     * @param Company $newCompany
     * @param Company|null $oldCompany
     */
    private function addAuditLog(Company $newCompany, Company $oldCompany = null)
    {
        $old = new \stdClass();
        $new = new \stdClass();
        $changed = false;
        $oldCompany = $oldCompany != null ? $oldCompany : new Company();
        
        // Loop through all values of the new company and compare to old
        foreach ($newCompany as $key => $val) {
            if (!isset($oldCompany->$key) || $val != $oldCompany->$key) {
                $changed = true;
                $new->$key = $val;
                $old->$key = isset($oldCompany->$key) ? $oldCompany->$key : null;
            }
        }
        if ( !$changed ) {
            return;
        }
        // Save audit record
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