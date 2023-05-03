-- --------------------------------------------------------
-- Hôte :                        127.0.0.1
-- Version du serveur:           5.7.24 - MySQL Community Server (GPL)
-- SE du serveur:                Win64
-- HeidiSQL Version:             10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Listage de la structure de la base pour symfonysession
CREATE DATABASE IF NOT EXISTS `symfonysession` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `symfonysession`;

-- Listage de la structure de la table symfonysession. category
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Listage des données de la table symfonysession.category : ~0 rows (environ)
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` (`id`, `title`) VALUES
	(1, 'Danger');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;

-- Listage de la structure de la table symfonysession. doctrine_migration_versions
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Listage des données de la table symfonysession.doctrine_migration_versions : ~1 rows (environ)
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
	('DoctrineMigrations\\Version20230503090023', '2023-05-03 09:00:36', 1190);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;

-- Listage de la structure de la table symfonysession. formation
CREATE TABLE IF NOT EXISTS `formation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Listage des données de la table symfonysession.formation : ~0 rows (environ)
/*!40000 ALTER TABLE `formation` DISABLE KEYS */;
INSERT INTO `formation` (`id`, `title`, `image`) VALUES
	(1, 'Les chat', 'http://placekitten.com/g/200/300');
/*!40000 ALTER TABLE `formation` ENABLE KEYS */;

-- Listage de la structure de la table symfonysession. intern
CREATE TABLE IF NOT EXISTS `intern` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `signing_date` datetime NOT NULL,
  `mail` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `phone` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Listage des données de la table symfonysession.intern : ~0 rows (environ)
/*!40000 ALTER TABLE `intern` DISABLE KEYS */;
INSERT INTO `intern` (`id`, `name`, `firstname`, `signing_date`, `mail`, `password`, `city`, `phone`) VALUES
	(1, 'Paresseux', 'Stroumf', '2023-05-03 11:03:23', 'sp@test.fr', '123', 'Nimp', '0647648844'),
	(2, 'Joli', 'Stroumf', '2023-05-03 11:05:26', 'sj@test.fr', '123', 'Nimp', '0647648844');
/*!40000 ALTER TABLE `intern` ENABLE KEYS */;

-- Listage de la structure de la table symfonysession. messenger_messages
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Listage des données de la table symfonysession.messenger_messages : ~0 rows (environ)
/*!40000 ALTER TABLE `messenger_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messenger_messages` ENABLE KEYS */;

-- Listage de la structure de la table symfonysession. module
CREATE TABLE IF NOT EXISTS `module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C24262812469DE2` (`category_id`),
  CONSTRAINT `FK_C24262812469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Listage des données de la table symfonysession.module : ~0 rows (environ)
/*!40000 ALTER TABLE `module` DISABLE KEYS */;
INSERT INTO `module` (`id`, `category_id`, `title`, `description`) VALUES
	(1, 1, 'Echaper aux chat', 'Comment échaper à Azrael en 5 minutes');
/*!40000 ALTER TABLE `module` ENABLE KEYS */;

-- Listage de la structure de la table symfonysession. programme
CREATE TABLE IF NOT EXISTS `programme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `module_duration` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3DDCB9FFAFC2B591` (`module_id`),
  KEY `IDX_3DDCB9FF613FECDF` (`session_id`),
  CONSTRAINT `FK_3DDCB9FF613FECDF` FOREIGN KEY (`session_id`) REFERENCES `session` (`id`),
  CONSTRAINT `FK_3DDCB9FFAFC2B591` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Listage des données de la table symfonysession.programme : ~0 rows (environ)
/*!40000 ALTER TABLE `programme` DISABLE KEYS */;
INSERT INTO `programme` (`id`, `module_id`, `session_id`, `module_duration`) VALUES
	(1, 1, 1, 8);
/*!40000 ALTER TABLE `programme` ENABLE KEYS */;

-- Listage de la structure de la table symfonysession. session
CREATE TABLE IF NOT EXISTS `session` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `formation_id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `nb_place` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D044D5D45200282E` (`formation_id`),
  KEY `IDX_D044D5D4FB08EDF6` (`trainer_id`),
  CONSTRAINT `FK_D044D5D45200282E` FOREIGN KEY (`formation_id`) REFERENCES `formation` (`id`),
  CONSTRAINT `FK_D044D5D4FB08EDF6` FOREIGN KEY (`trainer_id`) REFERENCES `trainer` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Listage des données de la table symfonysession.session : ~0 rows (environ)
/*!40000 ALTER TABLE `session` DISABLE KEYS */;
INSERT INTO `session` (`id`, `formation_id`, `trainer_id`, `title`, `image`, `start_date`, `end_date`, `nb_place`) VALUES
	(1, 1, 1, 'Echaper au chat', 'http://placekitten.com/g/200/300', '2023-05-01 11:08:19', '2023-05-03 11:08:20', 8);
/*!40000 ALTER TABLE `session` ENABLE KEYS */;

-- Listage de la structure de la table symfonysession. session_intern
CREATE TABLE IF NOT EXISTS `session_intern` (
  `session_id` int(11) NOT NULL,
  `intern_id` int(11) NOT NULL,
  PRIMARY KEY (`session_id`,`intern_id`),
  KEY `IDX_CA12556F613FECDF` (`session_id`),
  KEY `IDX_CA12556F525DD4B4` (`intern_id`),
  CONSTRAINT `FK_CA12556F525DD4B4` FOREIGN KEY (`intern_id`) REFERENCES `intern` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_CA12556F613FECDF` FOREIGN KEY (`session_id`) REFERENCES `session` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Listage des données de la table symfonysession.session_intern : ~0 rows (environ)
/*!40000 ALTER TABLE `session_intern` DISABLE KEYS */;
INSERT INTO `session_intern` (`session_id`, `intern_id`) VALUES
	(1, 1),
	(1, 2);
/*!40000 ALTER TABLE `session_intern` ENABLE KEYS */;

-- Listage de la structure de la table symfonysession. trainer
CREATE TABLE IF NOT EXISTS `trainer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `signing_date` datetime NOT NULL,
  `mail` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Listage des données de la table symfonysession.trainer : ~0 rows (environ)
/*!40000 ALTER TABLE `trainer` DISABLE KEYS */;
INSERT INTO `trainer` (`id`, `name`, `firstname`, `signing_date`, `mail`, `password`) VALUES
	(1, 'Grognon', 'Stroumf', '2023-05-03 11:04:24', 'sg@test.fr', '123'),
	(2, 'Coquet', 'Stroumf', '2023-05-03 11:04:52', 'sc@test.fr', '123');
/*!40000 ALTER TABLE `trainer` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
