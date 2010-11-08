-- MySQL dump 10.13  Distrib 5.1.39, for apple-darwin9.5.0 (i386)
--
-- Host: localhost    Database: eventcal
-- ------------------------------------------------------
-- Server version	5.1.39
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,POSTGRESQL' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table "account"
--

DROP TABLE IF EXISTS "account";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "account" (
  "id" int(10) unsigned NOT NULL,
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "account"
--

LOCK TABLES "account" WRITE;
/*!40000 ALTER TABLE "account" DISABLE KEYS */;
/*!40000 ALTER TABLE "account" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table "admissioncharge"
--

DROP TABLE IF EXISTS "admissioncharge";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "admissioncharge" (
  "id" int(10) unsigned NOT NULL,
  "admissioninfogroup_id" int(10) unsigned NOT NULL DEFAULT '0',
  "price" varchar(100) DEFAULT NULL,
  "description" varchar(255) DEFAULT NULL,
  PRIMARY KEY ("id")
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "admissioncharge"
--

LOCK TABLES "admissioncharge" WRITE;
/*!40000 ALTER TABLE "admissioncharge" DISABLE KEYS */;
/*!40000 ALTER TABLE "admissioncharge" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table "admissioninfo"
--

DROP TABLE IF EXISTS "admissioninfo";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "admissioninfo" (
  "id" int(10) unsigned NOT NULL,
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "admissioninfo"
--

LOCK TABLES "admissioninfo" WRITE;
/*!40000 ALTER TABLE "admissioninfo" DISABLE KEYS */;
/*!40000 ALTER TABLE "admissioninfo" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table "attendancerestriction"
--

DROP TABLE IF EXISTS "attendancerestriction";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "attendancerestriction" (
  "id" int(10) unsigned NOT NULL,
  "event_id" int(10) unsigned NOT NULL DEFAULT '0',
  "description" longtext,
  PRIMARY KEY ("id"),
  KEY "attendancerestriction_event_id_idx" ("event_id")
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "attendancerestriction"
--

LOCK TABLES "attendancerestriction" WRITE;
/*!40000 ALTER TABLE "attendancerestriction" DISABLE KEYS */;
/*!40000 ALTER TABLE "attendancerestriction" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table "audience"
--

DROP TABLE IF EXISTS "audience";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "audience" (
  "id" int(10) unsigned NOT NULL,
  "name" varchar(100) DEFAULT NULL,
  "standard" tinyint(1) DEFAULT '1',
  PRIMARY KEY ("id")
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "audience"
--

LOCK TABLES "audience" WRITE;
/*!40000 ALTER TABLE "audience" DISABLE KEYS */;
/*!40000 ALTER TABLE "audience" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table "auth"
--

DROP TABLE IF EXISTS "auth";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "auth" (
  "username" varchar(50) NOT NULL DEFAULT '',
  "password" varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY ("username"),
  KEY "password" ("password")
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "auth"
--

LOCK TABLES "auth" WRITE;
/*!40000 ALTER TABLE "auth" DISABLE KEYS */;
/*!40000 ALTER TABLE "auth" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table "calendar"
--

DROP TABLE IF EXISTS "calendar";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "calendar" (
  "id" int(10) unsigned NOT NULL,
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "calendar"
--

LOCK TABLES "calendar" WRITE;
/*!40000 ALTER TABLE "calendar" DISABLE KEYS */;
/*!40000 ALTER TABLE "calendar" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table "calendar_has_event"
--

DROP TABLE IF EXISTS "calendar_has_event";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "calendar_has_event" (
  "calendar_id" int(10) unsigned NOT NULL DEFAULT '0',
  "event_id" int(10) unsigned NOT NULL DEFAULT '0',
  "status" varchar(100) DEFAULT NULL,
  "source" varchar(100) DEFAULT NULL,
  "datecreated" datetime DEFAULT NULL,
  "uidcreated" varchar(100) DEFAULT NULL,
  "datelastupdated" datetime DEFAULT NULL,
  "uidlastupdated" varchar(100) DEFAULT NULL,
  "id" int(10) unsigned NOT NULL,
  PRIMARY KEY ("id"),
  KEY "che_calendar_id_idx" ("calendar_id"),
  KEY "che_event_id_idx" ("event_id"),
  KEY "che_status_idx" ("status")
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "calendar_has_event"
--

LOCK TABLES "calendar_has_event" WRITE;
/*!40000 ALTER TABLE "calendar_has_event" DISABLE KEYS */;
/*!40000 ALTER TABLE "calendar_has_event" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table "document"
--

DROP TABLE IF EXISTS "document";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "document" (
  "id" int(10) unsigned NOT NULL,
  "event_id" int(10) unsigned NOT NULL DEFAULT '0',
  "name" varchar(100) DEFAULT NULL,
  "url" varchar(255) DEFAULT NULL,
  PRIMARY KEY ("id")
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "document"
--

LOCK TABLES "document" WRITE;
/*!40000 ALTER TABLE "document" DISABLE KEYS */;
/*!40000 ALTER TABLE "document" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table "event"
--

DROP TABLE IF EXISTS "event";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "event" (
  "id" int(10) unsigned NOT NULL,
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "event"
--

LOCK TABLES "event" WRITE;
/*!40000 ALTER TABLE "event" DISABLE KEYS */;
/*!40000 ALTER TABLE "event" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table "event_has_eventtype"
--

DROP TABLE IF EXISTS "event_has_eventtype";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "event_has_eventtype" (
  "event_id" int(10) unsigned NOT NULL DEFAULT '0',
  "eventtype_id" int(10) unsigned NOT NULL DEFAULT '0',
  "id" int(10) unsigned NOT NULL,
  PRIMARY KEY ("id"),
  KEY "ehe_event_id_idx" ("event_id"),
  KEY "ehe_eventtype_id_idx" ("eventtype_id")
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "event_has_eventtype"
--

LOCK TABLES "event_has_eventtype" WRITE;
/*!40000 ALTER TABLE "event_has_eventtype" DISABLE KEYS */;
/*!40000 ALTER TABLE "event_has_eventtype" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table "event_has_keyword"
--

DROP TABLE IF EXISTS "event_has_keyword";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "event_has_keyword" (
  "event_id" int(10) unsigned NOT NULL DEFAULT '0',
  "keyword_id" int(10) unsigned NOT NULL DEFAULT '0',
  "id" int(10) unsigned NOT NULL,
  PRIMARY KEY ("id"),
  KEY "ehk_event_id_idx" ("event_id"),
  KEY "ehk_sponsor_id_idx" ("keyword_id")
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "event_has_keyword"
--

LOCK TABLES "event_has_keyword" WRITE;
/*!40000 ALTER TABLE "event_has_keyword" DISABLE KEYS */;
/*!40000 ALTER TABLE "event_has_keyword" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table "event_has_sponsor"
--

DROP TABLE IF EXISTS "event_has_sponsor";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "event_has_sponsor" (
  "event_id" int(10) unsigned NOT NULL DEFAULT '0',
  "sponsor_id" int(10) unsigned NOT NULL DEFAULT '0',
  "id" int(10) unsigned NOT NULL,
  PRIMARY KEY ("id"),
  KEY "ehs_event_id_idx" ("event_id"),
  KEY "ehs_sponsor_id_idx" ("sponsor_id")
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "event_has_sponsor"
--

LOCK TABLES "event_has_sponsor" WRITE;
/*!40000 ALTER TABLE "event_has_sponsor" DISABLE KEYS */;
/*!40000 ALTER TABLE "event_has_sponsor" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table "event_isopento_audience"
--

DROP TABLE IF EXISTS "event_isopento_audience";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "event_isopento_audience" (
  "event_id" int(10) unsigned NOT NULL DEFAULT '0',
  "audience_id" int(10) unsigned NOT NULL DEFAULT '0',
  "id" int(10) unsigned NOT NULL,
  PRIMARY KEY ("id"),
  KEY "eia_event_id_idx" ("event_id"),
  KEY "eia_audience_id_idx" ("audience_id")
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "event_isopento_audience"
--

LOCK TABLES "event_isopento_audience" WRITE;
/*!40000 ALTER TABLE "event_isopento_audience" DISABLE KEYS */;
/*!40000 ALTER TABLE "event_isopento_audience" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table "event_targets_audience"
--

DROP TABLE IF EXISTS "event_targets_audience";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "event_targets_audience" (
  "event_id" int(10) unsigned NOT NULL DEFAULT '0',
  "audience_id" int(10) unsigned NOT NULL DEFAULT '0',
  "id" int(10) unsigned NOT NULL,
  PRIMARY KEY ("id"),
  KEY "eta_event_id_idx" ("event_id"),
  KEY "eta_audience_id_idx" ("audience_id")
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "event_targets_audience"
--

LOCK TABLES "event_targets_audience" WRITE;
/*!40000 ALTER TABLE "event_targets_audience" DISABLE KEYS */;
/*!40000 ALTER TABLE "event_targets_audience" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table "eventdatetime"
--

DROP TABLE IF EXISTS "eventdatetime";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "eventdatetime" (
  "id" int(10) unsigned NOT NULL,
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "eventdatetime"
--

LOCK TABLES "eventdatetime" WRITE;
/*!40000 ALTER TABLE "eventdatetime" DISABLE KEYS */;
/*!40000 ALTER TABLE "eventdatetime" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table "eventtype"
--

DROP TABLE IF EXISTS "eventtype";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "eventtype" (
  "id" int(10) unsigned NOT NULL,
  "calendar_id" int(10) unsigned NOT NULL DEFAULT '0',
  "name" varchar(100) NOT NULL DEFAULT ' ',
  "description" varchar(255) DEFAULT NULL,
  "eventtypegroup" varchar(8) DEFAULT NULL,
  "standard" tinyint(1) DEFAULT '1',
  PRIMARY KEY ("id"),
  KEY "eventtype_name_idx" ("name")
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "eventtype"
--

LOCK TABLES "eventtype" WRITE;
/*!40000 ALTER TABLE "eventtype" DISABLE KEYS */;
/*!40000 ALTER TABLE "eventtype" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table "keyword"
--

DROP TABLE IF EXISTS "keyword";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "keyword" (
  "id" int(10) unsigned NOT NULL,
  "name" varchar(100) NOT NULL DEFAULT ' ',
  PRIMARY KEY ("id")
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "keyword"
--

LOCK TABLES "keyword" WRITE;
/*!40000 ALTER TABLE "keyword" DISABLE KEYS */;
/*!40000 ALTER TABLE "keyword" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table "location"
--

DROP TABLE IF EXISTS "location";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "location" (
  "id" int(10) unsigned NOT NULL,
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "location"
--

LOCK TABLES "location" WRITE;
/*!40000 ALTER TABLE "location" DISABLE KEYS */;
/*!40000 ALTER TABLE "location" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table "ongoingcheck"
--

DROP TABLE IF EXISTS "ongoingcheck";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "ongoingcheck" (
  "d" date NOT NULL,
  PRIMARY KEY ("d")
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "ongoingcheck"
--

LOCK TABLES "ongoingcheck" WRITE;
/*!40000 ALTER TABLE "ongoingcheck" DISABLE KEYS */;
/*!40000 ALTER TABLE "ongoingcheck" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table "performer"
--

DROP TABLE IF EXISTS "performer";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "performer" (
  "id" int(10) unsigned NOT NULL,
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "performer"
--

LOCK TABLES "performer" WRITE;
/*!40000 ALTER TABLE "performer" DISABLE KEYS */;
/*!40000 ALTER TABLE "performer" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table "permission"
--

DROP TABLE IF EXISTS "permission";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "permission" (
  "id" int(10) unsigned NOT NULL,
  "name" varchar(100) DEFAULT NULL,
  "description" varchar(255) DEFAULT NULL,
  PRIMARY KEY ("id")
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "permission"
--

LOCK TABLES "permission" WRITE;
/*!40000 ALTER TABLE "permission" DISABLE KEYS */;
/*!40000 ALTER TABLE "permission" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table "publiccontact"
--

DROP TABLE IF EXISTS "publiccontact";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "publiccontact" (
  "id" int(10) unsigned NOT NULL,
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "publiccontact"
--

LOCK TABLES "publiccontact" WRITE;
/*!40000 ALTER TABLE "publiccontact" DISABLE KEYS */;
/*!40000 ALTER TABLE "publiccontact" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table "relatedevent"
--

DROP TABLE IF EXISTS "relatedevent";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "relatedevent" (
  "event_id" int(10) unsigned NOT NULL DEFAULT '0',
  "related_event_id" int(10) unsigned NOT NULL DEFAULT '0',
  "relationtype" varchar(100) NOT NULL DEFAULT ' '
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "relatedevent"
--

LOCK TABLES "relatedevent" WRITE;
/*!40000 ALTER TABLE "relatedevent" DISABLE KEYS */;
/*!40000 ALTER TABLE "relatedevent" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table "role"
--

DROP TABLE IF EXISTS "role";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "role" (
  "id" int(10) unsigned NOT NULL,
  "name" varchar(255) NOT NULL DEFAULT ' ',
  "standard" tinyint(1) DEFAULT '1',
  PRIMARY KEY ("id")
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "role"
--

LOCK TABLES "role" WRITE;
/*!40000 ALTER TABLE "role" DISABLE KEYS */;
/*!40000 ALTER TABLE "role" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table "session"
--

DROP TABLE IF EXISTS "session";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "session" (
  "user_uid" varchar(255) NOT NULL DEFAULT ' ',
  "lastaction" datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  "data" longtext,
  PRIMARY KEY ("user_uid")
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "session"
--

LOCK TABLES "session" WRITE;
/*!40000 ALTER TABLE "session" DISABLE KEYS */;
/*!40000 ALTER TABLE "session" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table "sponsor"
--

DROP TABLE IF EXISTS "sponsor";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "sponsor" (
  "id" int(10) unsigned NOT NULL,
  "name" varchar(255) DEFAULT NULL,
  "standard" tinyint(1) DEFAULT '1',
  "sponsortype" varchar(255) DEFAULT NULL,
  "webpageurl" longtext,
  PRIMARY KEY ("id")
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "sponsor"
--

LOCK TABLES "sponsor" WRITE;
/*!40000 ALTER TABLE "sponsor" DISABLE KEYS */;
/*!40000 ALTER TABLE "sponsor" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table "subscription"
--

DROP TABLE IF EXISTS "subscription";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "subscription" (
  "id" int(10) unsigned NOT NULL,
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "subscription"
--

LOCK TABLES "subscription" WRITE;
/*!40000 ALTER TABLE "subscription" DISABLE KEYS */;
/*!40000 ALTER TABLE "subscription" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table "user"
--

DROP TABLE IF EXISTS "user";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "user" (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "user"
--

LOCK TABLES "user" WRITE;
/*!40000 ALTER TABLE "user" DISABLE KEYS */;
/*!40000 ALTER TABLE "user" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table "user_has_permission"
--

DROP TABLE IF EXISTS "user_has_permission";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "user_has_permission" (
  "permission_id" int(10) unsigned NOT NULL DEFAULT '0',
  "user_uid" varchar(100) NOT NULL DEFAULT ' ',
  "calendar_id" int(10) unsigned NOT NULL DEFAULT '0',
  "id" int(10) unsigned NOT NULL,
  PRIMARY KEY ("id")
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "user_has_permission"
--

LOCK TABLES "user_has_permission" WRITE;
/*!40000 ALTER TABLE "user_has_permission" DISABLE KEYS */;
/*!40000 ALTER TABLE "user_has_permission" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table "webcast"
--

DROP TABLE IF EXISTS "webcast";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "webcast" (
  "id" int(10) unsigned NOT NULL,
  "event_id" int(10) unsigned NOT NULL DEFAULT '0',
  "title" varchar(100) DEFAULT NULL,
  "status" varchar(100) DEFAULT NULL,
  "dateavailable" datetime DEFAULT NULL,
  "playertype" varchar(100) DEFAULT NULL,
  "bandwidth" varchar(255) DEFAULT NULL,
  "additionalinfo" longtext,
  PRIMARY KEY ("id")
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "webcast"
--

LOCK TABLES "webcast" WRITE;
/*!40000 ALTER TABLE "webcast" DISABLE KEYS */;
/*!40000 ALTER TABLE "webcast" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table "webcastlink"
--

DROP TABLE IF EXISTS "webcastlink";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "webcastlink" (
  "id" int(10) unsigned NOT NULL,
  "webcast_id" int(10) unsigned NOT NULL DEFAULT '0',
  "url" longtext,
  "sequencenumber" int(10) unsigned DEFAULT NULL,
  "related" varchar(1) DEFAULT 'n',
  PRIMARY KEY ("id")
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "webcastlink"
--

LOCK TABLES "webcastlink" WRITE;
/*!40000 ALTER TABLE "webcastlink" DISABLE KEYS */;
/*!40000 ALTER TABLE "webcastlink" ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2010-03-17 13:25:54
