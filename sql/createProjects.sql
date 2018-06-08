CREATE TABLE `projects` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rawdata` json DEFAULT NULL,
  `uuid` varchar(36) CHARACTER SET utf8mb4 GENERATED ALWAYS AS (json_unquote(json_extract(`rawdata`,'$.uuid'))) STORED,
  `createdon` datetime DEFAULT CURRENT_TIMESTAMP,
  `updatedon` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `seasons_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 GENERATED ALWAYS AS (json_unquote(json_extract(`rawdata`,'$.name'))) STORED,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_projects_uuid` (`uuid`),
  UNIQUE KEY `idx_unique_projects_seasons` (`seasons_id`,`name`),
  KEY `idx_projects_seasons_id` (`seasons_id`),
  CONSTRAINT `fk_projects_seasons` FOREIGN KEY (`seasons_id`) REFERENCES `seasons` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`sjcArchiveAdmin`@`%`*/ /*!50003 TRIGGER `sjcWalmartArchive`.`projects_BEFORE_INSERT` BEFORE INSERT ON `projects` FOR EACH ROW
BEGIN
if new.rawdata is null or JSON_EXTRACT(NEW.rawdata, '$.name') is null then 
signal sqlstate '45000' set MESSAGE_TEXT = "Rawdata.Name can not be null";
end if;



if JSON_EXTRACT(new.rawdata,'$.UUID') is null then
	set NEW.rawdata = JSON_SET(NEW.rawdata,"$.uuid",uuid());
END IF;


END */;;

/*!50003 CREATE*/ /*!50017 DEFINER=`sjcArchiveAdmin`@`%`*/ /*!50003 TRIGGER `sjcWalmartArchive`.`projects_BEFORE_UPDATE` BEFORE UPDATE ON `projects` FOR EACH ROW
BEGIN
insert into `entity_history` (`rawdata`) values (json_set(old.rawdata,'$.entity_type','projects','$.entity_id',old.id));
END */;;

/*!50003 CREATE*/ /*!50017 DEFINER=`sjcArchiveAdmin`@`%`*/ /*!50003 TRIGGER `sjcWalmartArchive`.`projects_BEFORE_DELETE` BEFORE DELETE ON `projects` FOR EACH ROW
BEGIN
insert into `entity_history` (`rawdata`) values (json_set(old.rawdata,'$.entity_type','projects','$.entity_id',old.id));
END */;;
Delimiter ;

CREATE TABLE `projects_attachments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `projects_id` int(10) unsigned NOT NULL,
  `attachments_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_projects_attachments` (`projects_id`,`attachments_id`),
  KEY `fk_projects_attachments` (`attachments_id`),
  CONSTRAINT `fk_attachments_projects` FOREIGN KEY (`projects_id`) REFERENCES `projects` (`id`),
  CONSTRAINT `fk_projects_attachments` FOREIGN KEY (`attachments_id`) REFERENCES `attachments` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `projects_products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `projects_id` int(10) unsigned NOT NULL,
  `products_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_projects_products` (`projects_id`,`products_id`),
  KEY `fk_projects_products` (`products_id`),
  CONSTRAINT `fk_products_projects` FOREIGN KEY (`projects_id`) REFERENCES `projects` (`id`),
  CONSTRAINT `fk_projects_products` FOREIGN KEY (`products_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;