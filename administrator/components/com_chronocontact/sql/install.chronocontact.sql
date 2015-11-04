--
-- Table structure for table `#__chronoengine_extensions`
--

CREATE TABLE IF NOT EXISTS `#__chronoengine_extensions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `addon_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `ordering` int(4) NOT NULL,
  `settings` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__chronoengine_forms`
--

CREATE TABLE IF NOT EXISTS `#__chronoengine_forms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `form_type` tinyint(1) NOT NULL,
  `content` longtext NOT NULL,
  `extras` longtext NOT NULL,
  `config` longtext NOT NULL,
  `wizardcode` longtext NOT NULL,
  `events_actions_map` longtext NOT NULL,
  `params` longtext NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `app` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;