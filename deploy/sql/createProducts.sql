CREATE TABLE `products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rawdata` json DEFAULT NULL,
  `uuid` varchar(36) CHARACTER SET utf8mb4 GENERATED ALWAYS AS (json_unquote(json_extract(`rawdata`,'$.uuid'))) STORED,
  `createdon` datetime DEFAULT CURRENT_TIMESTAMP,
  `updatedon` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `brand` varchar(100) COLLATE utf8mb4_unicode_ci GENERATED ALWAYS AS (json_unquote(json_extract(`rawdata`,'$."brand"'))) STORED,
  `abstyle` varchar(50) COLLATE utf8mb4_unicode_ci GENERATED ALWAYS AS (json_unquote(json_extract(`rawdata`,'$."abstyle"'))) STORED,
  `mainstyleattributes` text COLLATE utf8mb4_unicode_ci GENERATED ALWAYS AS (replace(json_unquote(json_extract(`rawdata`,'$."mainstyleattributes"')),'<br>','\n')) STORED,
  `styledescription` text COLLATE utf8mb4_unicode_ci GENERATED ALWAYS AS (replace(json_unquote(json_extract(`rawdata`,'$."styledescription"')),'<br>','\n')) VIRTUAL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uuid` (`uuid`),
  UNIQUE KEY `idx_unique_style` (`abstyle`)
) ENGINE=InnoDB AUTO_INCREMENT=3464 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`sjcArchiveAdmin`@`%`*/ /*!50003 TRIGGER `sjcWalmartArchive`.`products_BEFORE_INSERT` BEFORE INSERT ON `products` FOR EACH ROW
BEGIN
if new.rawdata is null or JSON_EXTRACT(NEW.rawdata, '$.keyfield') is null then 
signal sqlstate '45000' set MESSAGE_TEXT = "abstyle can not be null";
end if;
if JSON_EXTRACT(NEW.rawdata, '$.iconsimage') is null then 
	set NEW.rawdata = JSON_SET(NEW.rawdata,"$.iconsimage",JSON_ARRAY());
end if;
if JSON_EXTRACT(NEW.rawdata, '$.logos') is null then 
	set NEW.rawdata = JSON_SET(NEW.rawdata,"$.logos",JSON_ARRAY());
end if;

if JSON_EXTRACT(new.rawdata,'$.UUID') is null then
	set NEW.rawdata = JSON_SET(NEW.rawdata,"$.uuid",uuid());
END IF;
END */;;

/*!50003 CREATE*/ /*!50017 DEFINER=`sjcArchiveAdmin`@`%`*/ /*!50003 TRIGGER `sjcWalmartArchive`.`products_BEFORE_UPDATE` BEFORE UPDATE ON `products` FOR EACH ROW
BEGIN


insert into `entity_history` (`rawdata`) values (json_set(old.rawdata,'$.entity_type','products','$.entity_id',old.id));
END */;;
/*!50003 CREATE*/ /*!50017 DEFINER=`sjcArchiveAdmin`@`%`*/ /*!50003 TRIGGER `sjcWalmartArchive`.`products_BEFORE_DELETE` BEFORE DELETE ON `products` FOR EACH ROW
BEGIN

insert into `entity_history` (`rawdata`) values (json_set(old.rawdata,'$.entity_type','products','$.entity_id',old.id));
END */;;
DELIMITER ;

CREATE TABLE `products_attachments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `products_id` int(10) unsigned NOT NULL,
  `attachments_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_products_attachments` (`products_id`,`attachments_id`),
  KEY `fk_products_attachments` (`attachments_id`),
  CONSTRAINT `fk_attachments_products` FOREIGN KEY (`products_id`) REFERENCES `products` (`id`),
  CONSTRAINT `fk_products_attachments` FOREIGN KEY (`attachments_id`) REFERENCES `attachments` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;