<?php



























define('DISTRIBUTION_NAME', 'Communauté');


define('DISTRIBUTION_DESCRIPTION', '<img src="distribution/community.png" alt="" style="float:right;padding-right:35px"/>
<p>Vous allez installer la distribution <strong>Communauté</strong> de PHPBoost.</p>
<p>Cette distribution est idéale pour créer et gérer une communauté en ligne. Des outils de discussion (tels que le forum ou la discussion) ainsi que des outils de contribution (wiki) vous permettront à vos utilisateurs d\'interagir.</p>');


define('DISTRIBUTION_THEME', 'extends');


define('DISTRIBUTION_START_PAGE', '/news/news.php');


define('DISTRIBUTION_ENABLE_USER', true);


$DISTRIBUTION_MODULES = array('articles', 'connect', 'contact', 'database', 'news', 'pages', 'search', 'web', 'download', 'wiki', 'shoutbox', 'faq', 'forum', 'guestbook', 'online', 'poll');

?>
