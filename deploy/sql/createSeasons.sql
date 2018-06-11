CREATE TABLE `seasons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rawdata` json DEFAULT NULL,
  `createdon` datetime DEFAULT CURRENT_TIMESTAMP,
  `updatedon` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `uuid` varchar(36) CHARACTER SET utf8mb4 GENERATED ALWAYS AS (json_unquote(json_extract(`rawdata`,'$.uuid'))) VIRTUAL,
  `name` varchar(100) CHARACTER SET utf8mb4 GENERATED ALWAYS AS (json_unquote(json_extract(`rawdata`,'$.name'))) VIRTUAL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  UNIQUE KEY `uuid_UNIQUE` (`uuid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`sjcArchiveAdmin`@`%`*/ /*!50003 TRIGGER `sjcWalmartArchive`.`seasons_BEFORE_INSERT` BEFORE INSERT ON `seasons` FOR EACH ROW
BEGIN
if new.rawdata is null or JSON_EXTRACT(NEW.rawdata, '$.name') is null then 
signal sqlstate '45000' set MESSAGE_TEXT = "Rawdata.Name can not be null";
end if;


if JSON_EXTRACT(new.rawdata,'$.UUID') is null then
	set NEW.rawdata = JSON_SET(NEW.rawdata,"$.uuid",uuid());
END IF;
END */;;

/*!50003 CREATE*/ /*!50017 DEFINER=`sjcArchiveAdmin`@`%`*/ /*!50003 TRIGGER `sjcWalmartArchive`.`seasons_BEFORE_UPDATE` BEFORE UPDATE ON `seasons` FOR EACH ROW
BEGIN
insert into `entity_history` (`rawdata`) values (json_set(old.rawdata,'$.entity_type','seasons','$.entity_id',old.id));
END */;;

DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`sjcArchiveAdmin`@`%`*/ /*!50003 TRIGGER `sjcWalmartArchive`.`seasons_BEFORE_DELETE` BEFORE DELETE ON `seasons` FOR EACH ROW
BEGIN
insert into `entity_history` (`rawdata`) values (json_set(old.rawdata,'$.entity_type','seasons','$.entity_id',old.id));
END */;;
