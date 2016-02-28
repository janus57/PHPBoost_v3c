<?php



























define('DISTRIBUTION_NAME', 'Publication');


define('DISTRIBUTION_DESCRIPTION', '<img src="distribution/publication.png" alt="" style="float:right;padding-right:35px"/>
<p>Vous allez installer la distribution <strong>Publication</strong> de PHPBoost.</p>
<p>Cette distribution est idéale pour créer un site qui servira à proposer aux visiteurs du contenu, que ce soit sous forme de texte, d\'images ou autres.</p>');


define('DISTRIBUTION_THEME', 'publishing');


define('DISTRIBUTION_START_PAGE', '/news/news.php');


define('DISTRIBUTION_ENABLE_USER', true);


$DISTRIBUTION_MODULES = array('articles', 'contact', 'connect', 'database', 'guestbook', 'news', 'pages', 'search', 'web');

?>
