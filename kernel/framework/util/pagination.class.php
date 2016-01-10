<?php


























define('NO_PREVIOUS_NEXT_LINKS',false);
define('LINK_START_PAGE',false);








class Pagination
{
## Public Methods ##



function Pagination()
{
}















function display($path,$total_msg,$var_page,$nbr_msg_page,$nbr_max_link,$font_size=11,$previous_next=true,$link_start_page=true)
{
if($total_msg>$nbr_msg_page)
{

$links='';

$this->page=$this->_get_var_page($var_page);
$nbr_page=ceil($total_msg/$nbr_msg_page);
if($nbr_page==1)
{
return '';
}

$this->page=$this->_check_page($nbr_page);


if($this->page!=1&&$nbr_page>1&&$previous_next===true)
{
$links.='&nbsp;<a style="font-size:'.$font_size.'px;" href="'.sprintf($path,$this->page-1).'">&laquo;</a>&nbsp;';
}

$page_max_end=$nbr_page-$this->nbr_end_links;
$page_current_max=$this->page+$nbr_max_link;
$page_current_min=$this->page-$nbr_max_link;

for($i=1;$i<=$nbr_page;$i++)
{
if($i==$this->page&&$link_start_page)
{
$links.='&nbsp;<span class="text_strong" style="font-size:'.$font_size.'px;text-decoration: underline;">'.$this->page.'</span>&nbsp;';
}
elseif($i<=$this->nbr_start_links || $i>$page_max_end ||($i<=$page_current_max&&$i>=$page_current_min))
{
$links.='&nbsp;<a style="font-size:'.$font_size.'px;" href="'.sprintf($path,$i).'">'.$i.'</a>&nbsp;';
}
else
{
if($i>=$this->nbr_start_links&&$i<=$page_current_min)
{
$i=$page_current_min-1;
$links.='...';
}
elseif($i>=$page_current_max&&$i<=$page_max_end)
{
$i=$page_max_end;
$links.='...';
}
}
}


if($this->page!=$nbr_page&&$nbr_page>1&&$previous_next===true)
{
$links.='&nbsp;<a style="font-size:'.$font_size.'px;" href="'.sprintf($path,$this->page+1).'">&raquo;</a>';
}

return $links;
}
else
return '';
}











function get_first_msg($nbr_msg_page,$var_page)
{
$page=!empty($_GET[$var_page])?numeric($_GET[$var_page]):1;
$page=$page>0?$page:1;
return(($page-1)*$nbr_msg_page);
}




function get_current_page()
{
return $this->_get_var_page($this->var_page);
}

## Private Methods ##




function _get_var_page($var_page)
{
$_GET[$var_page]=isset($_GET[$var_page])?numeric($_GET[$var_page]):0;
if(!empty($_GET[$var_page]))
{
return $_GET[$var_page];
}
else
{
return 1;
}
}






function _check_page($nbr_page)
{
global $Errorh;

if($this->page<0)
{
$Errorh->handler('e_unexist_page',E_USER_REDIRECT);
}
elseif($this->page>$nbr_page)
{
$Errorh->handler('e_unexist_page',E_USER_REDIRECT);
}

return $this->page;
}

## Private Attribute ##
var $page;
var $nbr_start_links=3;
var $nbr_end_links=3;
var $var_page;
}

?>