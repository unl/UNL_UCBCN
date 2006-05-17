CREATE TABLE IF NOT EXISTS Account (
  ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  User_has_Permission_User_CalNetUID Varchar(100) NOT NULL,
  User_has_Permission_Permission_ID INTEGER UNSIGNED NOT NULL,
  Name Varchar(100) NULL,
  ShortName Varchar(100) NULL,
  StreetAddress1 Varchar(255) NULL,
  StreetAddress2 Varchar(255) NULL,
  City Varchar(100) NULL,
  State CHAR(2) NULL,
  Zip VARCHAR(10) NULL,
  Phone Varchar(50) NULL,
  Fax Varchar(50) NULL,
  Email Varchar(100) NULL,
  EventReleasePreference VARCHAR(100) NULL,
  AccountStatus VARCHAR(100) NULL,
  CalendarDateRange VARCHAR(100) NULL,
  FormatCalendarData TEXT NULL,
  EmailLists TEXT NULL,
  DateCreated DATETIME NULL,
  CalNetUIDCreated VARCHAR(100) NULL,
  DateLastUpdated TIMESTAMP NULL,
  CalNetUIDLastUpdated VARCHAR(100) NULL,
  PRIMARY KEY(ID),
  INDEX Account_FKIndex1(User_has_Permission_Permission_ID, User_has_Permission_User_CalNetUID)
);

CREATE TABLE IF NOT EXISTS Account_has_Event (
  Account_ID INTEGER UNSIGNED NOT NULL,
  Event_ID INTEGER UNSIGNED NOT NULL,
  Status VARCHAR(100) NULL,
  Source VARCHAR(100) NULL,
  DateCreated DATETIME NULL,
  CalNetUIDCreated Varchar(100) NULL,
  DateLastUpdated TIMESTAMP NULL,
  CalNetUIDLastUpdated Varchar(100) NULL,
  PRIMARY KEY(Account_ID, Event_ID),
  INDEX Account_has_Event_FKIndex1(Account_ID),
  INDEX Account_has_Event_FKIndex2(Event_ID)
);

CREATE TABLE IF NOT EXISTS AdmissionCharge (
  ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  AdmissionInfoGroup_ID INTEGER UNSIGNED NOT NULL,
  Price Varchar(100) NULL,
  Description Varchar(255) NULL,
  PRIMARY KEY(ID),
  INDEX AdmissionCharge_FKIndex1(AdmissionInfoGroup_ID)
);

CREATE TABLE IF NOT EXISTS AdmissionInfoGroup (
  ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  Event_ID INTEGER UNSIGNED NOT NULL,
  TicketPolicy Varchar(100) NULL,
  TicketContactName Varchar(100) NULL,
  TicketContactPhone Varchar(50) NULL,
  TicketContactURL TEXT NULL,
  TicketsOnSaleDate DATETIME NULL,
  TicketAdditionalInfo VARCHAR(255) NULL,
  ReservationPolicy Varchar(100) NULL,
  ReservationContactName Varchar(100) NULL,
  ReservationContactPhone Varchar(50) NULL,
  ReservationContactURL TEXT NULL,
  ReservationAdditionalInfo Varchar(255) NULL,
  FreeEvent BOOL NULL,
  SoldOut BOOL NULL,
  OtherAudience Varchar(255) NULL,
  PRIMARY KEY(ID),
  INDEX AdmissionInfoGroup_FKIndex1(Event_ID)
);

CREATE TABLE IF NOT EXISTS AdmissionInfoGroup_has_Audience (
  AdmissionInfoGroup_ID INTEGER UNSIGNED NOT NULL,
  Audience_ID INTEGER UNSIGNED NOT NULL,
  PRIMARY KEY(AdmissionInfoGroup_ID, Audience_ID),
  INDEX AdmissionInfoGroup_has_Audience_FKIndex1(AdmissionInfoGroup_ID),
  INDEX AdmissionInfoGroup_has_Audience_FKIndex2(Audience_ID)
);

CREATE TABLE IF NOT EXISTS AttendanceRestriction (
  ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  Event_ID INTEGER UNSIGNED NOT NULL,
  Name VARCHAR(100) NULL,
  Description VARCHAR(255) NULL,
  PRIMARY KEY(ID),
  INDEX AttendanceRestriction_FKIndex1(Event_ID)
);

CREATE TABLE IF NOT EXISTS Audience (
  ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  Name VARCHAR(100) NULL,
  PRIMARY KEY(ID)
);

