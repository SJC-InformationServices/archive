CREATE TABLE `attachments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rawdata` json DEFAULT NULL,
  `createdon` datetime DEFAULT CURRENT_TIMESTAMP,
  `updatedon` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `unique` char(36) CHARACTER SET utf8mb4 GENERATED ALWAYS AS (md5(json_unquote(json_extract(`rawdata`,'$.fullpath')))) STORED,
  `name` text CHARACTER SET utf8mb4 GENERATED ALWAYS AS (json_unquote(json_extract(`rawdata`,'$.name'))) VIRTUAL,
  `path` text CHARACTER SET utf8mb4 GENERATED ALWAYS AS (json_unquote(json_extract(`rawdata`,'$.path'))) STORED,
  `fullpath` text CHARACTER SET utf8mb4 GENERATED ALWAYS AS (json_unquote(json_extract(`rawdata`,'$.fullpath'))) STORED,
  `type` varchar(45) COLLATE utf8mb4_unicode_ci GENERATED ALWAYS AS (json_unquote(json_extract(`rawdata`,'$.type'))) STORED,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_unique_attachements` (`unique`),
  KEY `idx_paths` (`path`(255)),
  KEY `idx_attachements_types` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`sjcArchiveAdmin`@`%`*/ /*!50003 TRIGGER `sjcWalmartArchive`.`attachments_BEFORE_INSERT` BEFORE INSERT ON `attachments` FOR EACH ROW
BEGIN
if JSON_EXTRACT(new.rawdata,'$.UUID') is null then
	set NEW.rawdata = JSON_SET(NEW.rawdata,"$.uuid",uuid());
END IF;

END */;;
/*!50003 CREATE*/ /*!50017 DEFINER=`sjcArchiveAdmin`@`%`*/ /*!50003 TRIGGER `sjcWalmartArchive`.`attachments_BEFORE_UPDATE` BEFORE UPDATE ON `attachments` FOR EACH ROW
BEGIN

insert into `entity_history` (`rawdata`) values (json_set(old.rawdata,'$.entity_type','colors','$.entity_id',old.id));

END */;;
DELIMITER ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`sjcArchiveAdmin`@`%`*/ /*!50003 TRIGGER `sjcWalmartArchive`.`attachments_BEFORE_DELETE` BEFORE DELETE ON `attachments` FOR EACH ROW
BEGIN

insert into `entity_history` (`rawdata`) values (json_set(old.rawdata,'$.entity_type','colors','$.entity_id',old.id));

END */;;
DELIMITER ;