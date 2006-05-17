use eventcal;

CREATE TABLE IF NOT EXISTS tblAccounts (
	pkAccountID		INT UNSIGNED NOT NULL AUTO_INCREMENT,
	strAccountName 	VARCHAR(100),
	strAcctNameShort VARCHAR(6),
	strStreetAddress1 VARCHAR(100),
	strStreetAddress2 VARCHAR(100),
	strCity VARCHAR(100),
	strState VARCHAR(2),
	strZip VARCHAR(10),
	strPhone VARCHAR(25),
	strFax VARCHAR(25),
	strEmail VARCHAR(100),
	strUserID VARCHAR(12),
	strPassword VARCHAR (10),
	strUserFirstName VARCHAR (100),
	strUserLastName VARCHAR (100),
	strUserContactPhone VARCHAR (25),
	strUserContactEmail VARCHAR (100),
	PRIMARY KEY (pkAccountID));
	
INSERT INTO tblAccounts (strAccountName, strAcctNameShort, strStreetAddress1, strStreetAddress2, strCity, strState, strZip, strPhone, strEmail, strUserID, strPassword, strUserFirstName, strUserLastName, strUserContactPhone, strUserContactEmail)
VALUES ("Biology","Bio", "University of California", "201 Campbell Hall", "Berkeley", "CA", "94720-2920", "510-642-4487", "biology@berkeley.edu", "biology", "biology", "James", "Elliott", "510-642-1071", "jamese@berkeley.edu");

INSERT INTO tblAccounts (strAccountName, strAcctNameShort, strStreetAddress1, strStreetAddress2, strCity, strState, strZip, strPhone, strFax, strEmail, strUserID, strPassword, strUserFirstName, strUserLastName, strUserContactPhone, strUserContactEmail)
VALUES ("Mechanical Engineering", "ME", "University of California", "Mail Code 1740", "Berkeley", "CA", "94720-1740", "510-642-1338", "510-642-6163", "mecheng@berkeley.edu", "me", "me", "Harriet", "Jones", "510-642-8637", "harrietj@berkeley.edu");

INSERT INTO tblAccounts (strAccountName, strAcctNameShort, strStreetAddress1, strStreetAddress2, strCity, strState, strZip, strPhone, strFax, strEmail, strUserID, strPassword, strUserFirstName, strUserLastName, strUserContactPhone, strUserContactEmail)
VALUES ("Electrical Engineering and Computer Science", "EECS", "University of California", "253 Cory Hall", "Berkeley", "CA", "94720-1770", "510-642-3214", "510-642-7846", "eecs@berkeley.edu", "eecs", "eecs", "Mary", "Townsend", "510-642-8648", "maryt@berkeley.edu");

INSERT INTO tblAccounts (strAccountName, strAcctNameShort, strStreetAddress1, strStreetAddress2, strCity, strState, strZip, strPhone, strFax, strEmail, strUserID, strPassword, strUserFirstName, strUserLastName, strUserContactPhone, strUserContactEmail)
VALUES ("School of Information Management & Systems", "SIMS", "University of California", "102 South Hall", "Berkeley", "CA", "94720-4600", "510-642-1464", "510-642-5814", "info@sims.berkeley.edu", "sims","sims", "Kevin", "Heard", "510-642-6363", "kevin@sims.berkeley.edu");

INSERT INTO tblAccounts (strAccountName, strAcctNameShort, strStreetAddress1, strStreetAddress2, strCity, strState, strZip, strPhone, strFax, strEmail, strUserID, strPassword, strUserFirstName, strUserLastName, strUserContactPhone, strUserContactEmail)
VALUES ("BioEngineering", "BioEng", "University of California", "459 Evans Hall #1762", "Berkeley", "CA", "94720-1762", "510-642-5833", "510-642-5235", "bioeng@berkeley.edu", "bioeng", "bioeng", "Davis", "Smith", "510-642-1829", "dsmith@berkeley.edu");

INSERT INTO tblAccounts (strAccountName, strAcctNameShort, strUserID, strPassword)
VALUES ("Environmental Science", "EnvSci", "envsci","envsci");

INSERT INTO tblAccounts (strAccountName, strUserID, strPassword)
VALUES ("Civil & Environmental Engineering","civenveng","civenveng");

INSERT INTO tblAccounts (strAccountName, strUserID, strPassword)
VALUES ("e-Berkeley","eberkeley","eberkeley");