CREATE TABLE IF NOT EXISTS Document (
  ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  Event_ID INTEGER UNSIGNED NOT NULL,
  Name Varchar(100) NULL,
  URL TEXT NULL,
  PRIMARY KEY(ID),
  INDEX Document_FKIndex1(Event_ID)
);

CREATE TABLE IF NOT EXISTS Event (
  ID INTEGER UNSIGNED NOT NULL,
  EventType_ID INTEGER UNSIGNED NOT NULL,
  Title VARCHAR(100) NULL,
  Subtitle VARCHAR(100) NULL,
  OtherType Varchar(255) NULL,
  Subtype Varchar(100) NULL,
  Description TEXT NULL,
  ShortDescription VARCHAR(255) NULL,
  Refreshments VARCHAR(255) NULL,
  NetworkClassification Varchar(100) NULL,
  ApprovedForCirculation BOOL NULL,
  Status Varchar(100) NULL,
  OwnerID INTEGER UNSIGNED NULL,
  PrivateComment TEXT NULL,
  OtherKeyword Varchar(255) NULL,
  ImageTitle Varchar(100) NULL,
  ImageURL TEXT NULL,
  WebPageURL TEXT NULL,
  ListingContactCalNetUID VARCHAR(100) NULL,
  DateCreated DATETIME NULL,
  CalNetUIDCreated VARCHAR(100) NULL,
  DateLastUpdated TIMESTAMP NULL,
  CalNetUIDLastUpdated VARCHAR(100) NULL,
  PRIMARY KEY(ID),
  INDEX Event_FKIndex1(EventType_ID)
);

CREATE TABLE IF NOT EXISTS EventType (
  ID INTEGER UNSIGNED NOT NULL,
  Name Varchar(100) NOT NULL,
  Description Varchar(255) NULL,
  PRIMARY KEY(ID)
);

CREATE TABLE IF NOT EXISTS Event_has_Location (
  Event_ID INTEGER UNSIGNED NOT NULL,
  Location_ID INTEGER UNSIGNED NOT NULL,
  PRIMARY KEY(Event_ID, Location_ID),
  INDEX Event_has_Location_FKIndex1(Event_ID),
  INDEX Event_has_Location_FKIndex2(Location_ID)
);

CREATE TABLE IF NOT EXISTS Event_has_Sponsor (
  Event_ID INTEGER UNSIGNED NOT NULL,
  Sponsor_ID INTEGER UNSIGNED NOT NULL,
  PRIMARY KEY(Event_ID, Sponsor_ID),
  INDEX Event_has_Sponsor_FKIndex1(Event_ID),
  INDEX Event_has_Sponsor_FKIndex2(Sponsor_ID)
);

CREATE TABLE IF NOT EXISTS FeatureType (
  ID INTEGER UNSIGNED NOT NULL,
  Name Varchar(100) NOT NULL,
  Event_ID INTEGER UNSIGNED NOT NULL,
  Description VARCHAR(255) NULL,
  PRIMARY KEY(ID),
  INDEX FeatureType_FKIndex1(Event_ID)
);

CREATE TABLE IF NOT EXISTS Keyword (
  ID INTEGER UNSIGNED NOT NULL,
  Name Varchar(100) NOT NULL,
  Event_ID INTEGER UNSIGNED NOT NULL,
  PRIMARY KEY(ID),
  INDEX Keyword_FKIndex1(Event_ID)
);

CREATE TABLE IF NOT EXISTS Location (
  ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  Name Varchar(100) NULL,
  StreetAddress1 Varchar(255) NULL,
  StreetAddress2 Varchar(255) NULL,
  Room VARCHAR(100) NULL,
  City Varchar(100) NULL,
  State CHAR(2) NULL,
  Zip VARCHAR(10) NULL,
  MapURL TEXT NULL,
  WebPageURL TEXT NULL,
  Hours Varchar(255) NULL,
  AdditionalPublicInfo Varchar(255) NULL,
  Type  Varchar(100) NULL,
  Phone VARCHAR(50) NULL,
  PRIMARY KEY(ID)
);

CREATE TABLE IF NOT EXISTS Performer (
  ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  PerformerType_ID INTEGER UNSIGNED NOT NULL,
  Event_ID INTEGER UNSIGNED NOT NULL,
  PersonalName Varchar(100) NULL,
  OtherPerformerType Varchar(255) NULL,
  JobTitle Varchar(100) NULL,
  OrganizationName Varchar(100) NULL,
  PersonalWebPageURL TEXT NULL,
  OrganizationWebPageURL TEXT NULL,
  PRIMARY KEY(ID),
  INDEX Performer_FKIndex1(Event_ID),
  INDEX Performer_FKIndex2(PerformerType_ID)
);

