<?php

declare(strict_types=1);

namespace CDS\DataModels;

class ContactHistory {
    public string $ID;
    public string $PRI;
    public Contact $contact;
    public User $user;
    public \stdClass $Old;
    public \stdClass $New;
    public \DateTime $Timestamp;
}