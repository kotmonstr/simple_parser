-- MySQL dump 10.13  Distrib 5.5.44, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: corbizrealestate
-- ------------------------------------------------------
-- Server version	5.5.44-0ubuntu0.14.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


DROP TABLE IF EXISTS `houses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `houses` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `User_ID` int(10) unsigned NOT NULL DEFAULT '0',
  `Agent_ID` int(10) unsigned NOT NULL DEFAULT '0',
  `Ownership` tinyint(3) unsigned DEFAULT NULL,
  `OfferType` tinyint(3) unsigned DEFAULT NULL,
  `Status` tinyint(1) unsigned DEFAULT NULL,
  `Zip` varchar(10) DEFAULT NULL,
  `Address` varchar(250) DEFAULT NULL,
  `City` varchar(50) DEFAULT NULL,
  `County` varchar(50) DEFAULT NULL,
  `State` varchar(2) DEFAULT NULL,
  `Country` varchar(2) DEFAULT NULL,
  `Price` float(10,2) DEFAULT NULL,
  `ValueOfSize` enum('sqft','sqm','month','acres','unit') DEFAULT NULL,
  `BuildingSize` float(10,5) NOT NULL DEFAULT '0.00000',
  `BuildingClass` enum('A','B','C') DEFAULT NULL,
  `LotSize` float(15,5) NOT NULL DEFAULT '0.00000',
  `Description` text,
  `YearBuilt` int(4) unsigned NOT NULL DEFAULT '0',
  `YearRenovated` int(4) unsigned DEFAULT NULL,
  `APN` varchar(250) DEFAULT NULL,
  `MLSID` varchar(20) DEFAULT NULL,
  `DateAdded` datetime DEFAULT NULL,
  `DateUpdated` datetime DEFAULT NULL,
  `Source` int(4) unsigned NOT NULL DEFAULT '0',
  `ExternalID` varchar(32) DEFAULT NULL,
  `ExternalChecksum` varchar(32) DEFAULT NULL,
  `ExternalSourceID` int(10) unsigned DEFAULT NULL,
  `Rooms` int(5) unsigned NOT NULL,
  `Beds` int(5) unsigned NOT NULL DEFAULT '0',
  `Baths` int(5) unsigned NOT NULL DEFAULT '0',
  `Basement` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `Heating` varchar(250) DEFAULT NULL,
  `Sewage` varchar(250) DEFAULT NULL,
  `Water` varchar(250) DEFAULT NULL,
  `ParkingAvailable` tinyint(1) NOT NULL DEFAULT '0',
  `ParkingReserved` tinyint(1) DEFAULT '0',
  `ParkingSpaces` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ParkingType` varchar(250) DEFAULT NULL,
  `Garage` varchar(250) DEFAULT NULL,
  `GarageSpaces` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `SwimmingPool` varchar(250) DEFAULT NULL,
  `Floors` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `Backyard` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `AirConditioning` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `Published` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `BuildingName` varchar(250) DEFAULT NULL,
  `SubmarketID` int(10) unsigned DEFAULT NULL,
  `MSA` varchar(250) DEFAULT NULL,
  `Zoning` varchar(250) DEFAULT NULL,
  `Buildings` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `Tenancy` enum('Multiple Tenants','Single Tenant') DEFAULT NULL,
  `BroadbandInternet` varchar(250) DEFAULT NULL,
  `Market` varchar(250) DEFAULT NULL,
  `Services` text,
  `OwnerID` int(10) unsigned DEFAULT NULL,
  `AgentsInfo` text,
  `Contact` varchar(250) DEFAULT NULL,
  `Sold` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `LivingArea` float(10,5) DEFAULT NULL,
  `StatusType` enum('Foreclosure/Bank Owned','For Sale By Owner','Short Sale') DEFAULT NULL,
  `Patio` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `FirePlace` varchar(250) DEFAULT NULL,
  `Roof` varchar(250) DEFAULT NULL,
  `Pool` varchar(250) DEFAULT NULL,
  `Style` varchar(250) DEFAULT NULL,
  `HOA` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `HoaFees` float(10,2) DEFAULT NULL,
  `HoaFeesType` enum('Annually','Monthly','Quarterly') DEFAULT NULL,
  `Appliances` varchar(250) DEFAULT NULL,
  `DbAddedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ExternalID` (`ExternalID`),
  KEY `User_ID` (`User_ID`),
  KEY `Agent_ID` (`Agent_ID`),
  KEY `OfferType` (`OfferType`),
  KEY `Status` (`Status`),
  KEY `State` (`State`),
  KEY `DateUpdated` (`DateUpdated`),
  KEY `SubmarketID` (`SubmarketID`),
  KEY `Published` (`Published`),
  KEY `Ownership` (`Ownership`),
  KEY `Sold` (`Sold`)
) ENGINE=InnoDB AUTO_INCREMENT=1051058 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `houses_constructions`
--

