<?php



























define('DISTRIBUTION_NAME', 'Community');


define('DISTRIBUTION_DESCRIPTION', '<img src="distribution/community.png" alt="" style="float:right;padding-right:35px"/>
<p>You are going to install the <strong>Community</strong> distribution of PHPBoost.</p>
<p>This distribution is ideal to create and manage a community. Some discussion tools (such as the forum or the shoutbox) and contribution tools (wiki for instance) will enable the community members to participate.</p>');


define('DISTRIBUTION_THEME', 'extends');


define('DISTRIBUTION_START_PAGE', '/news/news.php');


define('DISTRIBUTION_ENABLE_USER', true);


$DISTRIBUTION_MODULES = array('articles', 'connect', 'contact', 'database', 'news', 'pages', 'search', 'web', 'download', 'wiki', 'shoutbox', 'faq', 'forum', 'guestbook', 'online', 'poll');

?>
