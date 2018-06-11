CREATE TABLE `entity_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rawdata` json DEFAULT NULL,
  `entity_id` int(11) GENERATED ALWAYS AS (json_unquote(json_extract(`rawdata`,'$.entity_id'))) STORED,
  `entity_type` varchar(200) COLLATE utf8mb4_unicode_ci GENERATED ALWAYS AS (json_unquote(json_extract(`rawdata`,'$.entity_type'))) STORED,
  `createdon` datetime DEFAULT CURRENT_TIMESTAMP,
  `updatedon` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38433 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE `entity_history_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rawdata` json DEFAULT NULL,
  `createdon` datetime DEFAULT CURRENT_TIMESTAMP,
  `updatedon` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `entity_type` varchar(150) COLLATE utf8mb4_unicode_ci GENERATED ALWAYS AS (json_unquote(json_extract(`rawdata`,'$.entity_type'))) STORED,
  `entity_id` int(11) GENERATED ALWAYS AS (json_unquote(json_extract(`rawdata`,'$.entity_id'))) STORED,
  `entity_attribute` varchar(100) COLLATE utf8mb4_unicode_ci GENERATED ALWAYS AS (json_unquote(json_extract(`rawdata`,'$.entity_attribute'))) STORED,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;