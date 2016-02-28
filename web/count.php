<?php


























require_once('../kernel/begin.php');

$idweb = retrieve(GET, 'id', 0);
if (!empty($idweb))
	$Sql->query_inject("UPDATE " . PREFIX . "web SET compt = compt + 1 WHERE id = '" . $idweb . "'", __LINE__, __LINE__); 


$url_web = $Sql->query("SELECT url FROM " . PREFIX . "web WHERE id = '" . $idweb . "'", __LINE__, __FILE__);
if (!empty($url_web))
	redirect($url_web);

?>
