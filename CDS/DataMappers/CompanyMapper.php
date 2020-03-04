<?php

declare(strict_types=1);

namespace CDS\DataMappers;

use CDS\Database;
use CDS\DataModels\Company;
use CDS\DataModels\User;

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
        }
        
        return $company;
    }
}