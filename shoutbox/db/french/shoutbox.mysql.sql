DROP TABLE IF EXISTS `phpboost_shoutbox`;
CREATE TABLE `phpboost_shoutbox` (
  `id` int(11) NOT NULL auto_increment,
  `login` varchar(150) NOT NULL default '',
  `user_id` int(11) NOT NULL default '0',
  `level` tinyint(1) NOT NULL default '0',
  `contents` text,
  `timestamp` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `timestamp` (`timestamp`)
) ENGINE=MyISAM;

INSERT INTO `phpboost_shoutbox` VALUES (1, 'Equipe PHPBoost', -1, -1, 'l''�quipe de PHPBoost vous souhaite la bienvenue!', unix_timestamp(current_timestamp));