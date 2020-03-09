USE [CDSSampleApp_Main]
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
    ContactId bigint constraint FK_CompanyauditLog_ContactId references tbldat_BusinessContacts(PRI),
    UserId bigint constraint FK_ContactauditLog_UserId references tbldat_Users(PRI),
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
    CompanyId bigint constraint FK_CompanyauditLog_CompanyId references tbldat_Companies(PRI),
    UserId bigint constraint FK_CompanyauditLog_UserId references tbldat_Users(PRI),
    Old       nvarchar(max),
    New       nvarchar(max),
    Timestamp DATETIME2(0) DEFAULT GETDATE()
)

create table tbldat_Users
(
    ID       uniqueidentifier not null,
    PRI      bigint identity
        constraint PK_Users_PrimaryKey
            primary key,
    Username nvarchar(255),
    Password nvarchar(255),
    Active bit default 1 not null
)
go

