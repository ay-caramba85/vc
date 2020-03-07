CREATE TABLE IF NOT EXISTS `#__vc` (
	`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`ip_address` INTEGER UNSIGNED NOT NULL,
	`user_agent` VARCHAR(255) NOT NULL,
	`date_of_access` DATE NOT NULL
) ENGINE=INNODB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `#__counter` (
	`id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`access_counter` BIGINT UNSIGNED NOT NULL
) ENGINE=INNODB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


INSERT INTO `#__counter` (`id`, `access_counter`)
VALUES(1, 0);