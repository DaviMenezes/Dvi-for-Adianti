-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           5.7.22-0ubuntu0.16.04.1 - (Ubuntu)
-- OS do Servidor:               Linux
-- HeidiSQL Versão:              9.5.0.5278
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Copiando estrutura para tabela dvi.ctt_city
DROP TABLE IF EXISTS `ctt_city`;
CREATE TABLE IF NOT EXISTS `ctt_city` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela dvi.ctt_city: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `ctt_city` DISABLE KEYS */;
INSERT INTO `ctt_city` (`id`, `name`) VALUES
	(1, 'São Paulo');
/*!40000 ALTER TABLE `ctt_city` ENABLE KEYS */;

-- Copiando estrutura para tabela dvi.ctt_contact
DROP TABLE IF EXISTS `ctt_contact`;
CREATE TABLE IF NOT EXISTS `ctt_contact` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cpf` varchar(14) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rg` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `observation` text COLLATE utf8mb4_unicode_ci,
  `city_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cpf` (`cpf`),
  KEY `FK_ctt_contact_ctt_city` (`city_id`),
  KEY `name` (`name`),
  CONSTRAINT `FK_ctt_contact_ctt_city` FOREIGN KEY (`city_id`) REFERENCES `ctt_city` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela dvi.ctt_contact: ~1 rows (aproximadamente)
/*!40000 ALTER TABLE `ctt_contact` DISABLE KEYS */;
INSERT INTO `ctt_contact` (`id`, `name`, `cpf`, `rg`, `birthday`, `observation`, `city_id`) VALUES
	(1, 'Davi Menezes', NULL, NULL, NULL, 'Telegram: @davimenezes\r\nGithub: https://github.com/DaviMenezes', 1);
/*!40000 ALTER TABLE `ctt_contact` ENABLE KEYS */;

-- Copiando estrutura para tabela dvi.sys_document
DROP TABLE IF EXISTS `sys_document`;
CREATE TABLE IF NOT EXISTS `sys_document` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `system_user_id` int(10) unsigned DEFAULT NULL,
  `title` text,
  `description` text,
  `category_id` int(10) unsigned DEFAULT NULL,
  `submission_date` date DEFAULT NULL,
  `archive_date` date DEFAULT NULL,
  `filename` text,
  PRIMARY KEY (`id`),
  KEY `FK_sys_document_sys_user` (`system_user_id`),
  KEY `FK_sys_document_sys_document_category` (`category_id`),
  CONSTRAINT `FK_sys_document_sys_document_category` FOREIGN KEY (`category_id`) REFERENCES `sys_document_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_sys_document_sys_user` FOREIGN KEY (`system_user_id`) REFERENCES `sys_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Copiando dados para a tabela dvi.sys_document: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `sys_document` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_document` ENABLE KEYS */;

-- Copiando estrutura para tabela dvi.sys_document_category
DROP TABLE IF EXISTS `sys_document_category`;
CREATE TABLE IF NOT EXISTS `sys_document_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Copiando dados para a tabela dvi.sys_document_category: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `sys_document_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_document_category` ENABLE KEYS */;

-- Copiando estrutura para tabela dvi.sys_document_group
DROP TABLE IF EXISTS `sys_document_group`;
CREATE TABLE IF NOT EXISTS `sys_document_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `document_id` int(10) unsigned DEFAULT NULL,
  `system_group_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_sys_document_group_sys_document` (`document_id`),
  KEY `FK_sys_document_group_sys_group` (`system_group_id`),
  CONSTRAINT `FK_sys_document_group_sys_document` FOREIGN KEY (`document_id`) REFERENCES `sys_document` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_sys_document_group_sys_group` FOREIGN KEY (`system_group_id`) REFERENCES `sys_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Copiando dados para a tabela dvi.sys_document_group: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `sys_document_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_document_group` ENABLE KEYS */;

