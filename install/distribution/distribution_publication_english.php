<?php



























define('DISTRIBUTION_NAME', 'Publication');


define('DISTRIBUTION_DESCRIPTION', '<img src="distribution/publication.png" alt="" style="float:right;padding-right:35px"/>
<p>You are going to install the <strong>Publication</strong> distribution of PHPBoost.</p>
<p>This distribution is ideal to create a website which will purpose some content to visitors, that can be text, pictures or others kinds of content.</p>');


define('DISTRIBUTION_THEME', 'publishing');


define('DISTRIBUTION_START_PAGE', '/news/news.php');


define('DISTRIBUTION_ENABLE_USER', true);


$DISTRIBUTION_MODULES = array('articles', 'contact', 'connect', 'database', 'guestbook', 'news', 'pages', 'search', 'web');

?>