CREATE TABLE IF NOT EXISTS PerformerType (
  ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  Name Varchar(100) NULL,
  Description Varchar(255) NULL,
  PRIMARY KEY(ID)
);

CREATE TABLE IF NOT EXISTS Permission (
  ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  Name Varchar(100) NULL,
  Description Varchar(255) NULL,
  PRIMARY KEY(ID)
);

CREATE TABLE IF NOT EXISTS PublicContact (
  ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  Event_ID INTEGER UNSIGNED NOT NULL,
  Name Varchar(100) NULL,
  JobTitle Varchar(100) NULL,
  Organization Varchar(100) NULL,
  AddressLine1 Varchar(255) NULL,
  AddressLine2 Varchar(255) NULL,
  City Varchar(100) NULL,
  State CHAR(2) NULL,
  Zip VARCHAR(10) NULL,
  EmailAddress Varchar(100) NULL,
  Phone Varchar(50) NULL,
  Fax Varchar(50) NULL,
  WebPageURL TEXT NULL,
  PRIMARY KEY(ID),
  INDEX PublicContact_FKIndex1(Event_ID)
);

CREATE TABLE IF NOT EXISTS RelatedEvent (
  Event_ID INTEGER UNSIGNED NOT NULL,
  RelatedEventID INTEGER UNSIGNED NOT NULL,
  RelationType Varchar(100) NOT NULL,
  PRIMARY KEY(Event_ID, RelatedEventID, RelationType),
  INDEX RelatedEvent_FKIndex1(Event_ID)
);

CREATE TABLE IF NOT EXISTS Sponsor (
  ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  Name VARCHAR(255) NULL,
  Level VARCHAR(100) NULL,
  LogoTitle Varchar(100) NULL,
  LogoURL TEXT NULL,
  Description TEXT NULL,
  WebPageURL TEXT NULL,
  PRIMARY KEY(ID)
);

CREATE TABLE IF NOT EXISTS Subscription (
  ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  Account_ID INTEGER UNSIGNED NOT NULL,
  Name VARCHAR(100) NULL,
  AutomaticApproval BOOL NOT NULL,
  TimePeriod DATE NULL,
  ExpirationDate DATE NULL,
  SearchCriteria TEXT NULL,
  DateCreated DATETIME NULL,
  CalNetUIDCreated VARCHAR(100) NULL,
  DateLastUpdated TIMESTAMP NULL,
  CalNetUIDLastUpdated VARCHAR(100) NULL,
  PRIMARY KEY(ID),
  INDEX Subscription_FKIndex1(Account_ID)
);

CREATE TABLE IF NOT EXISTS User (
  CalNetUID Varchar(100) NOT NULL,
  Account_ID INTEGER UNSIGNED NOT NULL,
  AccountStatus VARCHAR(100) NULL,
  DateCreated DATETIME NULL,
  CalNetUIDCreated VARCHAR(100) NULL,
  DateLastUpdated TIMESTAMP NULL,
  CalNetUIDLastUpdated VARCHAR(100) NULL,
  PRIMARY KEY(CalNetUID),
  INDEX User_FKIndex1(Account_ID)
);

CREATE TABLE IF NOT EXISTS User_has_Permission (
  Permission_ID INTEGER UNSIGNED NOT NULL,
  User_CalNetUID Varchar(100) NOT NULL,
  Accoun INTEGER UNSIGNED NULL,
  PRIMARY KEY(Permission_ID, User_CalNetUID),
  INDEX User_has_Permission_FKIndex1(User_CalNetUID),
  INDEX User_has_Permission_FKIndex2(Permission_ID)
);

CREATE TABLE IF NOT EXISTS Webcast (
  ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  Event_ID INTEGER UNSIGNED NOT NULL,
  Title Varchar(100) NULL,
  Status Varchar(100) NULL,
  DateAvailable DATETIME NULL,
  PlayerType Varchar(100) NULL,
  PRIMARY KEY(ID),
  INDEX Webcast_FKIndex1(Event_ID)
);

CREATE TABLE IF NOT EXISTS WebcastLink (
  ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  Webcast_ID INTEGER UNSIGNED NOT NULL,
  URL TEXT NULL,
  SequenceNumber INTEGER UNSIGNED NULL,
  PRIMARY KEY(ID),
  INDEX WebcastURL_FKIndex1(Webcast_ID)
);