CREATE TABLE IF NOT EXISTS `#__rsseo_competitors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `pagerank` int(11) NOT NULL DEFAULT '-1',
  `alexa` int(11) NOT NULL DEFAULT '-1',
  `technorati` int(11) NOT NULL DEFAULT '-1',
  `googlep` int(11) NOT NULL DEFAULT '-1',
  `bingp` int(11) NOT NULL DEFAULT '-1',
  `googleb` int(11) NOT NULL DEFAULT '-1',
  `bingb` int(11) NOT NULL DEFAULT '-1',
  `dmoz` int(1) NOT NULL DEFAULT '-1',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tags` text NOT NULL,
  PRIMARY KEY (`id`)
);


CREATE TABLE IF NOT EXISTS `#__rsseo_keywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` varchar(255) NOT NULL,
  `importance` enum('low','relevant','important','critical') NOT NULL,
  `position` int(11) NOT NULL,
  `lastposition` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `bold` int(2) NOT NULL,
  `underline` int(2) NOT NULL,
  `limit` int(3) NOT NULL,
  `attributes` text NOT NULL,
  `link` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Keyword` (`keyword`)
);


CREATE TABLE IF NOT EXISTS `#__rsseo_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `title` text NOT NULL,
  `keywords` text NOT NULL,
  `keywordsdensity` text NOT NULL,
  `description` text NOT NULL,
  `sitemap` tinyint(1) NOT NULL,
  `insitemap` int(2) NOT NULL,
  `crawled` tinyint(1) NOT NULL,
  `date` datetime NOT NULL,
  `modified` int(3) NOT NULL,
  `level` tinyint(4) NOT NULL,
  `grade` float(10,2) NOT NULL DEFAULT '-1.00',
  `params` text NOT NULL,
  `densityparams` text NOT NULL,
  `canonical` varchar(500) NOT NULL,
  `robots` varchar(255) NOT NULL,
  `frequency` varchar(255) NOT NULL,
  `priority` varchar(255) NOT NULL,
  `imagesnoalt` text NOT NULL,
  `imagesnowh` text NOT NULL,
  `published` tinyint(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `PageURL` (`url`)
);


CREATE TABLE IF NOT EXISTS `#__rsseo_redirects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` varchar(255) NOT NULL,
  `to` varchar(255) NOT NULL,
  `type` enum('301','302') NOT NULL,
  `published` int(2) NOT NULL,
  PRIMARY KEY (`id`)
);

INSERT IGNORE INTO `#__rsseo_pages` (`id`, `url`, `title`, `keywords`, `keywordsdensity`, `description`, `sitemap`, `insitemap`, `crawled`, `date`, `modified`, `level`, `grade`, `params`, `densityparams`, `canonical`, `robots`, `frequency`, `priority`, `imagesnoalt`, `imagesnowh`, `published`) VALUES (1, '', '', '', '', '', 0, 0, 0, NOW(), 0, 0, 0, '', '', '', '', '', '', '', '', 1);