INSERT INTO tblAccounts (strAccountName, strUserID, strPassword)
VALUES ("Graduate School of Journalism","journ","journ");

CREATE TABLE IF NOT EXISTS tblAccountEvents(
	fkAccountID 	INT UNSIGNED NOT NULL,
	fkEventID 		INT UNSIGNED NOT NULL,
	strStatus 		VARCHAR(20),
	strSource		VARCHAR(50),
	dateReceived	TIMESTAMP,
	PRIMARY KEY (fkAccountID,fkEventID));
	
	
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (1,2,'posted','Internal Form');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (1,3,'posted','External Form');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (1,6,'pending','Recommendation');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (1,12,'posted','External Form');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (1,16,'pending','Subscription');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (1,17,'posted','Subscription');

INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (2,4,'posted','Internal Form');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (2,1,'pending','External Form');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (2,2,'posted','Subscription');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (2,6,'pending','External Form');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (2,3,'posted','External Form');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (2,7,'pending','Recommendation');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (2,8,'pending','Subscription');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (2,9,'pending','Subscription');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (2,10,'posted','Recommendation');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (2,26,'archived','External Form');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (2,27,'archived','Search');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (2,28,'archived','Subscription');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (2,29,'pending','Internal Form');

INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (3,1,'posted','External Form');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (3,5,'pending','Internal Form');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (3,3,'pending','Subscription');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (3,2,'posted','Subscription');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (3,4,'posted','Subscription');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (3,6,'posted','Subscription');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (3,7,'posted','Internal Form');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (3,8,'posted','External Form');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (3,23,'posted','External Form');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (3,24,'posted','Internal Form');

INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (4,1,'pending','Recommendation');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (4,9,'posted','Internal Form');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (4,18,'archived','External Form');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (4,19,'posted','Internal Form');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (4,20,'posted','Internal Form');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (4,21,'pending','External Form');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (4,22,'posted','External Form');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (4,23,'pending','Subscription');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (4,24,'pending','External Form');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (4,25,'pending','Recommendation');

INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (5,10,'posted','External Form');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (5,11,'posted','Internal Form');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (5,12,'posted','External Form');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (5,13,'posted','Internal Form');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (5,14,'posted','Subscription');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (5,15,'posted','Subscription');

INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (6,11,'pending','Subscription');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (6,17,'posted','Internal Form');
INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (6,22,'posted','Recommendation');

INSERT INTO tblAccountEvents (fkAccountID, fkEventID, strStatus, strSource)
VALUES (8,25,'posted','Internal Form');


CREATE TABLE IF NOT EXISTS tblEventTypes(
	pkEventTypeID	INT UNSIGNED NOT NULL AUTO_INCREMENT,
	strEventType	VARCHAR(50),
	PRIMARY KEY (pkEventTypeID));
	
INSERT INTO tblEventTypes (strEventType) VALUES ('Seminar');
INSERT INTO tblEventTypes (strEventType) VALUES ('Lecture');
INSERT INTO tblEventTypes (strEventType) VALUES ('Performance');
INSERT INTO tblEventTypes (strEventType) VALUES ('Meeting');
INSERT INTO tblEventTypes (strEventType) VALUES ('Workshop');
INSERT INTO tblEventTypes (strEventType) VALUES ('Panel');

CREATE TABLE IF NOT EXISTS tblLocations(
	pkLocationID		INT UNSIGNED NOT NULL AUTO_INCREMENT,
	strLocationType		VARCHAR(20),
	strLocationName		TEXT,
	strStreet		TEXT,
	strCity			TEXT,
	strState		VARCHAR(2),
	strZip			VARCHAR(5),
	strMapURL		TEXT,
	PRIMARY KEY (pkLocationID));
	
