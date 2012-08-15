ALTER TABLE  `settings` CHANGE  `id`  `id` TINYINT( 1 ) UNSIGNED NOT NULL;

CREATE TABLE IF NOT EXISTS `db_version` (
  `current` varchar(14) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`current`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO  `commando`.`db_version` (`current`) VALUES ('0.1.1');