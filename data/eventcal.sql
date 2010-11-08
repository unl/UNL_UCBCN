-- phpMyAdmin SQL Dump
-- version 2.11.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 16, 2010 at 01:48 PM
-- Server version: 5.1.39
-- PHP Version: 5.3.0

--
-- Database: `eventcal`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE IF NOT EXISTS "account" (
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "name" varchar(100) DEFAULT NULL,
  "streetaddress1" varchar(255) DEFAULT NULL,
  "streetaddress2" varchar(255) DEFAULT NULL,
  "city" varchar(100) DEFAULT NULL,
  "state" varchar(2) DEFAULT NULL,
  "zip" varchar(10) DEFAULT NULL,
  "phone" varchar(50) DEFAULT NULL,
  "fax" varchar(50) DEFAULT NULL,
  "email" varchar(100) DEFAULT NULL,
  "accountstatus" varchar(100) DEFAULT NULL,
  "datecreated" datetime DEFAULT NULL,
  "datelastupdated" datetime DEFAULT NULL,
  "sponsor_id" int(11) NOT NULL DEFAULT '0',
  "website" varchar(255) DEFAULT NULL,
  PRIMARY KEY ("id")
);

-- --------------------------------------------------------

--
-- Table structure for table `admissioncharge`
--

CREATE TABLE IF NOT EXISTS "admissioncharge" (
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "admissioninfogroup_id" int(10) unsigned NOT NULL DEFAULT '0',
  "price" varchar(100) DEFAULT NULL,
  "description" varchar(255) DEFAULT NULL,
  PRIMARY KEY ("id")
);

-- --------------------------------------------------------

--
-- Table structure for table `admissioninfo`
--

CREATE TABLE IF NOT EXISTS "admissioninfo" (
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "event_id" int(10) unsigned NOT NULL DEFAULT '0',
  "type" varchar(255) DEFAULT NULL,
  "obligation" varchar(100) DEFAULT NULL,
  "contactname" varchar(100) DEFAULT NULL,
  "contactphone" varchar(50) DEFAULT NULL,
  "contactemail" varchar(255) DEFAULT NULL,
  "contacturl" longtext,
  "status" varchar(255) DEFAULT NULL,
  "additionalinfo" longtext,
  "deadline" datetime DEFAULT NULL,
  "opendate" datetime DEFAULT NULL,
  PRIMARY KEY ("id"),
  KEY "event_id_idx" ("event_id")
);

-- --------------------------------------------------------

--
-- Table structure for table `attendancerestriction`
--

CREATE TABLE IF NOT EXISTS "attendancerestriction" (
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "event_id" int(10) unsigned NOT NULL DEFAULT '0',
  "description" longtext,
  PRIMARY KEY ("id"),
  KEY "attendancerestriction_event_id_idx" ("event_id")
);

-- --------------------------------------------------------

--
-- Table structure for table `audience`
--

CREATE TABLE IF NOT EXISTS "audience" (
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "name" varchar(100) DEFAULT NULL,
  "standard" tinyint(1) DEFAULT '1',
  PRIMARY KEY ("id")
);

-- --------------------------------------------------------

--
-- Table structure for table `auth`
--

CREATE TABLE IF NOT EXISTS "auth" (
  "username" varchar(50) NOT NULL DEFAULT '',
  "password" varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY ("username"),
  KEY "password" ("password")
);

-- --------------------------------------------------------

--
-- Table structure for table `calendar`
--

CREATE TABLE IF NOT EXISTS "calendar" (
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "account_id" int(10) unsigned NOT NULL DEFAULT '0',
  "name" varchar(255) DEFAULT NULL,
  "shortname" varchar(100) DEFAULT NULL,
  "eventreleasepreference" varchar(255) DEFAULT NULL,
  "calendardaterange" int(10) unsigned DEFAULT NULL,
  "formatcalendardata" longtext,
  "uploadedcss" longtext,
  "uploadedxsl" longtext,
  "emaillists" longtext,
  "calendarstatus" varchar(255) DEFAULT NULL,
  "datecreated" datetime DEFAULT NULL,
  "uidcreated" varchar(255) DEFAULT NULL,
  "datelastupdated" datetime DEFAULT NULL,
  "uidlastupdated" varchar(255) DEFAULT NULL,
  "externalforms" varchar(255) DEFAULT NULL,
  "website" varchar(255) DEFAULT NULL,
  "theme" varchar(255) DEFAULT 'base',
  PRIMARY KEY ("id"),
  KEY "account_id_idx" ("account_id"),
  KEY "shortname_idx" ("shortname")
);

-- --------------------------------------------------------

