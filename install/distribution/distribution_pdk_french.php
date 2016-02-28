<?php



























define('DISTRIBUTION_NAME', 'PDK');


define('DISTRIBUTION_DESCRIPTION', '<p>Vous allez installer la distribution <strong><acronym title="PHPBoost Development Kit">PDK</acronym></strong> de PHPBoost.</p>
<p>Cette distribution est parfaitement adaptée aux développeurs qui souhaitent développer un module afin de l\'intégrer à PHPBoost. Elle contient un outil de gestion de la base de données ainsi que la documentation du framework de PHPBoost.</p>');


define('DISTRIBUTION_THEME', 'extends');


define('DISTRIBUTION_START_PAGE', '/doc/3.0/index.php');


define('DISTRIBUTION_ENABLE_USER', true);


$DISTRIBUTION_MODULES = array('connect', 'database', 'doc');

?>
