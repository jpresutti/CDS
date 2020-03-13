CREATE DATABASE CDSSampleAPP_Main;
GO
USE [CDSSampleApp_Main]
GO
create table tbldat_Users
(
    ID       uniqueidentifier not null,
    PRI      bigint identity
        constraint PK_Users_PrimaryKey
            primary key,
    Username nvarchar(255),
    Password nvarchar(255),
    Active   bit default 1    not null
)
GO
create table tbldat_BusinessContacts
(
    ID            uniqueidentifier
        constraint DF_tbldat_BusinessContacts_ID default newid() not null,
    PRI           bigint identity
        constraint PK_PrimaryKey
            primary key,
    Company_Key   bigint,
    Title         nvarchar(50),
    FName         nvarchar(50),
    MName         nvarchar(50),
    LName         nvarchar(50),
    Suffix        nvarchar(50),
    Address_1     nvarchar(255),
    Address_2     nvarchar(255),
    City          nvarchar(50),
    State         nvarchar(5),
    PostalCode    nvarchar(24),
    Website       nvarchar(255),
    Email_Primary nvarchar(255),
    Email_2       nvarchar(255),
    EMail_3       nvarchar(255),
    Email_4       nvarchar(255),
    Phone_Primary nchar(10),
    Phone_Mobile  nchar(10),
    Phone_Land    nchar(10),
    Phone_Fax     nchar(10),
    TwitterHandle nvarchar(255),
    FaceBookName  nvarchar(255),
    Active        bit
        constraint DF_tbldat_BusinessContacts_Active default 1   not null,
    Deleted       bit
        constraint DF_tbldat_BusinessContacts_Deleted default 0  not null,
    Archived      bit
        constraint DF_tbldat_BusinessContacts_Archived default 0 not null
)
go

exec sp_addextendedproperty 'MS_Description', 'PRI is CDS Default Primary Key', 'SCHEMA', 'dbo', 'TABLE',
     'tbldat_BusinessContacts', 'CONSTRAINT', 'PK_PrimaryKey'
go

create table tbldat_Companies
(
    ID                  uniqueidentifier
        constraint DF_tbldat_Companies_ID default newid() not null,
    PRI                 bigint identity
        constraint PK_tbldat_Companies
            primary key,
    CompanyName         nvarchar(255),
    Ticker              nvarchar(50),
    NickName            nvarchar(255),
    Address_1           nvarchar(255),
    Address_2           nvarchar(255),
    City                nvarchar(255),
    State               nvarchar(5),
    PostalCode          nchar(10),
    HomeCountry         nvarchar(255),
    MainCountryOfOrigin nvarchar(255),
    Active              bit                               not null,
    Deleted             bit                               not null,
    Archived            bit                               not null
)
go

create table tbldat_ContactAuditLog
(
    ID        uniqueidentifier not null,
    PRI       bigint identity
        constraint PK_auditLog_PrimaryKey
            primary key,
    ContactId bigint
        constraint FK_CompanyauditLog_ContactId references tbldat_BusinessContacts (PRI),
    UserId    bigint
        constraint FK_ContactauditLog_UserId references tbldat_Users (PRI),
    Old       nvarchar(max),
    New       nvarchar(max),
    Timestamp DATETIME2(0) DEFAULT GETDATE()
)
go

create table tbldat_CompanyAuditLog
(
    ID        uniqueidentifier not null,
    PRI       bigint identity
        constraint PK_CompanyauditLog_PrimaryKey
            primary key,
    CompanyId bigint
        constraint FK_CompanyauditLog_CompanyId references tbldat_Companies (PRI),
    UserId    bigint
        constraint FK_CompanyauditLog_UserId references tbldat_Users (PRI),
    Old       nvarchar(max),
    New       nvarchar(max),
    Timestamp DATETIME2(0) DEFAULT GETDATE()
)


go

