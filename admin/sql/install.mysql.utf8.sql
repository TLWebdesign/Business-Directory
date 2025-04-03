SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `#__businessdirectory_company` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`asset_id` INT(10) unsigned NULL DEFAULT 0 COMMENT 'FK to the #__assets table.',
	`address` VARCHAR(255) NOT NULL DEFAULT '',
	`city` VARCHAR(255) NOT NULL DEFAULT '',
	`coc` VARCHAR(255) NOT NULL DEFAULT '',
	`companysize` VARCHAR(255) NULL DEFAULT '',
	`contactname` VARCHAR(255) NOT NULL DEFAULT '',
	`country` VARCHAR(255) NULL DEFAULT '',
	`email` VARCHAR(255) NULL DEFAULT '',
	`expertise` TEXT NOT NULL,
	`guid` VARCHAR(36) NULL DEFAULT '',
	`name` VARCHAR(255) NULL DEFAULT '',
	`phone` VARCHAR(50) NOT NULL DEFAULT '',
	`portfolio` TEXT NULL,
	`state` VARCHAR(255) NULL DEFAULT '',
	`website` VARCHAR(255) NOT NULL DEFAULT '',
	`zip` VARCHAR(255) NOT NULL DEFAULT '',
	`params` TEXT NULL,
	`published` TINYINT(3) NULL DEFAULT 1,
	`created_by` INT unsigned NULL,
	`modified_by` INT unsigned,
	`created` DATETIME DEFAULT CURRENT_TIMESTAMP,
	`modified` DATETIME,
	`checked_out` int unsigned,
	`checked_out_time` DATETIME,
	`version` INT(10) unsigned NULL DEFAULT 1,
	`hits` INT(10) unsigned NULL DEFAULT 0,
	`access` INT(10) unsigned NULL DEFAULT 0,
	`ordering` INT(11) NULL DEFAULT 0,
	`metakey` TEXT,
	`metadesc` TEXT,
	`metadata` TEXT,
	PRIMARY KEY  (`id`),
	KEY `idx_name` (`name`),
	KEY `idx_country` (`country`),
	KEY `idx_guid` (`guid`),
	KEY `idx_companysize` (`companysize`),
	KEY `idx_access` (`access`),
	KEY `idx_checkout` (`checked_out`),
	KEY `idx_createdby` (`created_by`),
	KEY `idx_modifiedby` (`modified_by`),
	KEY `idx_state` (`published`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS `#__businessdirectory_country` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`asset_id` INT(10) unsigned NULL DEFAULT 0 COMMENT 'FK to the #__assets table.',
	`guid` VARCHAR(36) NULL DEFAULT '',
	`name` VARCHAR(255) NULL DEFAULT '',
	`params` TEXT NULL,
	`published` TINYINT(3) NULL DEFAULT 1,
	`created_by` INT unsigned NULL,
	`modified_by` INT unsigned,
	`created` DATETIME DEFAULT CURRENT_TIMESTAMP,
	`modified` DATETIME,
	`checked_out` int unsigned,
	`checked_out_time` DATETIME,
	`version` INT(10) unsigned NULL DEFAULT 1,
	`hits` INT(10) unsigned NULL DEFAULT 0,
	`access` INT(10) unsigned NULL DEFAULT 0,
	`ordering` INT(11) NULL DEFAULT 0,
	`metakey` TEXT,
	`metadesc` TEXT,
	`metadata` TEXT,
	PRIMARY KEY  (`id`),
	KEY `idx_name` (`name`),
	KEY `idx_guid` (`guid`),
	KEY `idx_access` (`access`),
	KEY `idx_checkout` (`checked_out`),
	KEY `idx_createdby` (`created_by`),
	KEY `idx_modifiedby` (`modified_by`),
	KEY `idx_state` (`published`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS `#__businessdirectory_state` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`asset_id` INT(10) unsigned NULL DEFAULT 0 COMMENT 'FK to the #__assets table.',
	`country` VARCHAR(255) NULL DEFAULT '',
	`guid` VARCHAR(36) NULL DEFAULT '',
	`name` VARCHAR(255) NULL DEFAULT '',
	`params` TEXT NULL,
	`published` TINYINT(3) NULL DEFAULT 1,
	`created_by` INT unsigned NULL,
	`modified_by` INT unsigned,
	`created` DATETIME DEFAULT CURRENT_TIMESTAMP,
	`modified` DATETIME,
	`checked_out` int unsigned,
	`checked_out_time` DATETIME,
	`version` INT(10) unsigned NULL DEFAULT 1,
	`hits` INT(10) unsigned NULL DEFAULT 0,
	`access` INT(10) unsigned NULL DEFAULT 0,
	`ordering` INT(11) NULL DEFAULT 0,
	`metakey` TEXT,
	`metadesc` TEXT,
	`metadata` TEXT,
	PRIMARY KEY  (`id`),
	KEY `idx_name` (`name`),
	KEY `idx_country` (`country`),
	KEY `idx_guid` (`guid`),
	KEY `idx_access` (`access`),
	KEY `idx_checkout` (`checked_out`),
	KEY `idx_createdby` (`created_by`),
	KEY `idx_modifiedby` (`modified_by`),
	KEY `idx_state` (`published`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `#__businessdirectory_country`
--

INSERT INTO `#__businessdirectory_country` (`id`, `name`, `metakey`, `metadesc`, `metadata`, `ordering`, `published`, `modified`, `created`,  `created_by`, `hits`, `version`, `params`, `guid`) VALUES
(1, 'The Netherlands', '', '', '{"robots":"","author":"","rights":""}', 1, 1, '2025-03-26 19:55:47', '2025-03-25 16:23:34', 0, '', 1, '', 'a1b2c3d4-1111-2222-3333-1234567890ab'),
(2, 'Belgium', '', '', '{"robots":"","author":"","rights":""}', 1, 1, '2025-03-26 19:55:47', '2025-03-25 16:23:34', 0, '', 1, '', 'b2c3d4e5-4444-5555-6666-abcdefabcdef');

--
-- Dumping data for table `#__businessdirectory_state`
--

INSERT INTO `#__businessdirectory_state` 
(`id`, `country`, `name`, `metakey`, `metadesc`, `metadata`, `ordering`, `published`, `modified`, `created`, `created_by`, `hits`, `version`, `params`, `guid`)
VALUES
-- Nederlandse provincies
(1, 'a1b2c3d4-1111-2222-3333-1234567890ab', 'Drenthe', '', '', '{"robots":"","author":"","rights":""}', 1, 1, '2025-03-26 19:55:47', '2025-03-25 16:23:34', 0, '', 1, '', 'f36a1b3c-1df2-430e-a981-71a1c1000011'),
(2, 'a1b2c3d4-1111-2222-3333-1234567890ab', 'Flevoland', '', '', '{"robots":"","author":"","rights":""}', 2, 1, '2025-03-26 19:55:47', '2025-03-25 16:23:34', 0, '', 1, '', 'f36a1b3c-1df2-430e-a981-71a1c1000012'),
(3, 'a1b2c3d4-1111-2222-3333-1234567890ab', 'Friesland', '', '', '{"robots":"","author":"","rights":""}', 3, 1, '2025-03-26 19:55:47', '2025-03-25 16:23:34', 0, '', 1, '', 'f36a1b3c-1df2-430e-a981-71a1c1000013'),
(4, 'a1b2c3d4-1111-2222-3333-1234567890ab', 'Gelderland', '', '', '{"robots":"","author":"","rights":""}', 4, 1, '2025-03-26 19:55:47', '2025-03-25 16:23:34', 0, '', 1, '', 'f36a1b3c-1df2-430e-a981-71a1c1000014'),
(5, 'a1b2c3d4-1111-2222-3333-1234567890ab', 'Groningen', '', '', '{"robots":"","author":"","rights":""}', 5, 1, '2025-03-26 19:55:47', '2025-03-25 16:23:34', 0, '', 1, '', 'f36a1b3c-1df2-430e-a981-71a1c1000015'),
(6, 'a1b2c3d4-1111-2222-3333-1234567890ab', 'Limburg', '', '', '{"robots":"","author":"","rights":""}', 6, 1, '2025-03-26 19:55:47', '2025-03-25 16:23:34', 0, '', 1, '', 'f36a1b3c-1df2-430e-a981-71a1c1000016'),
(7, 'a1b2c3d4-1111-2222-3333-1234567890ab', 'Noord-Brabant', '', '', '{"robots":"","author":"","rights":""}', 7, 1, '2025-03-26 19:55:47', '2025-03-25 16:23:34', 0, '', 1, '', 'f36a1b3c-1df2-430e-a981-71a1c1000017'),
(8, 'a1b2c3d4-1111-2222-3333-1234567890ab', 'Noord-Holland', '', '', '{"robots":"","author":"","rights":""}', 8, 1, '2025-03-26 19:55:47', '2025-03-25 16:23:34', 0, '', 1, '', 'f36a1b3c-1df2-430e-a981-71a1c1000018'),
(9, 'a1b2c3d4-1111-2222-3333-1234567890ab', 'Overijssel', '', '', '{"robots":"","author":"","rights":""}', 9, 1, '2025-03-26 19:55:47', '2025-03-25 16:23:34', 0, '', 1, '', 'f36a1b3c-1df2-430e-a981-71a1c1000019'),
(10, 'a1b2c3d4-1111-2222-3333-1234567890ab', 'Utrecht', '', '', '{"robots":"","author":"","rights":""}', 10, 1, '2025-03-26 19:55:47', '2025-03-25 16:23:34', 0, '', 1, '', 'f36a1b3c-1df2-430e-a981-71a1c1000020'),
(11, 'a1b2c3d4-1111-2222-3333-1234567890ab', 'Zeeland', '', '', '{"robots":"","author":"","rights":""}', 11, 1, '2025-03-26 19:55:47', '2025-03-25 16:23:34', 0, '', 1, '', 'f36a1b3c-1df2-430e-a981-71a1c1000021'),
(12, 'a1b2c3d4-1111-2222-3333-1234567890ab', 'Zuid-Holland', '', '', '{"robots":"","author":"","rights":""}', 12, 1, '2025-03-26 19:55:47', '2025-03-25 16:23:34', 0, '', 1, '', 'f36a1b3c-1df2-430e-a981-71a1c1000022'),

-- Belgische gewesten
(13, 'b2c3d4e5-4444-5555-6666-abcdefabcdef', 'Vlaams Gewest', '', '', '{"robots":"","author":"","rights":""}', 1, 1, '2025-03-26 19:55:47', '2025-03-25 16:23:34', 0, '', 1, '', 'b27aa8f1-21ce-4b3e-9a92-88919d000001'),
(14, 'b2c3d4e5-4444-5555-6666-abcdefabcdef', 'Waals Gewest', '', '', '{"robots":"","author":"","rights":""}', 2, 1, '2025-03-26 19:55:47', '2025-03-25 16:23:34', 0, '', 1, '', 'b27aa8f1-21ce-4b3e-9a92-88919d000002');