--
-- Table structure for table `calendar_has_event`
--

CREATE TABLE IF NOT EXISTS "calendar_has_event" (
  "calendar_id" int(10) unsigned NOT NULL DEFAULT '0',
  "event_id" int(10) unsigned NOT NULL DEFAULT '0',
  "status" varchar(100) DEFAULT NULL,
  "source" varchar(100) DEFAULT NULL,
  "datecreated" datetime DEFAULT NULL,
  "uidcreated" varchar(100) DEFAULT NULL,
  "datelastupdated" datetime DEFAULT NULL,
  "uidlastupdated" varchar(100) DEFAULT NULL,
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY ("id"),
  KEY "che_calendar_id_idx" ("calendar_id"),
  KEY "che_event_id_idx" ("event_id"),
  KEY "che_status_idx" ("status")
);

-- --------------------------------------------------------

--
-- Table structure for table `document`
--

CREATE TABLE IF NOT EXISTS "document" (
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "event_id" int(10) unsigned NOT NULL DEFAULT '0',
  "name" varchar(100) DEFAULT NULL,
  "url" varchar(255) DEFAULT NULL,
  PRIMARY KEY ("id")
);

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE IF NOT EXISTS "event" (
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "title" varchar(100) NOT NULL DEFAULT ' ',
  "subtitle" varchar(100) DEFAULT NULL,
  "othereventtype" varchar(255) DEFAULT NULL,
  "description" longtext,
  "shortdescription" varchar(255) DEFAULT NULL,
  "refreshments" varchar(255) DEFAULT NULL,
  "classification" varchar(100) DEFAULT NULL,
  "approvedforcirculation" tinyint(1) DEFAULT NULL,
  "transparency" varchar(255) DEFAULT NULL,
  "status" varchar(100) DEFAULT NULL,
  "privatecomment" longtext,
  "otherkeywords" varchar(255) DEFAULT NULL,
  "imagetitle" varchar(100) DEFAULT NULL,
  "imageurl" longtext,
  "webpageurl" longtext,
  "listingcontactuid" varchar(255) DEFAULT NULL,
  "listingcontactname" varchar(100) DEFAULT NULL,
  "listingcontactphone" varchar(255) DEFAULT NULL,
  "listingcontactemail" varchar(255) DEFAULT NULL,
  "icalendar" longtext,
  "imagedata" longblob,
  "imagemime" varchar(255) DEFAULT NULL,
  "datecreated" datetime DEFAULT NULL,
  "uidcreated" varchar(100) DEFAULT NULL,
  "datelastupdated" datetime DEFAULT NULL,
  "uidlastupdated" varchar(100) DEFAULT NULL,
  PRIMARY KEY ("id"),
  KEY "event_title_idx" ("title")
);

-- --------------------------------------------------------

--
-- Table structure for table `eventdatetime`
--

CREATE TABLE IF NOT EXISTS "eventdatetime" (
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "event_id" int(10) unsigned NOT NULL DEFAULT '0',
  "location_id" int(10) unsigned NOT NULL DEFAULT '0',
  "starttime" datetime DEFAULT NULL,
  "endtime" datetime DEFAULT NULL,
  "room" varchar(255) DEFAULT NULL,
  "hours" varchar(255) DEFAULT NULL,
  "directions" longtext,
  "additionalpublicinfo" longtext,
  PRIMARY KEY ("id"),
  KEY "edt_event_id_idx" ("event_id"),
  KEY "edt_location_id_idx" ("location_id"),
  KEY "edt_starttime_idx" ("starttime"),
  KEY "edt_endtime_idx" ("endtime")
);

-- --------------------------------------------------------

--
-- Table structure for table `eventtype`
--

CREATE TABLE IF NOT EXISTS "eventtype" (
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "calendar_id" int(10) unsigned NOT NULL DEFAULT '0',
  "name" varchar(100) NOT NULL DEFAULT ' ',
  "description" varchar(255) DEFAULT NULL,
  "eventtypegroup" varchar(8) DEFAULT NULL,
  "standard" tinyint(1) DEFAULT '1',
  PRIMARY KEY ("id"),
  KEY "eventtype_name_idx" ("name")
);

-- --------------------------------------------------------

--
-- Table structure for table `event_has_eventtype`
--

CREATE TABLE IF NOT EXISTS "event_has_eventtype" (
  "event_id" int(10) unsigned NOT NULL DEFAULT '0',
  "eventtype_id" int(10) unsigned NOT NULL DEFAULT '0',
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY ("id"),
  KEY "ehe_event_id_idx" ("event_id"),
  KEY "ehe_eventtype_id_idx" ("eventtype_id")
);