-- Copiando estrutura para tabela dvi.sys_document_user
DROP TABLE IF EXISTS `sys_document_user`;
CREATE TABLE IF NOT EXISTS `sys_document_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `document_id` int(10) unsigned DEFAULT NULL,
  `system_user_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_sys_document_user_sys_document` (`document_id`),
  KEY `FK_sys_document_user_sys_user` (`system_user_id`),
  CONSTRAINT `FK_sys_document_user_sys_document` FOREIGN KEY (`document_id`) REFERENCES `sys_document` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_sys_document_user_sys_user` FOREIGN KEY (`system_user_id`) REFERENCES `sys_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Copiando dados para a tabela dvi.sys_document_user: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `sys_document_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_document_user` ENABLE KEYS */;

-- Copiando estrutura para tabela dvi.sys_group
DROP TABLE IF EXISTS `sys_group`;
CREATE TABLE IF NOT EXISTS `sys_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Copiando dados para a tabela dvi.sys_group: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `sys_group` DISABLE KEYS */;
INSERT INTO `sys_group` (`id`, `name`) VALUES
	(1, 'Admin'),
	(2, 'Standard');
/*!40000 ALTER TABLE `sys_group` ENABLE KEYS */;

-- Copiando estrutura para tabela dvi.sys_group_program
DROP TABLE IF EXISTS `sys_group_program`;
CREATE TABLE IF NOT EXISTS `sys_group_program` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `system_group_id` int(10) unsigned DEFAULT NULL,
  `system_program_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_sys_group_program_sys_group` (`system_group_id`),
  KEY `FK_sys_group_program_sys_program` (`system_program_id`),
  CONSTRAINT `FK_sys_group_program_sys_group` FOREIGN KEY (`system_group_id`) REFERENCES `sys_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_sys_group_program_sys_program` FOREIGN KEY (`system_program_id`) REFERENCES `sys_program` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=latin1;

-- Copiando dados para a tabela dvi.sys_group_program: ~38 rows (aproximadamente)
/*!40000 ALTER TABLE `sys_group_program` DISABLE KEYS */;
INSERT INTO `sys_group_program` (`id`, `system_group_id`, `system_program_id`) VALUES
	(1, 1, 1),
	(2, 1, 2),
	(3, 1, 3),
	(4, 1, 4),
	(5, 1, 5),
	(6, 1, 6),
	(7, 1, 8),
	(8, 1, 9),
	(9, 1, 11),
	(10, 1, 14),
	(11, 1, 15),
	(20, 1, 21),
	(25, 1, 26),
	(26, 1, 27),
	(27, 1, 28),
	(28, 1, 29),
	(30, 1, 31),
	(31, 1, 32),
	(32, 1, 33),
	(33, 1, 34),
	(34, 2, 10),
	(35, 2, 12),
	(36, 2, 13),
	(37, 2, 16),
	(38, 2, 17),
	(39, 2, 18),
	(40, 2, 19),
	(41, 2, 20),
	(42, 2, 22),
	(43, 2, 23),
	(44, 2, 24),
	(45, 2, 25),
	(46, 2, 30),
	(49, 2, 37),
	(50, 2, 38),
	(51, 2, 39);
/*!40000 ALTER TABLE `sys_group_program` ENABLE KEYS */;

-- Copiando estrutura para tabela dvi.sys_message
DROP TABLE IF EXISTS `sys_message`;
CREATE TABLE IF NOT EXISTS `sys_message` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `system_user_id` int(10) unsigned DEFAULT NULL,
  `system_user_to_id` int(10) unsigned DEFAULT NULL,
  `subject` text,
  `message` text,
  `dt_message` text,
  `checked` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_sys_message_sys_user` (`system_user_to_id`),
  KEY `FK_sys_message_sys_user_2` (`system_user_id`),
  CONSTRAINT `FK_sys_message_sys_user` FOREIGN KEY (`system_user_to_id`) REFERENCES `sys_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_sys_message_sys_user_2` FOREIGN KEY (`system_user_id`) REFERENCES `sys_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Copiando dados para a tabela dvi.sys_message: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `sys_message` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_message` ENABLE KEYS */;

-- Copiando estrutura para tabela dvi.sys_notification
DROP TABLE IF EXISTS `sys_notification`;
CREATE TABLE IF NOT EXISTS `sys_notification` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `system_user_id` int(10) unsigned DEFAULT NULL,
  `system_user_to_id` int(10) unsigned DEFAULT NULL,
  `subject` text,
  `message` text,
  `dt_message` text,
  `action_url` text,
  `action_label` text,
  `icon` text,
  `checked` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_sys_notification_sys_user` (`system_user_id`),
  KEY `FK_sys_notification_sys_user_2` (`system_user_to_id`),
  CONSTRAINT `FK_sys_notification_sys_user` FOREIGN KEY (`system_user_id`) REFERENCES `sys_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_sys_notification_sys_user_2` FOREIGN KEY (`system_user_to_id`) REFERENCES `sys_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Copiando dados para a tabela dvi.sys_notification: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `sys_notification` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_notification` ENABLE KEYS */;

