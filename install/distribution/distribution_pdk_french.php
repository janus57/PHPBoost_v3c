<?php



























define('DISTRIBUTION_NAME', 'PDK');


define('DISTRIBUTION_DESCRIPTION', '<p>Vous allez installer la distribution <strong><acronym title="PHPBoost Development Kit">PDK</acronym></strong> de PHPBoost.</p>
<p>Cette distribution est parfaitement adapt�e aux d�veloppeurs qui souhaitent d�velopper un module afin de l\'int�grer � PHPBoost. Elle contient un outil de gestion de la base de donn�es ainsi que la documentation du framework de PHPBoost.</p>');


define('DISTRIBUTION_THEME', 'extends');


define('DISTRIBUTION_START_PAGE', '/doc/3.0/index.php');


define('DISTRIBUTION_ENABLE_USER', true);


$DISTRIBUTION_MODULES = array('connect', 'database', 'doc');

?>