CREATE PROCEDURE GetCompanyByPri @PRI BIGINT AS
SELECT ID,
       PRI,
       CompanyName,
       Ticker,
       NickName,
       Address_1,
       Address_2,
       City,
       State,
       PostalCode,
       HomeCountry,
       MainCountryOfOrigin,
       Active,
       Deleted,
       Archived
FROM tbldat_Companies
where PRI = @PRI;
GO

CREATE PROCEDURE GetAllCompaniesWithDeleted AS
    SELECT ID, PRI, CompanyName, Active, Deleted, Archived
    FROM tbldat_Companies
    ORDER BY CompanyName;
GO
CREATE PROCEDURE GetAllCompaniesWithoutDeleted AS
SELECT ID, PRI, CompanyName, Active, Deleted, Archived
FROM tbldat_Companies
WHERE Deleted = 0
ORDER BY CompanyName;
GO
    CREATE PROCEDURE GetCompanyAuditLog @Company BIGINT AS
    SELECT tbldat_CompanyAuditLog.ID,
           tbldat_CompanyAuditLog.PRI,
           tbldat_CompanyAuditLog.Old,
           tbldat_CompanyAuditLog.New,
           tbldat_CompanyAuditLog.Timestamp,
           tbldat_Users.ID       as UserID,
           tbldat_Users.PRI      as UserPRI,
           tbldat_Users.Username as UserUsername


    FROM tbldat_CompanyAuditLog
             LEFT JOIN tbldat_Users ON UserId = tbldat_Users.PRI
    WHERE CompanyId = @Company
    ORDER BY tbldat_CompanyAuditLog.PRI desc;

GO
CREATE PROCEDURE GetContactAuditLog @Contact BIGINT AS
SELECT tbldat_ContactAuditLog.ID,
       tbldat_ContactAuditLog.PRI,
       tbldat_ContactAuditLog.Old,
       tbldat_ContactAuditLog.New,
       tbldat_ContactAuditLog.Timestamp,
       tbldat_Users.ID       as UserID,
       tbldat_Users.PRI      as UserPRI,
       tbldat_Users.Username as UserUsername

FROM tbldat_ContactAuditLog
         LEFT JOIN tbldat_Users ON UserId = tbldat_Users.PRI
WHERE ContactId = @Contact
ORDER BY tbldat_ContactAuditLog.PRI desc;

GO
CREATE PROCEDURE GetContactsWithDeleted @Company BIGINT AS
SELECT ID,
       PRI,
       Company_Key,
       Title,
       FName,
       MName,
       LName,
       Suffix,
       Address_1,
       Address_2,
       City,
       State,
       PostalCode,
       Website,
       Email_Primary,
       Email_2,
       EMail_3,
       Email_4,
       Phone_Primary,
       Phone_Mobile,
       Phone_Land,
       Phone_Fax,
       TwitterHandle,
       FaceBookName,
       Active,
       Deleted,
       Archived
FROM tbldat_BusinessContacts
where Company_Key = @Company
ORDER BY LName;
GO
CREATE PROCEDURE GetContactsWithoutDeleted @Company BIGINT AS
SELECT ID,
       PRI,
       Company_Key,
       Title,
       FName,
       MName,
       LName,
       Suffix,
       Address_1,
       Address_2,
       City,
       State,
       PostalCode,
       Website,
       Email_Primary,
       Email_2,
       EMail_3,
       Email_4,
       Phone_Primary,
       Phone_Mobile,
       Phone_Land,
       Phone_Fax,
       TwitterHandle,
       FaceBookName,
       Active,
       Deleted,
       Archived
FROM tbldat_BusinessContacts
where Company_Key = @Company
  and Deleted = 0
ORDER BY LName;

GO

CREATE PROCEDURE GetUserByUsername @Username nvarchar(255) AS
SELECT ID, PRI, Username, Password
FROM dbo.tbldat_Users
where Username = @Username
  and active = 1;
GO
