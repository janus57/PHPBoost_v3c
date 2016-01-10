DROP TABLE IF EXISTS `phpboost_web`;
CREATE TABLE `phpboost_web` (
	`id` int(11) NOT NULL auto_increment,
	`idcat` int(11) NOT NULL default '0',
	`title` varchar(100) NOT NULL default '',
	`contents` text,
	`url` text,
	`compt` int(11) NOT NULL default '0',
	`aprob` tinyint(1) NOT NULL default '1',
	`timestamp` int(11) NOT NULL default '0',
	`users_note` text,
	`nbrnote` mediumint(9) NOT NULL default '0',
	`note` float NOT NULL default '0',
	`nbr_com` int(11) unsigned NOT NULL default '0',
	`lock_com` tinyint(1) NOT NULL default '0',
	PRIMARY KEY	(`id`),
	KEY `idcat` (`idcat`)
) ENGINE=MyISAM;

INSERT INTO `phpboost_web` VALUES (1, 1, 'PHPBoost', '<p class="float_right"><img src="../templates/base/theme/images/phpboost_box_3_0.png" alt="" class="valign_" /></p><br />\r\nPHPBoost est un CMS (Content Managing System ou syst�me de gestion de contenu) fran�ais. Ce logiciel permet � n''importe qui de cr�er son site de fa�on tr�s simple, tout est assist�. Con�u pour satisfaire les d�butants, il devrait aussi ravir les utilisateurs exp�riment�s qui souhaiteraient pousser son fonctionnement ou encore d�velopper leurs propres modules.<br />\r\nPHPBoost est un logiciel libre distribu� sous la licence GPL.<br />\r\n<br />\r\nComme son nom l''indique, PHPBoost utilise le PHP comme langage de programmation principal, mais, comme toute application Web, il utilise du XHTML et des CSS pour la mise en forme des pages, du JavaScript pour ajouter une touche dynamique sur les pages, ainsi que du SQL pour effectuer des op�rations dans la base de donn�es. Il s''installe sur un serveur Web et se param�tre � distance.<br />\r\n<br />\r\nComme pour une grande majorit� de logiciels libres, la communaut� de PHPBoost lui permet d''avoir � la fois une fiabilit� importante car beaucoup d''utilisateurs ont test� chaque version et les ont ainsi approuv�es. Il b�n�ficie aussi par ailleurs d''une �volution rapide car nous essayons d''�tre le plus possible � l''�coute des commentaires et des propositions de chacun. M�me si tout le monde ne participe pas � son d�veloppement, beaucoup de gens nous ont aid�s, rien qu''en nous donnant des id�es, nous sugg�rant des modifications, des fonctionnalit�s suppl�mentaires.<br />\r\n<br />\r\nSi vous ne deviez retenir que quelques points essentiels sur le projet, ce seraient ceux-ci :<br />\r\n<br />\r\n    * Projet Open Source sous licence GNU/GPL<br />\r\n    * Code XHTML 1.0 strict et s�mantique<br />\r\n    * Multilangue<br />\r\n    * Facilement personnalisable gr�ce aux th�mes et templates<br />\r\n    * Gestion fine des droits et des groupes multiples pour chaque utilisateur<br />\r\n    * Url rewriting<br />\r\n    * Installation et mise � jour automatis�es des modules et du noyau<br />\r\n    * Aide au d�veloppement de nouveaux modules gr�ce au framework de PHPBoost', 'http://www.phpboost.com', 0, 1, 1234956484, '0', 0, 0, 0, 0);


DROP TABLE IF EXISTS `phpboost_web_cat`;
CREATE TABLE `phpboost_web_cat` (
	`id` int(11) NOT NULL auto_increment,
	`class` int(11) NOT NULL default '0',
	`name` varchar(150) NOT NULL default '',
	`contents` text,
	`icon` varchar(255) NOT NULL default '',
	`aprob` tinyint(1) NOT NULL default '0',
	`secure` tinyint(1) NOT NULL default '0',
	PRIMARY KEY	(`id`),
	KEY `class` (`class`)
) ENGINE=MyISAM;

INSERT INTO `phpboost_web_cat` VALUES (1, 1, 'Cat�gorie de test', 'Liens de test', 'web.png', 1, -1);