INSERT INTO tblLocations (strLocationType,strLocationName) VALUES ('oncampus','Alumni House');
INSERT INTO tblLocations (strLocationType,strLocationName) VALUES ('oncampus','Anna Head Lot');
INSERT INTO tblLocations (strLocationType,strLocationName) VALUES ('oncampus','Anthony Hall');
INSERT INTO tblLocations (strLocationType,strLocationName) VALUES ('oncampus','Barrows Hall');
INSERT INTO tblLocations (strLocationType,strLocationName) VALUES ('oncampus','Campanile Esplanade');
INSERT INTO tblLocations (strLocationType,strLocationName) VALUES ('oncampus','Etcheverry Hall');
INSERT INTO tblLocations (strLocationType,strLocationName) VALUES ('oncampus','South Hall');
INSERT INTO tblLocations (strLocationType,strLocationName) VALUES ('oncampus','Soda Hall');
INSERT INTO tblLocations (strLocationType,strLocationName) VALUES ('oncampus','Sproul Plaza');
INSERT INTO tblLocations (strLocationType,strLocationName) VALUES ('oncampus','Evans Hall');
INSERT INTO tblLocations (strLocationType,strLocationName) VALUES ('oncampus','Wheeler');
INSERT INTO tblLocations (strLocationType,strLocationName) VALUES ('oncampus','North Gate Library');


CREATE TABLE IF NOT EXISTS tblRoles(
	pkRoleID	INT UNSIGNED NOT NULL AUTO_INCREMENT,
	strRole		VARCHAR(50),
	PRIMARY KEY (pkRoleID));
	
INSERT INTO tblRoles (strRole) VALUES ('Speaker');
INSERT INTO tblRoles (strRole) VALUES ('Discussant');
INSERT INTO tblRoles (strRole) VALUES ('Performer');

CREATE TABLE IF NOT EXISTS tblEventParticipants(
	pkEventParticipantID	INT UNSIGNED NOT NULL AUTO_INCREMENT,
	fkEventID		INT UNSIGNED NOT NULL,
	fkRoleID		INT UNSIGNED NOT NULL,
	strFirstName	TEXT,
	strLastName		TEXT,
	strNameURL		TEXT,
	strTitle		TEXT,
	strAffiliation	TEXT,
	strTelephone	VARCHAR(13),
	strEmail		TEXT,
	strAddress		TEXT,
	PRIMARY KEY (pkEventParticipantID));

CREATE TABLE IF NOT EXISTS tblEventSponsors(
	pkEventSponsorID	INT UNSIGNED NOT NULL AUTO_INCREMENT,
	fkEventID		INT UNSIGNED NOT NULL,
	strSponsorName	TEXT,
	strSponsorURL	TEXT,
	strGraphic		TEXT,
	strDescription	TEXT,
	PRIMARY KEY (pkEventSponsorID));

CREATE TABLE IF NOT EXISTS tblEvents(
	pkEventID			INT UNSIGNED NOT NULL AUTO_INCREMENT,
	strNetworkStatus	VARCHAR(8),
	strTitle			TEXT,
	strSubtitle			TEXT,
	fkEventTypeID		INT,
	eventDate			DATE,
	startTime			TIME,
	endTime				TIME,
	fkLocationID		INT,
	strRoomInfo			TEXT,
	strLocAddInfo		TEXT,
	bitLinkToMap		CHAR,
	strDescription		TEXT,
	strEventURL			TEXT,
	strImage			TEXT,
	strRefresh			TEXT,
	strPubContactName	TEXT,
	strPubContactPhone	VARCHAR(13),
	strPubContactEmail	TEXT,
	tmpCreated		TIMESTAMP,
	fkEventOwnerID	INT UNSIGNED NOT NULL,
	PRIMARY KEY (pkEventID));
	
INSERT INTO tblEvents(
	strNetworkStatus,
	strTitle,
	fkEventTypeID,
	eventDate,
	startTime,
	endTime,
	fkLocationID,
	strRoomInfo,
	strDescription,
	fkEventOwnerID)
VALUES (
	'public',
	'Improving Software Performance with Configurable Logic',
	2,
	'2004-12-13',
	'15:30',
	'16:30',
	4,
	'Rm 120',
	'Find out the latest state of the art methods of improving software performance with configurable logic from experts in the field.',
	3
);

INSERT INTO tblEvents(
	strNetworkStatus,
	strTitle,
	strSubtitle,
	fkEventTypeID,
	eventDate,
	startTime,
	endTime,
	fkLocationID,
	strRoomInfo,
	strDescription,
	fkEventOwnerID)
VALUES (
	'public',
	'Spring 2004 Seminar Series - Molecular and Cell Biology',
	'Genetic Models of Disease',
	1,
	'2004-11-30',
	'16:00',
	'17:30',
	5,
	'Rm 1250',
	'This is a description. This is a description. This is a description. This is a description. This is a description. This is a description. This is a description. This is a description.',
	1
);


