<?php

declare(strict_types=1);

namespace CDS\DataModels;

class Company {
    public ?string $ID;
    public ?string $PRI;
    public ?string $CompanyName;
    public ?string $Ticker;
    public ?string $NickName;
    public ?string $Address_1;
    public ?string $Address_2;
    public ?string $City;
    public ?string $State;
    public ?string $PostalCode;
    public ?string $HomeCountry;
    public ?string $MainCountryOfOrigin;
    public ?bool $Active;
    public ?bool $Deleted;
    public ?bool $Archived;
    
}