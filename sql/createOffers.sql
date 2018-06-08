CREATE TABLE `offers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rawdata` json DEFAULT NULL,
  `createdon` datetime DEFAULT CURRENT_TIMESTAMP,
  `updatedon` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `projects_id` int(10) unsigned NOT NULL,
  `pagefrom` varchar(45) CHARACTER SET utf8mb4 GENERATED ALWAYS AS (json_unquote(json_extract(`rawdata`,'$.pagefrom'))) STORED,
  `pageto` varchar(45) CHARACTER SET utf8mb4 GENERATED ALWAYS AS (json_unquote(json_extract(`rawdata`,'$.pageto'))) STORED,
  `abstyle` varchar(45) COLLATE utf8mb4_unicode_ci GENERATED ALWAYS AS (json_unquote(json_extract(`rawdata`,'$.abstyle'))) STORED,
  `offerstate` varchar(45) COLLATE utf8mb4_unicode_ci GENERATED ALWAYS AS (json_unquote(json_extract(`rawdata`,'$.offersstate'))) STORED,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_unique_offers` (`projects_id`,`pagefrom`,`abstyle`),
  KEY `idx_offers_project_id` (`projects_id`,`pagefrom`,`abstyle`),
  CONSTRAINT `fk_offers_projects` FOREIGN KEY (`projects_id`) REFERENCES `projects` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20660 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`sjcArchiveAdmin`@`%`*/ /*!50003 TRIGGER `sjcWalmartArchive`.`offers_BEFORE_INSERT` BEFORE INSERT ON `offers` FOR EACH ROW
BEGIN

if new.rawdata is null or JSON_EXTRACT(NEW.rawdata, '$.abstyle') is null then 
signal sqlstate '45000' set MESSAGE_TEXT = "Rawdata.abstyle can not be null";
end if;

if JSON_EXTRACT(new.rawdata,'$.UUID') is null then
	set NEW.rawdata = JSON_SET(NEW.rawdata,"$.uuid",uuid());
END IF;

if JSON_EXTRACT(NEW.rawdata,'$.pagefrom') is null then
set new.rawdata = JSON_SET(new.rawdata,"$.pagefrom",0);
end if;

if JSON_EXTRACT(NEW.rawdata, '$.pageto') is null then 
	set NEW.rawdata = JSON_SET(NEW.rawdata,"$.pageto",JSON_UNQUOTE(JSON_EXTRACT(NEW.rawdata, '$.pagefrom')));
end if;

if JSON_EXTRACT(NEW.rawdata, '$.offerstate') is null then 
	set NEW.rawdata = JSON_SET(NEW.rawdata,"$.offerstate","OPEN");
end if;

if JSON_EXTRACT(NEW.rawdata, '$.iconsimage') is null then 
	set NEW.rawdata = JSON_SET(NEW.rawdata,"$.iconsimage",JSON_ARRAY());
end if;
if JSON_EXTRACT(NEW.rawdata, '$.offerimage') is null then 
	set NEW.rawdata = JSON_SET(NEW.rawdata,"$.offerimage",JSON_ARRAY());
end if;
if JSON_EXTRACT(NEW.rawdata, '$.logos') is null then 
	set NEW.rawdata = JSON_SET(NEW.rawdata,"$.logos",JSON_ARRAY());
end if;

END */;;

/*!50003 CREATE*/ /*!50017 DEFINER=`sjcArchiveAdmin`@`%`*/ /*!50003 TRIGGER `sjcWalmartArchive`.`offers_BEFORE_UPDATE` BEFORE UPDATE ON `offers` FOR EACH ROW
BEGIN
if old.offerstate = "CLOSED" and NEW.offerstate <> "OPEN" then 
signal sqlstate '45000' set MESSAGE_TEXT = "Record has been closed for Updates Please Choose correct records";
end if;

insert into `entity_history` (`rawdata`) values (json_set(old.rawdata,'$.entity_type','colors','$.entity_id',old.id));
END */;;

/*!50003 CREATE*/ /*!50017 DEFINER=`sjcArchiveAdmin`@`%`*/ /*!50003 TRIGGER `sjcWalmartArchive`.`offers_BEFORE_DELETE` BEFORE DELETE ON `offers` FOR EACH ROW
BEGIN
insert into `entity_history` (`rawdata`) values (json_set(old.rawdata,'$.entity_type','colors','$.entity_id',old.id));
END */;;

DELIMITER ;
CREATE TABLE `offers_attachments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `offers_id` int(10) unsigned NOT NULL,
  `attachments_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_offers_attachments` (`offers_id`,`attachments_id`),
  KEY `fk_offers_attachments` (`attachments_id`),
  CONSTRAINT `fk_attachments_offers` FOREIGN KEY (`offers_id`) REFERENCES `offers` (`id`),
  CONSTRAINT `fk_offers_attachments` FOREIGN KEY (`attachments_id`) REFERENCES `attachments` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `offers_products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `offers_id` int(10) unsigned NOT NULL,
  `products_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_offers_products` (`offers_id`,`products_id`),
  KEY `fk_offers_products` (`products_id`),
  CONSTRAINT `fk_offers_products` FOREIGN KEY (`products_id`) REFERENCES `products` (`id`),
  CONSTRAINT `fk_products_offers` FOREIGN KEY (`offers_id`) REFERENCES `offers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
