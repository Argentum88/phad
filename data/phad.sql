
SET NAMES utf8;

CREATE TABLE `phad_administrators` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `password` char(60) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `phad_administrators` (`id`, `name`, `password`, `created_at`, `updated_at`) VALUES
(1,	'admin',	'$2a$08$1LA0EZJoTxxWVjTY0ghLNuEvipW8UQgS0z/gnriYaMNGiB3B9leHe',	'2015-06-23 14:44:17',	'2015-06-23 14:44:17');