INSERT INTO tblEvents(
	strNetworkStatus, 
	strTitle,
	fkEventTypeID,
	eventDate,
	startTime,
	endTime,
	fkLocationID,
	strRoomInfo,
	strDescription,
	fkEventOwnerID)
VALUES (
	'public',
	'Chromatin Structure',
	2,
	'2004-12-07',
	'09:00',
	'10:00',
	8,
	'Rm 310',
	'This is a description. This is a description. This is a description. This is a description. This is a description.',
	1
);

INSERT INTO tblEvents(
	strNetworkStatus, 
	strTitle,
	fkEventTypeID,
	eventDate,
	startTime,
	endTime,
	fkLocationID,
	strRoomInfo,
	strDescription,
	fkEventOwnerID)
VALUES (
	'public',
	'Fatigue of Aircraft Riveted Joints',
	2,
	'2004-11-30',
	'12:30',
	'13:30',
	9,
	'Rm 101',
	'This is a description. This is a description. This is a description. This is a description. This is a description. This is a description. This is a description. This is a description. This is a description. This is a description. This is a description.',
	2
);

INSERT INTO tblEvents(
	strNetworkStatus, 
	strTitle,
	fkEventTypeID,
	eventDate,
	startTime,
	endTime,
	fkLocationID,
	strRoomInfo,
	strDescription,
	fkEventOwnerID)
VALUES (
	'public',
	'Layering and Resilience in IP/Optical Networks',
	1,
	'2004-12-09',
	'14:00',
	'15:00',
	5,
	'Rm 280',
	'This is a description. This is a description. This is a description.',
	3
);

INSERT INTO tblEvents(
	strNetworkStatus, 
	strTitle,
	fkEventTypeID,
	eventDate,
	startTime,
	endTime,
	fkLocationID,
	strRoomInfo,
	strDescription,
	fkEventOwnerID)
VALUES (
	'public',
	'ME Grad Visiting Day',
	1,
	'2004-11-18',
	'15:00',
	'17:00',
	5,
	'Rm 280',
	'Come and visit the Mechanical Engineering School and the students who will be graduating.',
	2
);

INSERT INTO tblEvents(
	strNetworkStatus, 
	strTitle,
	fkEventTypeID,
	eventDate,
	startTime,
	endTime,
	fkLocationID,
	strRoomInfo,
	strDescription,
	fkEventOwnerID)
VALUES (
	'public',
	'Secrecy Key Generation and Multiterminal Source Coding',
	1,
	'2004-12-01',
	'13:30',
	'14:30',
	5,
	'Rm 250',
	'This is a description. This is a description. This is a description. This is a description. This is a description. This is a description. This is a description. This is a description. This is a description. This is a description. This is a description.',
	3
);

INSERT INTO tblEvents(
	strNetworkStatus, 
	strTitle,
	fkEventTypeID,
	eventDate,
	startTime,
	endTime,
	fkLocationID,
	strRoomInfo,
	strDescription,
	fkEventOwnerID)
VALUES (
	'public',
	'Macromolecular Simulation: A Computational Perspective',
	2,
	'2004-12-23',
	'10:00',
	'11:30',
	5,
	'Rm 364',
	'This is a description. This is a description. This is a description. This is a description. This is a description. This is a description. This is a description. This is a description. This is a description. This is a description. This is a description.',
	3
);

INSERT INTO tblEvents(
	strNetworkStatus, 
	strTitle,
	fkEventTypeID,
	eventDate,
	startTime,
	endTime,
	fkLocationID,
	strRoomInfo,
	strDescription,
	fkEventOwnerID)
VALUES (
	'public',
	'Guidant Information Meeting',
	4,
	'2004-12-06',
	'16:30',
	'14:30',
	4,
	'Rm 110',
	'This is a description. This is a description. This is a description. This is a description. This is a description. This is a description. This is a description. This is a description. This is a description. This is a description. This is a description.',
	4
);

INSERT INTO tblEvents(
	strNetworkStatus, 
	strTitle,
	fkEventTypeID,
	eventDate,
	startTime,
	endTime,
	fkLocationID,
	strRoomInfo,
	strDescription,
	fkEventOwnerID)
