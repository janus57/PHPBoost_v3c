<?php


































class BreadCrumb
{



function BreadCrumb()
{
}






function add($text,$target='')
{
if(!empty($text))
{
$this->array_links[]=array($text,$target);
return true;
}
else
{
return false;
}
}





function reverse()
{
$this->array_links=array_reverse($this->array_links);
}




function remove_last()
{
array_pop($this->array_links);
}




function display()
{
global $Template,$CONFIG,$LANG;

if(empty($this->array_links))
{
$this->add(stripslashes(TITLE),HOST.SCRIPT.SID);
}

$start_page='';
if(!empty($CONFIG['server_name']))$start_page.=$CONFIG['server_name'];
if(!empty($CONFIG['server_path']))$start_page.=$CONFIG['server_path'].'/';

$Template->assign_vars(array(
'START_PAGE'=>$start_page,
'L_INDEX'=>$LANG['home']
));

foreach($this->array_links as $key=>$array)
{
$Template->assign_block_vars('link_bread_crumb',array(
'URL'=>$array[1],
'TITLE'=>$array[0]
));
}
}




function clean()
{
$this->array_links=array();
}

## Attributs protégés #



var $array_links=array();
}

?>