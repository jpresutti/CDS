<?php

declare(strict_types=1);

namespace CDS\DataModels;

class Contact
{
    public ?string $ID;
    public ?string $PRI;
    public ?string $Company_Key;
    public ?string $Title;
    public ?string $FName;
    public ?string $MName;
    public ?string $LName;
    public ?string $Suffix;
    public ?string $Address_1;
    public ?string $Address_2;
    public ?string $City;
    public ?string $State;
    public ?string $PostalCode;
    public ?string $Website;
    public ?string $Email_Primary;
    public ?string $Email_2;
    public ?string $EMail_3;
    public ?string $Email_4;
    public ?string $Phone_Primary;
    public ?string $Phone_Mobile;
    public ?string $Phone_Land;
    public ?string $Phone_Fax;
    public ?string $TwitterHandle;
    public ?string $FaceBookName;
    public ?bool $Active;
    public ?bool $Deleted;
    public ?bool $Archived;
    
    /**
     * Get pretty assembled name for contact
     * @return string
     */
    public function getContactName() : string {
        $name = '';
        $name .= $this->Title ? $this->Title . ' ': '';
        $name .= $this->FName ? $this->FName . ' ': '';
        $name .= $this->MName ? $this->MName . ' ': '';
        $name .= $this->LName ? $this->LName . ' ': '';
        $name .= $this->Suffix ? $this->Suffix : '';
        
        return trim($name);
    }
}