VALUES (
	'public',
	'Conquest of Bread: 150 Years of CA Agribusiness',
	2,
	'2004-11-29',
	'16:00',
	'17:00',
	3,
	'Rm 110',
	'This is a description. This is a description. This is a description. This is a description. This is a description. This is a description. This is a description. This is a description. This is a description. This is a description. This is a description.',
	5
);

INSERT INTO tblEvents(
	strNetworkStatus, 
	strTitle,
	fkEventTypeID,
	eventDate,
	startTime,
	endTime,
	fkLocationID,
	strRoomInfo,
	strDescription,
	fkEventOwnerID)
VALUES (
	'public',
	'Environmental Conservation/Wildlife Seminar',
	1,
	'2004-11-16',
	'10:00',
	'11:00',
	9,
	'Rm 226',
	'This is a description. This is a description. This is a description. This is a description. This is a description. This is a description. This is a description. This is a description. This is a description. This is a description. This is a description.',
	5
);

INSERT INTO tblEvents(
	strNetworkStatus, 
	strTitle,
	fkEventTypeID,
	eventDate,
	startTime,
	endTime,
	fkLocationID,
	strRoomInfo,
	strDescription,
	fkEventOwnerID)
VALUES (
	'public',
	'Animals and Ecology: Factory Farming and the Trouble with Veganism',
	1,
	'2004-11-17',
	'15:00',
	'16:00',
	3,
	'Rm 110',
	' People aiming to clarify and deepen their opposition to contemporary abuse of animals and nature in factory farming face an important set of choices in philosophical theory.', 
	5
);

INSERT INTO tblEvents(
	strNetworkStatus, 
	strTitle,
	fkEventTypeID,
	eventDate,
	startTime,
	endTime,
	fkLocationID,
	strRoomInfo,
	strDescription,
	fkEventOwnerID)
VALUES (
	'public',
	'Forests With a Future: Planning & Managing the Environment of the National Forests of the Sierra Nevada',
	1,
	'2004-11-18',
	'9:00',
	'10:00',
	3,
	'Rm 110',
	'How to plan for the long term in the alpine forests of Northern California.', 
	5
);

INSERT INTO tblEvents(
	strNetworkStatus, 
	strTitle,
	fkEventTypeID,
	eventDate,
	startTime,
	endTime,
	fkLocationID,
	strRoomInfo,
	strDescription,
	fkEventOwnerID)
VALUES (
	'public',
	'Do not Let Sleeping Dogmas Lie: Novel Concepts in Reproductive Biology',
	1,
	'2004-11-16',
	'9:00',
	'10:00',
	5,
	'Rm 250',
	'Reproductive Biology is experiencing new advances every day. Learn about all of them at this exciting seminar!', 
	1
);

INSERT INTO tblEvents(
	strNetworkStatus, 
	strTitle,
	fkEventTypeID,
	eventDate,
	startTime,
	endTime,
	fkLocationID,
	strRoomInfo,
	strDescription,
	fkEventOwnerID)
VALUES (
	'public',
	'Genetic Approaches to the Problem of Sleep',
	1,
	'2004-11-26',
	'9:00',
	'10:00',
	5,
	'Rm 250',
	'Having problems sleeping? Well, we have just the answer for you...it is because of your mother!', 
	1
);

INSERT INTO tblEvents(
	strNetworkStatus, 
	strTitle,
	fkEventTypeID,
	eventDate,
	startTime,
	endTime,
	fkLocationID,
	strRoomInfo,
	strDescription,
	fkEventOwnerID)
VALUES (
	'public',
	'1-g Small Scale Model Slope Test',
	1,
	'2004-12-02',
	'9:00',
	'10:00',
	4,
	'Rm 155',
	'Seismic slope stability has been an important topic in geotechnical earthquake engineering for many years. Seismic stability of slopes and embankments is typically assessed by (1) conducting pseudostatic analyses, which will provide a stability parameter (e.g. factor of safety) and by (2) estimating permanent deformations induced by earthquake shaking.',
	6
);

INSERT INTO tblEvents(
	strNetworkStatus, 
	strTitle,
	fkEventTypeID,
	eventDate,
	startTime,
	endTime,
	fkLocationID,
	strRoomInfo,
	strDescription,
	fkEventOwnerID)
VALUES (
	'public',
	'Dehalococcoides: Strange Bugs for Dirty Jobs',
	1,
	'2004-11-29',
	'12:00',
	'13:00',
	4,
	'Rm 155',
	'If you have a dirty job, you can bet that a dehalococcoide is the answer.',
	6
);

