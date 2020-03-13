<?php
declare(strict_types=1);

namespace CDS\Tests;
use CDS\DataModels\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase {
    public static $testUser;
    public static function setUpBeforeClass() {
        $user = new User();
        $user->Username = 'Test';
        $user->setPassword('Passw0rd');
        self::$testUser = $user;
        
    }
    public function testValidLogin(): void
    {
        $this->assertTrue(self::$testUser->checkPassword('Passw0rd'));
    }
    
    public function testInvalidLogin(): void
    {
        $this->assertFalse(self::$testUser->checkPassword('Password'));
    }
    
}