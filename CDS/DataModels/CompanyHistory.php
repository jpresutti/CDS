<?php

declare(strict_types=1);

namespace CDS\DataModels;

class CompanyHistory {
    public string $ID;
    public string $PRI;
    public Company $company;
    public User $user;
    public \stdClass $Old;
    public \stdClass $New;
    public \DateTime $Timestamp;
}