INSERT INTO tblEvents(
	strNetworkStatus, 
	strTitle,
	eventDate,
	startTime,
	endTime,
	fkLocationID,
	strDescription,
	fkEventOwnerID)
VALUES (
	'public',
	'SIMS Graduation Ceremony',
	'2004-05-15',
	'13:00',
	'14:00',
	5,
	'Commencement Speaker: Peter Brown, Head of Information Resources Management, European Parliament.',
	4
);

INSERT INTO tblEvents(
	strNetworkStatus, 
	strTitle,
	eventDate,
	startTime,
	endTime,
	fkLocationID,
	strRoomInfo,
	strDescription,
	strEventURL,
	fkEventOwnerID)
VALUES (
	'private',
	'Final Project Presentations: Round 1',
	'2004-12-09',
	'13:00',
	'17:30',
	7,
	'Rm 202',
	"Come hear the IS213 students present their final projects!",
	"http://sims.berkeley.edu/academics/masters/final_project/projects04.html",
	4
);

INSERT INTO tblEvents(
	strNetworkStatus, 
	strTitle,
	eventDate,
	startTime,
	endTime,
	fkLocationID,
	strRoomInfo,
	strDescription,
	strEventURL,
	fkEventOwnerID)
VALUES (
	'private',
	'Final Project Presentations: Round 1',
	'2004-12-10',
	'12:30',
	'17:30',
	7,
	'Rm 202',
	"Come hear the IS213 students present their final projects!",
	"http://sims.berkeley.edu/academics/masters/final_project/projects04.html",
	4
);

INSERT INTO tblEvents(
	strNetworkStatus, 
	strTitle,
	eventDate,
	startTime,
	endTime,
	fkLocationID,
	strRoomInfo,
	strDescription,
	strEventURL,
	fkEventOwnerID)
VALUES (
	'public',
	'Final Project Presentations: Round 2',
	'2004-12-13',
	'10:30',
	'13:20',
	7,
	'Rm 202',
	"The five best IS213 projects are presented again to a team of outside judges.",
	"http://sims.berkeley.edu/academics/masters/final_project/projects04.html",
	4
);

INSERT INTO tblEvents(
	strNetworkStatus, 
	strTitle,
	strSubtitle,
	eventDate,
	startTime,
	endTime,
	fkEventTypeID,
	fkLocationID,
	strRoomInfo,
	strDescription,
	fkEventOwnerID)
VALUES (
	'public',
	'CDE Lecture',
	'XML: Batteries Not Included',
	'2004-11-18',
	'16:00',
	'17:00',
	2,
	7,
	'Rm 202',
	"Peter Brown of the European Parliament presents.",
	4
);

INSERT INTO tblEvents(
	strNetworkStatus, 
	strTitle,
	eventDate,
	startTime,
	endTime,
	fkEventTypeID,
	fkLocationID,
	strRoomInfo,
	strDescription,
	fkEventOwnerID)
