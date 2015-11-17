<?php
$sql = array(
	/*
	DELETE FROM `lin_directory` WHERE domainID != 16;
	DELETE FROM `lin_directory_categories` WHERE domainID != '16';
	
	ALTER TABLE  `lin_directory_cat` CHANGE  `dirID`  `itemID` INT( 6 ) NULL DEFAULT NULL;
	RENAME TABLE  `lin_directory` TO  `dir_items` ;
	RENAME TABLE  `lin_directory_categories` TO  `dir_categories` ;
	RENAME TABLE  `lin_directory_cat` TO  `dir_item_category` ;
	ALTER TABLE `dir_categories` DROP `domainID`;
	ALTER TABLE `dir_items` DROP `domainID`;
	  
	 */
	"ALTER TABLE  `dir_categories` ADD  `text` TEXT NULL DEFAULT NULL AFTER  `category`;", 
		"ALTER TABLE  `dir_categories` ADD  `photo` VARCHAR( 50 ) NULL DEFAULT NULL AFTER  `text`;",
		"RENAME TABLE  `tb_users` TO  `dir_users` ;",
		"ALTER TABLE  `dir_users` CHANGE  `lastActivity`  `lastLogin` DATETIME NULL DEFAULT NULL;",
		"CREATE TABLE IF NOT EXISTS `dir_items_photos` (  `ID` int(6) NOT NULL AUTO_INCREMENT,  `itemID` int(6) DEFAULT NULL,  `photo` varchar(50) DEFAULT NULL,  PRIMARY KEY (`ID`),  KEY `itemID` (`itemID`));",
		"ALTER TABLE  `dir_categories` ADD  `relationship` VARCHAR( 250 ) NULL DEFAULT NULL AFTER  `photo`;"








)?>
