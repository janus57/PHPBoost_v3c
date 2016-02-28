<?php



























require_once('../kernel/begin.php');
require_once('../kernel/header.php');

$tpl = new Template('member/csrf-attack.tpl');
$tpl->assign_vars(array(
    'L_ATTACK_EXPLAIN' => $LANG['csrf_attack'],
    'L_PREVIOUS' => $LANG['previous'],
));
$tpl->parse();

require_once('../kernel/footer.php');

?>