-- Copiando estrutura para tabela dvi.sys_preference
DROP TABLE IF EXISTS `sys_preference`;
CREATE TABLE IF NOT EXISTS `sys_preference` (
  `id` text,
  `value` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Copiando dados para a tabela dvi.sys_preference: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `sys_preference` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_preference` ENABLE KEYS */;

-- Copiando estrutura para tabela dvi.sys_program
DROP TABLE IF EXISTS `sys_program`;
CREATE TABLE IF NOT EXISTS `sys_program` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `controller` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=latin1;

-- Copiando dados para a tabela dvi.sys_program: ~37 rows (aproximadamente)
/*!40000 ALTER TABLE `sys_program` DISABLE KEYS */;
INSERT INTO `sys_program` (`id`, `name`, `controller`) VALUES
	(1, 'System Group Form', 'SystemGroupForm'),
	(2, 'System Group List', 'SystemGroupList'),
	(3, 'System Program Form', 'SystemProgramForm'),
	(4, 'System Program List', 'SystemProgramList'),
	(5, 'System User Form', 'SystemUserForm'),
	(6, 'System User List', 'SystemUserList'),
	(7, 'Common Page', 'CommonPage'),
	(8, 'System PHP Info', 'SystemPHPInfoView'),
	(9, 'System ChangeLog View', 'SystemChangeLogView'),
	(10, 'Welcome View', 'WelcomeView'),
	(11, 'System Sql Log', 'SystemSqlLogList'),
	(12, 'System Profile View', 'SystemProfileView'),
	(13, 'System Profile Form', 'SystemProfileForm'),
	(14, 'System SQL Panel', 'SystemSQLPanel'),
	(15, 'System Access Log', 'SystemAccessLogList'),
	(16, 'System Message Form', 'SystemMessageForm'),
	(17, 'System Message List', 'SystemMessageList'),
	(18, 'System Message Form View', 'SystemMessageFormView'),
	(19, 'System Notification List', 'SystemNotificationList'),
	(20, 'System Notification Form View', 'SystemNotificationFormView'),
	(21, 'System Document Category List', 'SystemDocumentCategoryFormList'),
	(22, 'System Document Form', 'SystemDocumentForm'),
	(23, 'System Document Upload Form', 'SystemDocumentUploadForm'),
	(24, 'System Document List', 'SystemDocumentList'),
	(25, 'System Shared Document List', 'SystemSharedDocumentList'),
	(26, 'System Unit Form', 'SystemUnitForm'),
	(27, 'System Unit List', 'SystemUnitList'),
	(28, 'System Access stats', 'SystemAccessLogStats'),
	(29, 'System Preference form', 'SystemPreferenceForm'),
	(30, 'System Support form', 'SystemSupportForm'),
	(31, 'System PHP Error', 'SystemPHPErrorLogView'),
	(32, 'System Database Browser', 'SystemDatabaseExplorer'),
	(33, 'System Table List', 'SystemTableList'),
	(34, 'System Data Browser', 'SystemDataBrowser'),
	(37, 'ContatoForm', 'ContatoForm'),
	(38, 'CityForm', 'CityForm'),
	(39, 'ContatoList', 'ContatoList');
/*!40000 ALTER TABLE `sys_program` ENABLE KEYS */;

-- Copiando estrutura para tabela dvi.sys_unit
DROP TABLE IF EXISTS `sys_unit`;
CREATE TABLE IF NOT EXISTS `sys_unit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Copiando dados para a tabela dvi.sys_unit: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `sys_unit` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_unit` ENABLE KEYS */;

-- Copiando estrutura para tabela dvi.sys_user
DROP TABLE IF EXISTS `sys_user`;
CREATE TABLE IF NOT EXISTS `sys_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `login` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `frontpage_id` int(10) unsigned DEFAULT NULL,
  `system_unit_id` int(10) unsigned DEFAULT NULL,
  `active` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_sys_user_sys_program` (`frontpage_id`),
  KEY `FK_sys_user_sys_unit` (`system_unit_id`),
  CONSTRAINT `FK_sys_user_sys_program` FOREIGN KEY (`frontpage_id`) REFERENCES `sys_program` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_sys_user_sys_unit` FOREIGN KEY (`system_unit_id`) REFERENCES `sys_unit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Copiando dados para a tabela dvi.sys_user: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `sys_user` DISABLE KEYS */;
INSERT INTO `sys_user` (`id`, `name`, `login`, `password`, `email`, `frontpage_id`, `system_unit_id`, `active`) VALUES
	(1, 'Admin', 'admin', '$2y$10$V.oRRHwc.57BiHKOQ0GpSuRgqCUTh5aGx839zC8bVwP1TLOz4AjoW', 'davimenezes.dev@gmail.com', NULL, NULL, 'Y'),
	(2, 'User', 'user', '$2y$10$InHz5RuJhPzb4JHTbTMMA.hcAdSsxsDY9HS7TUgf5FbAOUPTsEHuK', 'user@user.net', 7, NULL, 'Y');
/*!40000 ALTER TABLE `sys_user` ENABLE KEYS */;

-- Copiando estrutura para tabela dvi.sys_user_group
DROP TABLE IF EXISTS `sys_user_group`;
CREATE TABLE IF NOT EXISTS `sys_user_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `system_user_id` int(10) unsigned DEFAULT NULL,
  `system_group_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_sys_user_group_sys_user` (`system_user_id`),
  KEY `FK_sys_user_group_sys_group` (`system_group_id`),
  CONSTRAINT `FK_sys_user_group_sys_group` FOREIGN KEY (`system_group_id`) REFERENCES `sys_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_sys_user_group_sys_user` FOREIGN KEY (`system_user_id`) REFERENCES `sys_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Copiando dados para a tabela dvi.sys_user_group: ~3 rows (aproximadamente)
/*!40000 ALTER TABLE `sys_user_group` DISABLE KEYS */;
INSERT INTO `sys_user_group` (`id`, `system_user_id`, `system_group_id`) VALUES
	(2, 2, 2),
	(3, 1, 1),
	(4, 1, 2);
/*!40000 ALTER TABLE `sys_user_group` ENABLE KEYS */;

-- Copiando estrutura para tabela dvi.sys_user_program
DROP TABLE IF EXISTS `sys_user_program`;
CREATE TABLE IF NOT EXISTS `sys_user_program` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `system_user_id` int(10) unsigned DEFAULT NULL,
  `system_program_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_sys_user_program_sys_user` (`system_user_id`),
  KEY `FK_sys_user_program_sys_program` (`system_program_id`),
  CONSTRAINT `FK_sys_user_program_sys_program` FOREIGN KEY (`system_program_id`) REFERENCES `sys_program` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_sys_user_program_sys_user` FOREIGN KEY (`system_user_id`) REFERENCES `sys_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Copiando dados para a tabela dvi.sys_user_program: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `sys_user_program` DISABLE KEYS */;
INSERT INTO `sys_user_program` (`id`, `system_user_id`, `system_program_id`) VALUES
	(1, 2, 7);
/*!40000 ALTER TABLE `sys_user_program` ENABLE KEYS */;

-- Copiando estrutura para tabela dvi.sys_user_unit
DROP TABLE IF EXISTS `sys_user_unit`;
CREATE TABLE IF NOT EXISTS `sys_user_unit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `system_user_id` int(10) unsigned DEFAULT NULL,
  `system_unit_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_sys_user_unit_sys_user` (`system_user_id`),
  KEY `FK_sys_user_unit_sys_unit` (`system_unit_id`),
  CONSTRAINT `FK_sys_user_unit_sys_unit` FOREIGN KEY (`system_unit_id`) REFERENCES `sys_unit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_sys_user_unit_sys_user` FOREIGN KEY (`system_user_id`) REFERENCES `sys_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Copiando dados para a tabela dvi.sys_user_unit: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `sys_user_unit` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_user_unit` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