-- --------------------------------------------------------

--
-- Table structure for table `event_has_keyword`
--

CREATE TABLE IF NOT EXISTS "event_has_keyword" (
  "event_id" int(10) unsigned NOT NULL DEFAULT '0',
  "keyword_id" int(10) unsigned NOT NULL DEFAULT '0',
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY ("id"),
  KEY "ehk_event_id_idx" ("event_id"),
  KEY "ehk_sponsor_id_idx" ("keyword_id")
);

-- --------------------------------------------------------

--
-- Table structure for table `event_has_sponsor`
--

CREATE TABLE IF NOT EXISTS "event_has_sponsor" (
  "event_id" int(10) unsigned NOT NULL DEFAULT '0',
  "sponsor_id" int(10) unsigned NOT NULL DEFAULT '0',
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY ("id"),
  KEY "ehs_event_id_idx" ("event_id"),
  KEY "ehs_sponsor_id_idx" ("sponsor_id")
);

-- --------------------------------------------------------

--
-- Table structure for table `event_isopento_audience`
--

CREATE TABLE IF NOT EXISTS "event_isopento_audience" (
  "event_id" int(10) unsigned NOT NULL DEFAULT '0',
  "audience_id" int(10) unsigned NOT NULL DEFAULT '0',
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY ("id"),
  KEY "eia_event_id_idx" ("event_id"),
  KEY "eia_audience_id_idx" ("audience_id")
);

-- --------------------------------------------------------

--
-- Table structure for table `event_targets_audience`
--

CREATE TABLE IF NOT EXISTS "event_targets_audience" (
  "event_id" int(10) unsigned NOT NULL DEFAULT '0',
  "audience_id" int(10) unsigned NOT NULL DEFAULT '0',
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY ("id"),
  KEY "eta_event_id_idx" ("event_id"),
  KEY "eta_audience_id_idx" ("audience_id")
);

-- --------------------------------------------------------

--
-- Table structure for table `keyword`
--

CREATE TABLE IF NOT EXISTS "keyword" (
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "name" varchar(100) NOT NULL DEFAULT ' ',
  PRIMARY KEY ("id")
);

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE IF NOT EXISTS "location" (
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "name" varchar(100) DEFAULT NULL,
  "streetaddress1" varchar(255) DEFAULT NULL,
  "streetaddress2" varchar(255) DEFAULT NULL,
  "room" varchar(100) DEFAULT NULL,
  "city" varchar(100) DEFAULT NULL,
  "state" varchar(2) DEFAULT NULL,
  "zip" varchar(10) DEFAULT NULL,
  "mapurl" longtext,
  "webpageurl" longtext,
  "hours" varchar(255) DEFAULT NULL,
  "directions" longtext,
  "additionalpublicinfo" varchar(255) DEFAULT NULL,
  "type" varchar(100) DEFAULT NULL,
  "phone" varchar(50) DEFAULT NULL,
  "standard" tinyint(1) DEFAULT '1',
  PRIMARY KEY ("id"),
  KEY "location_name_idx" ("name")
);

-- --------------------------------------------------------

--
-- Table structure for table `ongoingcheck`
--

CREATE TABLE IF NOT EXISTS "ongoingcheck" (
  "d" date NOT NULL,
  PRIMARY KEY ("d")
);

-- --------------------------------------------------------

--
-- Table structure for table `performer`
--

CREATE TABLE IF NOT EXISTS "performer" (
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "performer_id" int(10) unsigned NOT NULL DEFAULT '0',
  "role_id" int(10) unsigned NOT NULL DEFAULT '0',
  "event_id" int(10) unsigned NOT NULL DEFAULT '0',
  "personalname" varchar(100) DEFAULT NULL,
  "name" varchar(255) DEFAULT NULL,
  "jobtitle" varchar(100) DEFAULT NULL,
  "organizationname" varchar(100) DEFAULT NULL,
  "personalwebpageurl" longtext,
  "organizationwebpageurl" longtext,
  "type" varchar(255) DEFAULT NULL,
  PRIMARY KEY ("id")
);

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

CREATE TABLE IF NOT EXISTS "permission" (
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "name" varchar(100) DEFAULT NULL,
  "description" varchar(255) DEFAULT NULL,
  PRIMARY KEY ("id")
);

-- --------------------------------------------------------

--
-- Table structure for table `publiccontact`
--

