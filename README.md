# CDS
#### This application is deliberately not using any external PHP code dependencies (except PHPUnit) and as such is not as efficient or flexible as it might otherwise be.

##### This application IS using Bootstrap4 for html/layout

#### Prerequisites
1. PHP 7.4
2. SQL Server 2019

#### Required PHP Libraries
1. PDO
2. SQLSRV
3. PDO_SQLSRV

---
Copy `config.ini.sample` to `config.ini` and update values before attempting to use this application

after configuring config file, run `php setup/setupUser.php`

Default primary user: Admin

Default primary password: Passw0rd (that is a zero not an oh)

Default secondary user: Jeremy

Default secondary password: passw0rd (that is a zero not an oh)

---
To run the unit test file (login is only current test), navigate to `CDS\Tests` and run the following command: 

```phpunit --bootstrap ../../baseline.php LoginTest```