DROP TABLE IF EXISTS `houses_constructions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `houses_constructions` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `House_ID` int(10) unsigned NOT NULL,
  `Construction_ID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `House_ID` (`House_ID`),
  KEY `Construction_ID` (`Construction_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `houses_floors`
--

DROP TABLE IF EXISTS `houses_floors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `houses_floors` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `House_ID` int(10) unsigned NOT NULL,
  `Floor` varchar(20) CHARACTER SET latin1 NOT NULL,
  `Size` float(10,5) DEFAULT NULL,
  `Min` float(10,5) DEFAULT NULL,
  `Max` float(10,5) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `House_ID` (`House_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `houses_hits`
--

DROP TABLE IF EXISTS `houses_hits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `houses_hits` (
  `IP` varchar(50) NOT NULL,
  `House_ID` int(10) unsigned NOT NULL,
  `RequestDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`IP`,`House_ID`) USING BTREE,
  KEY `House_ID` (`House_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `houses_owners`
--

DROP TABLE IF EXISTS `houses_owners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `houses_owners` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `houses_sewer`
--

DROP TABLE IF EXISTS `houses_sewer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `houses_sewer` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `House_ID` int(10) unsigned NOT NULL,
  `Sewer_ID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Index_House_ID` (`House_ID`),
  KEY `Index_Sewer_ID` (`Sewer_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `houses_simages`
--

DROP TABLE IF EXISTS `houses_simages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `houses_simages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `house_id` int(11) NOT NULL,
  `house_image_path` text,
  `status` varchar(45) NOT NULL,
  `mongo_id` varchar(254) NOT NULL,
  PRIMARY KEY (`id`,`status`,`house_id`,`mongo_id`),
  UNIQUE KEY `mongo_id_UNIQUE` (`mongo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1795411 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `houses_spaces`
--

DROP TABLE IF EXISTS `houses_spaces`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `houses_spaces` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `House_ID` int(11) NOT NULL,
  `User_ID` int(10) unsigned NOT NULL,
  `Price` float(7,2) NOT NULL DEFAULT '0.00',
  `SpaceOfferType` tinyint(1) unsigned NOT NULL,
  `SpacePricePerSize` enum('sfy','sfm','sqmy','sqmm','amo','ayr') DEFAULT NULL,
  `LeaseType` tinyint(1) DEFAULT NULL,
  `DateAvailable` date DEFAULT NULL,
  `Rooms` int(5) DEFAULT NULL,
  `Beds` int(5) DEFAULT NULL,
  `Baths` int(5) DEFAULT NULL,
  `Floor` int(5) DEFAULT NULL,
  `AirConditioning` tinyint(1) DEFAULT NULL,
  `OccupancyStatus` tinyint(1) NOT NULL DEFAULT '0',
  `Description` text,
  `Size` float(10,5) DEFAULT NULL,
  `MinDivisible` float(10,5) DEFAULT NULL,
  `MaxContiguous` float(10,5) DEFAULT NULL,
  `DateAdded` datetime NOT NULL,
  `ExternalHash` varchar(32) DEFAULT NULL,
  `Deleted` tinyint(1) NOT NULL DEFAULT '0',
  `LeaseTerm` varchar(100) DEFAULT NULL,
  `ConferenceRooms` int(2) unsigned DEFAULT NULL,
  `Contact` varchar(250) DEFAULT NULL,
  `Suite` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `House_ID` (`House_ID`),
  KEY `User_ID` (`User_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `houses_spaces_types`
--

DROP TABLE IF EXISTS `houses_spaces_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `houses_spaces_types` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Space_ID` int(10) unsigned NOT NULL DEFAULT '0',
  `Type_ID` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `Space_ID` (`Space_ID`),
  KEY `Type_ID` (`Type_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `houses_spintext`
--

DROP TABLE IF EXISTS `houses_spintext`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `houses_spintext` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `house_id` int(11) DEFAULT NULL,
  `spin_text` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `house_id` (`house_id`)
) ENGINE=InnoDB AUTO_INCREMENT=996792 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `houses_submarket`
--

DROP TABLE IF EXISTS `houses_submarket`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `houses_submarket` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `houses_tenants`
--

DROP TABLE IF EXISTS `houses_tenants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `houses_tenants` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `House_ID` int(10) unsigned NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Type` enum('office','retail') DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `House_ID` (`House_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `houses_terms`
--

DROP TABLE IF EXISTS `houses_terms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `houses_terms` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `House_ID` int(10) unsigned NOT NULL,
  `Term_ID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `House_ID` (`House_ID`),
  KEY `Term_ID` (`Term_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `houses_types`
--

DROP TABLE IF EXISTS `houses_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `houses_types` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `House_ID` int(10) unsigned NOT NULL DEFAULT '0',
  `Type_ID` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `House_ID` (`House_ID`),
  KEY `Type_ID` (`Type_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=1077810 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `houses_use_types`
--

DROP TABLE IF EXISTS `houses_use_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `houses_use_types` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `House_ID` int(10) unsigned NOT NULL,
  `UseType_ID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `House_ID` (`House_ID`),
  KEY `UseType_ID` (`UseType_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `houses_utilities`
--

DROP TABLE IF EXISTS `houses_utilities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `houses_utilities` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `House_ID` int(10) unsigned NOT NULL,
  `Utility_ID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Index_House_ID` (`House_ID`),
  KEY `Index_Utility_ID` (`Utility_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `images`
--

DROP TABLE IF EXISTS `images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `images` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL DEFAULT '',
  `Desc` text NOT NULL,
  `Assign` varchar(50) NOT NULL,
  `AssignID` int(10) unsigned NOT NULL,
  `Group` varchar(50) NOT NULL,
  `File` varchar(150) NOT NULL,
  `Path` varchar(250) NOT NULL,
  `Primary` tinyint(3) unsigned NOT NULL,
  `Active` tinyint(3) unsigned NOT NULL,
  `Width` int(5) NOT NULL DEFAULT '0',
  `Height` int(5) NOT NULL DEFAULT '0',
  `ExternalSourceID` int(10) unsigned DEFAULT NULL,
  `ExternalImageID` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `Assign` (`Assign`),
  KEY `AssignID` (`AssignID`)
) ENGINE=InnoDB AUTO_INCREMENT=2846480 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `images_thumbs`
--

DROP TABLE IF EXISTS `images_thumbs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `images_thumbs` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL DEFAULT '',
  `File` varchar(160) NOT NULL,
  `Path` varchar(250) NOT NULL,
  `Width` int(5) unsigned NOT NULL DEFAULT '0',
  `Height` int(5) unsigned NOT NULL DEFAULT '0',
  `Image_ID` int(10) unsigned NOT NULL DEFAULT '0',
  `ExternalSourceID` int(1) NOT NULL,
  PRIMARY KEY (`ID`,`ExternalSourceID`),
  KEY `Image_ID` (`Image_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2492129 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `import_mongodb_images`
--