VALUES (
	'public',
	'User Interfaces for Privacy: Design and Evaluation of the AT&T Privacy Bird P3P User Agent',
	'2004-12-20',
	'17:30',
	'19:00',
	1,
	7,
	'Rm 202',
	"The Platform for Privacy Preferences (P3P), developed by the World
Wide Web Consortium (W3C), provides a standard computer-readable (XML)
format for privacy policies and a protocol that enables web browsers
to read and process privacy policies automatically P3P has been built
into the Internet Explorer 6 and Netscape 7 web browsers and has been
adopted by about a third of the top 100 web sites. We developed the
AT&T Privacy Bird (http://privacybird.com) initially as an Internet
Explorer add-on that can compare P3P policies against a user's privacy
preferences and display a bird icon to indicate whether the user's
preferences are met. More recently we have integrated Privacy Bird
into a search engine front end.",
	3
);

INSERT INTO tblEvents(
	strNetworkStatus, 
	strTitle,
	eventDate,
	startTime,
	endTime,
	fkEventTypeID,
	fkLocationID,
	strRoomInfo,
	strDescription,
	fkEventOwnerID)
VALUES (
	'public',
	'Tangible User Interface Input: Tools and Techniques',
	'2004-12-17',
	'16:00',
	'17:25',
	1,
	8,
	'Rm 306',
	"Tangible user interfaces (TUIs) augment the physical world by integrating digital information with everyday physical objects. Developing tangible interfaces is challenging because programmers are responsible for acquiring and abstracting physical input. This is difficult, time-consuming, and requires a high level of technical expertise, especially with computer vision. To address this, we created Papier-Mache, a toolkit for building tangible interfaces using computer vision, electronic tags, and barcodes. Papier-Mache introduces high-level abstractions for working with these technologies that facilitate technology portability. For example, an application can be prototyped with computer vision and deployed with RFID. The design of Papier-Mache has been deeply influenced by his experiences building physical interfaces over the past several years, specifically The Designers' Outpost and Books with Voices. The Designers' Outpost is a tangible user interface combining the affordances of paper and large physical workspaces with the advantages of electronic media to support information design. With Outpost, users collaboratively author web site information architectures on an electronic whiteboard using physical media (Post-it notes and images). Books with Voices, based on his contextual inquiry into the practices of oral historians, provides barcode augmented paper transcripts for fast, random access to digital video interviews on a PDA.",
	3
);

INSERT INTO tblEvents(
	strNetworkStatus, 
	strTitle,
	eventDate,
	startTime,
	endTime,
	fkEventTypeID,
	fkLocationID,
	strRoomInfo,
	strDescription,
	fkEventOwnerID)
VALUES (
	'public',
	'e-Berkeley Symposium',
	'2004-12-10',
	'14:00',
	'17:00',
	1,
	8,
	'Rm 306',
	"",
	8
);

INSERT INTO tblEvents(
	strNetworkStatus, 
	strTitle,
	strSubtitle,
	eventDate,
	startTime,
	endTime,
	fkEventTypeID,
	fkLocationID,
	strDescription,
	fkEventOwnerID)
VALUES (
	'public',
	'Revisiting Virtual Communities',
	"The Internet's Impact on Society and Politics",
	'2004-11-11',
	'9:00',
	'10:15',
	6,
	12,
	"A panel discussion with Criag Newmark of craigslist, Markos Moulitsas Zuniga of the Daily Kos Weblog, Mark Pincus of Tribe Networks and Susan Mernit of the Navigating the Info Jungle Weblog on how the Internet is changing social interaction and political activism.",
	9
);

INSERT INTO tblEvents(
	strNetworkStatus, 
	strTitle,
	strSubtitle,
	eventDate,
	startTime,
	endTime,
	fkEventTypeID,
	fkLocationID,
	strDescription,
	fkEventOwnerID)
VALUES (
	'public',
	'Multimedia and Online Publishing Presentations',
	'Multimedia Reporting &amp; Convergence Workshop',
	'2004-11-11',
	'12:45',
	'20:30',
	5,
	12,
	"The J-School is hosting seven presentations by online publishing experts on topics like doing multimedia reporting projects, revenue strategies for online newspapers, the daily routines of Internet users and journalism Weblogs.",
	9
);

INSERT INTO tblEvents(
	strNetworkStatus, 
	strTitle,
	strSubtitle,
	eventDate,
	startTime,
	endTime,
	fkEventTypeID,
	fkLocationID,
	strDescription,
	fkEventOwnerID)
VALUES (
	'public',
	'A Conversation Around Mainstreaming CSR',
	'Can it be done?',
	'2004-11-15',
	'17:30',
	'20:00',
	5,
	12,
	"The second of only three events that the Center for Responsible Business will host this year, Michael Lewis,author of several best selling books, including Liar's Poker (#1 NYT National Bestseller!) and a contributing writer to the New York Times Magazine will be moderating a panel discussion.",
	9
);

INSERT INTO tblEvents(
	strNetworkStatus, 
	strTitle,
	eventDate,
	startTime,
	endTime,
	fkEventTypeID,
	fkLocationID,
	strDescription,
	fkEventOwnerID)
VALUES (
	'public',
	'The Science and the Industry of Shape Memory Alloys',
	'2004-11-20',
	'16:30',
	'19:00',
	5,
	6,
	"The mechanism of shape memory in Nitinol will be briefly introduced, followed by a survey of how the shape memory and superelastic effects have been applied in products. The emphasis will be on superelastic medical applications, with discussions on why Nitinol has had such a large impact on medical product design. Finally, examples of patterned three-dimensional thin films will be given with examples such as stent coverings, heart valves, high pressure balloons and distal protection filters.",
	2
);