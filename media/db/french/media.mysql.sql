DROP TABLE IF EXISTS `phpboost_media_cat`;
CREATE TABLE `phpboost_media_cat` (
  `id` int(11) NOT NULL auto_increment,
  `id_parent` int(11) NOT NULL default '0',
  `c_order` int(11) unsigned NOT NULL default '0',
  `auth` text,
  `name` varchar(255) NOT NULL default '',
  `visible` tinyint(1) NOT NULL default '0',
  `mime_type` tinyint(1) unsigned NOT NULL default '0',
  `active` int(11) unsigned NOT NULL default '0',
  `description` text,
  `image` varchar(255) NOT NULL default '',
  `num_media` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

INSERT INTO `phpboost_media_cat` VALUES(1, 0, 1, 'a:3:{s:3:"r-1";i:1;s:2:"r0";i:3;s:2:"r1";i:7;}', 'Vid�os de PHPboost', 1, 2, 7914, 'Cette cat�gorie contient des tutoriels vid�os afin de vous montrer certains actions que vous pourrez avoir besoin durant l''utilisation de <a href="http://www.phpboost.com/pages/videos-de-demonstration">PHPboost</a>. Ces vid�os sont en streaming..', '../media/templates/images/video.png', 3);
INSERT INTO `phpboost_media_cat` VALUES(2, 0, 2, 'a:3:{s:3:"r-1";i:1;s:2:"r0";i:3;s:2:"r1";i:7;}', 'D�monstration', 1, 1, 8191, 'Voici une cat�gorie de d�monstration.', '../media/templates/images/audio.png', 1);

DROP TABLE IF EXISTS `phpboost_media`;
CREATE TABLE `phpboost_media` (
  `id` int(11) NOT NULL auto_increment,
  `idcat` int(11) NOT NULL default '0',
  `iduser` int(11) unsigned NOT NULL default '1',
  `timestamp` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '0',
  `contents` text,
  `url` text,
  `mime_type` varchar(255) NOT NULL default '0',
  `infos` smallint(6) NOT NULL default '0',
  `width` mediumint(9) unsigned NOT NULL default '100',
  `height` mediumint(9) unsigned NOT NULL default '100',
  `counter` int(11) unsigned NOT NULL default '0',
  `users_note` text,
  `nbrnote` int(11) unsigned NOT NULL default '0',
  `note` float NOT NULL default '0',
  `nbr_com` int(11) unsigned NOT NULL default '0',
  `lock_com` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idcat` (`idcat`),
  FULLTEXT KEY `name` (`name`),
  FULLTEXT KEY `contents` (`contents`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

INSERT INTO `phpboost_media` VALUES(1, 1, 1, 1242287109, 'Sauvegarde et restauration de sa base de donn�es', 'Il est important de r�aliser r�guli�rement des sauvegardes de sa base de donn�es.<br />\r\n<br />\r\nVoici une petite vid�o vous montrant comment sauvegarder et restaurer sa base de donn�e � l''aide de l''utilitaire de PHPBoost.<br />\r\n<br />\r\nBonne visualisation  <img src="/images/smileys/sourire.gif" alt=":)" class="smiley" /> .', 'http://www.ptithom.net/documents/phpboost/videos/bdd/sauv_restau_bdd.flv', 'video/x-flv', 2, 640, 438, 0, '', 0, 0, 0, 0);
INSERT INTO `phpboost_media` VALUES(2, 1, 1, 1242287212, 'T�l�charger PHPBoost et transf�rer les fichiers sur votre serveur FTP', 'Voici une petite vid�o vous montrant comment t�l�charger et envoyer les fichiers de PHPBoost sur votre serveur FTP.<br />\r\n<br />\r\nBonne visualisation  <img src="/images/smileys/sourire.gif" alt=":)" class="smiley" /> .', 'http://www.ptithom.net/documents/phpboost/videos/transfert_fichiers/transfert_fichiers_serveur.flv', 'video/x-flv', 2, 640, 598, 0, '', 0, 0, 0, 0);
INSERT INTO `phpboost_media` VALUES(3, 1, 1, 1242287275, 'Installation de PHPBoost version 3', 'Vid�o vous expliquant comment installer PHPBoost version 3.', 'http://www.ptithom.net/documents/phpboost/videos/installateurv3.flv', 'video/x-flv', 2, 640, 598, 0, '', 0, 0, 0, 0);
INSERT INTO `phpboost_media` VALUES(4, 2, 1, 1242287543, 'La Moldau (Smetana)', 'Bed&#345;ich Smetana, n� le 2 mars 1824 � Litomy&#353;l et mort le 12 mai 1884 � Prague, est un compositeur tch�que. Il est c�l�bre pour son po�me symphonique Vltava (la Moldau en allemand), le second d''un cycle de six intitul� M� vlast ("Ma Patrie"), ainsi que pour son op�ra La Fianc�e vendue.', 'http://www.alsacreations.fr/mediaflash/mp3/moldau.mp3', 'audio/mpeg', 2, 0, 0, 0, '', 0, 0, 0, 0);