CREATE TABLE IF NOT EXISTS "publiccontact" (
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "event_id" int(10) unsigned NOT NULL DEFAULT '0',
  "name" varchar(100) DEFAULT NULL,
  "jobtitle" varchar(100) DEFAULT NULL,
  "organization" varchar(100) DEFAULT NULL,
  "addressline1" varchar(255) DEFAULT NULL,
  "addressline2" varchar(255) DEFAULT NULL,
  "room" varchar(255) DEFAULT NULL,
  "city" varchar(100) DEFAULT NULL,
  "state" varchar(2) DEFAULT NULL,
  "zip" varchar(10) DEFAULT NULL,
  "emailaddress" varchar(100) DEFAULT NULL,
  "phone" varchar(50) DEFAULT NULL,
  "fax" varchar(50) DEFAULT NULL,
  "webpageurl" longtext,
  PRIMARY KEY ("id")
);

-- --------------------------------------------------------

--
-- Table structure for table `relatedevent`
--

CREATE TABLE IF NOT EXISTS "relatedevent" (
  "event_id" int(10) unsigned NOT NULL DEFAULT '0',
  "related_event_id" int(10) unsigned NOT NULL DEFAULT '0',
  "relationtype" varchar(100) NOT NULL DEFAULT ' '
);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE IF NOT EXISTS "role" (
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "name" varchar(255) NOT NULL DEFAULT ' ',
  "standard" tinyint(1) DEFAULT '1',
  PRIMARY KEY ("id")
);

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

CREATE TABLE IF NOT EXISTS "session" (
  "user_uid" varchar(255) NOT NULL DEFAULT ' ',
  "lastaction" datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  "data" longtext,
  PRIMARY KEY ("user_uid")
);

-- --------------------------------------------------------

--
-- Table structure for table `sponsor`
--

CREATE TABLE IF NOT EXISTS "sponsor" (
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "name" varchar(255) DEFAULT NULL,
  "standard" tinyint(1) DEFAULT '1',
  "sponsortype" varchar(255) DEFAULT NULL,
  "webpageurl" longtext,
  PRIMARY KEY ("id")
);

-- --------------------------------------------------------

--
-- Table structure for table `subscription`
--

CREATE TABLE IF NOT EXISTS "subscription" (
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "name" varchar(100) DEFAULT NULL,
  "automaticapproval" tinyint(1) NOT NULL DEFAULT '0',
  "timeperiod" date DEFAULT NULL,
  "expirationdate" date DEFAULT NULL,
  "searchcriteria" longtext,
  "datecreated" datetime DEFAULT NULL,
  "uidcreated" varchar(100) DEFAULT NULL,
  "datelastupdated" datetime DEFAULT NULL,
  "uidlastupdated" varchar(100) DEFAULT NULL,
  "calendar_id" int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY ("id"),
  KEY "calendar_id_idx" ("calendar_id")
);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS "user" (
  "uid" varchar(100) NOT NULL DEFAULT ' ',
  "account_id" int(10) unsigned NOT NULL DEFAULT '0',
  "accountstatus" varchar(100) DEFAULT NULL,
  "datecreated" datetime DEFAULT NULL,
  "uidcreated" varchar(100) DEFAULT NULL,
  "datelastupdated" datetime DEFAULT NULL,
  "uidlastupdated" varchar(100) DEFAULT NULL,
  "calendar_id" int(10) unsigned DEFAULT '0',
  PRIMARY KEY ("uid")
);

-- --------------------------------------------------------

--
-- Table structure for table `user_has_permission`
--

CREATE TABLE IF NOT EXISTS "user_has_permission" (
  "permission_id" int(10) unsigned NOT NULL DEFAULT '0',
  "user_uid" varchar(100) NOT NULL DEFAULT ' ',
  "calendar_id" int(10) unsigned NOT NULL DEFAULT '0',
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY ("id")
);

-- --------------------------------------------------------

--
-- Table structure for table `webcast`
--

CREATE TABLE IF NOT EXISTS "webcast" (
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "event_id" int(10) unsigned NOT NULL DEFAULT '0',
  "title" varchar(100) DEFAULT NULL,
  "status" varchar(100) DEFAULT NULL,
  "dateavailable" datetime DEFAULT NULL,
  "playertype" varchar(100) DEFAULT NULL,
  "bandwidth" varchar(255) DEFAULT NULL,
  "additionalinfo" longtext,
  PRIMARY KEY ("id")
);

-- --------------------------------------------------------

--
-- Table structure for table `webcastlink`
--

CREATE TABLE IF NOT EXISTS "webcastlink" (
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "webcast_id" int(10) unsigned NOT NULL DEFAULT '0',
  "url" longtext,
  "sequencenumber" int(10) unsigned DEFAULT NULL,
  "related" varchar(1) DEFAULT 'n',
  PRIMARY KEY ("id")
);
