<?php






























define('PATH_TO_ROOT','../../..');
define('NO_SESSION_LOCATION',true);

include_once(PATH_TO_ROOT.'/kernel/begin.php');
define('TITLE','');
include_once(PATH_TO_ROOT.'/kernel/header_no_display.php');


$calendar_type=!empty($_GET['date'])?'timestamp':'date';
$field=!empty($_GET['field'])?trim($_GET['field']):'calendar';
$input_field=!empty($_GET['input_field'])?trim($_GET['input_field']):'';
$lyear=!empty($_GET['lyear'])?'&amp;lyear=1':'';

$Template->set_filenames(array(
'calendar'=>'framework/mini_calendar_response.tpl'
));


if($calendar_type=='date')
{
$year=gmdate_format('Y');
$month=gmdate_format('n');
$day=gmdate_format('j');

$year=!empty($_GET['y'])?numeric($_GET['y']):$year;
$month=!empty($_GET['m'])?numeric($_GET['m']):$month;
$day=!empty($_GET['d'])?numeric($_GET['d']):$day;
if(!checkdate($month,$day,$year))
{
list($year,$month,$day)=array(gmdate_format('Y'),gmdate_format('n'),gmdate_format('j'));
}
$bissextile=(date("L",mktime(0,0,0,1,1,$year))==1)?29:28;

$array_month=array(31,$bissextile,31,30,31,30,31,31,30,31,30,31);
$array_l_month=array($LANG['january'],$LANG['february'],$LANG['march'],$LANG['april'],$LANG['may'],$LANG['june'],
$LANG['july'],$LANG['august'],$LANG['september'],$LANG['october'],$LANG['november'],$LANG['december']);
$month_day=$array_month[$month-1];

$Template->assign_vars(array(
'FIELD'=>$field,
'INPUT_FIELD'=>$input_field,
'LYEAR'=>$lyear,
'MONTH'=>$month,
'YEAR'=>$year,
'U_PREVIOUS'=>($month==1)?'input_field='.$input_field.'&amp;field='.$field.$lyear.'&amp;d='.$day.'&amp;m=12&amp;y='.($year-1):'input_field='.$input_field.'&amp;input_field='.$input_field.'&amp;field='.$field.$lyear.'&amp;d=1&amp;m='.($month-1).'&amp;y='.$year,
'U_NEXT'=>($month==12)?'input_field='.$input_field.'&amp;field='.$field.$lyear.'&amp;d='.$day.'&amp;m=1&amp;y='.($year+1):'input_field='.$input_field.'&amp;field='.$field.$lyear.'&amp;d=1&amp;m='.($month+1).'&amp;y='.$year
));


for($i=1;$i<=12;$i++)
{
$selected=($month==$i)?'selected="selected"':'';
$Template->assign_block_vars('month',array(
'MONTH'=>'<option value="'.$i.'" '.$selected.'>'.htmlentities($array_l_month[$i-1]).'</option>'
));
}
for($i=1900;$i<=2037;$i++)
{
$selected=($year==$i)?'selected="selected"':'';
$Template->assign_block_vars('year',array(
'YEAR'=>'<option value="'.$i.'" '.$selected.'>'.$i.'</option>'
));
}


$array_l_days=array($LANG['monday'],$LANG['tuesday'],$LANG['wenesday'],$LANG['thursday'],$LANG['friday'],$LANG['saturday'],
$LANG['sunday']);
foreach($array_l_days as $l_day)
{
$Template->assign_block_vars('day',array(
'L_DAY'=>'<td style="width:25px;border-top:1px solid black;border-bottom:1px solid black"><span class="text_small">'.$l_day.'</span></td>'
));
}


$first_day=@gmdate_format('w',@mktime(1,0,0,$month,1,$year));
if($first_day==0)
{
$first_day=7;
}


$format='';
$array_date=explode('/',$LANG['date_format_short']);
for($i=0;$i<3;$i++)
{
switch($array_date[$i])
{
case 'd':
$format.="%1\$s";
break;
case 'm':
$format.="%2\$s";
break;
case 'y':
$format.="%3\$s";
break;
}
$format.=($i!=2)?'/':'';
}


$year=!empty($lyear)?$year:substr($year,2,2);
$month=($month<10&&substr($month,0,1)!=0)?'0'.$month:$month;
$j=1;
$last_day=($month_day+$first_day);
for($i=1;$i<=42;$i++)
{
if($i>=$first_day&&$i<$last_day)
{
$class=($day==$j)?' style="padding:0px;" class="row2"':' style="padding:0px;" class="row3"';
$style=($day==$j)?'border: 1px inset black;':'border: 1px outset black;';
$date=sprintf($format,(($j<10&&substr($j,0,1)!=0)?'0'.$j:$j),$month,$year);

$contents='<td'.$class.'><div style="'.$style.'padding:2px;"><a href="javascript:insert_date(\''.$input_field.'\', \''.$date.'\');">'.$j.'</a></div></td>';
$j++;
}
else
{
$contents='<td style="padding:0px;height:21px;" class="row3">&nbsp;</td>';
}

$Template->assign_block_vars('calendar',array(
'DAY'=>$contents,
'TR'=>(($i%7)==0&&$i!=42)?'</tr><tr style="text-align:center;">':''
));
}
}
else
{

}

$Template->pparse('calendar');

include_once(PATH_TO_ROOT.'/kernel/footer_no_display.php');
?>
