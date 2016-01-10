DROP TABLE IF EXISTS `phpboost_faq`;
CREATE TABLE `phpboost_faq` (
  `id` int(11) NOT NULL auto_increment,
  `idcat` int(11) NOT NULL default '0',
  `q_order` int(11) NOT NULL default '0',
  `question` varchar(255) NOT NULL default '',
  `answer` text,
  `user_id` int(11) NOT NULL default '0',
  `timestamp` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `question` (`question`),
  FULLTEXT KEY `answer` (`answer`),
  FULLTEXT KEY `question_2` (`question`,`answer`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `phpboost_faq_cats`;
CREATE TABLE `phpboost_faq_cats` (
  `id` int(11) NOT NULL auto_increment,
  `id_parent` int(11) NOT NULL default '0',
  `c_order` int(11) unsigned NOT NULL default '0',
  `auth` text,
  `name` varchar(255) NOT NULL default '',
  `visible` tinyint(1) NOT NULL default '0',
  `display_mode` tinyint(2) NOT NULL default '0',
  `description` text,
  `image` varchar(255) NOT NULL default '',
  `num_questions` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

INSERT INTO `phpboost_faq_cats` (`id`, `id_parent`, `c_order`, `auth`, `name`, `visible`, `display_mode`, `description`, `image`, `num_questions`) VALUES
(1, 0, 1, NULL, 'PHPBoost', 1, 0, 'Des questions sur PHPBoost?', 'faq.png', 1),
(2, 0, 2, NULL, 'Dictionnaire', 1, 0, '', 'faq.png', 1);

INSERT INTO `phpboost_faq` (`id`, `idcat`, `q_order`, `question`, `answer`, `user_id`, `timestamp`) VALUES
(1, 2, 1, 'Qu''est ce qu''un CMS?', 'C''est un syst�me de gestion de contenu ou SGC en fran�ais (en anglais :  Content Management Systems)', 1, 1242496334),
(2, 1, 1, 'Qu''est-ce que PHPBoost ?', 'PHPBoost est un CMS (Content Management System ou syst�me de gestion de contenu) fran�ais. Ce logiciel permet � n''importe qui de cr�er son site de fa�on tr�s simple, tout est assist�. Con�u pour satisfaire les d�butants, il devrait aussi ravir les utilisateurs exp�riment�s qui souhaiteraient pousser son fonctionnement ou encore d�velopper leurs propres modules.', 1, 1242496518);
