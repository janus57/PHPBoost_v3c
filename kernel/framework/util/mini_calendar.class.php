<?php



























import('util/date');







class MiniCalendar
{





function MiniCalendar($form_name)
{

static $num_instance=0;
$this->form_name=$form_name;
$this->num_instance=++$num_instance;
$this->date=new Date(DATE_NOW);
}





function set_date($date)
{
$this->date=$date;
}







function set_style($style)
{
$this->style=$style;
}





function get_date()
{
return $this->date;
}





function display()
{
global $CONFIG;


static $js_inclusion_already_done=false;


$template=new Template('framework/mini_calendar.tpl');

$template->assign_vars(array(
'DEFAULT_DATE'=>$this->date->format(DATE_FORMAT_SHORT),
'CALENDAR_ID'=>'calendar_'.$this->num_instance,
'CALENDAR_NUMBER'=>(string)$this->num_instance,
'DAY'=>$this->date->get_day(),
'MONTH'=>$this->date->get_month(),
'YEAR'=>$this->date->get_year(),
'FORM_NAME'=>$this->form_name,
'CALENDAR_STYLE'=>$this->style,
'C_INCLUDE_JS'=>!$js_inclusion_already_done
));

$js_inclusion_already_done=true;

return $template->parse(TEMPLATE_STRING_MODE);
}







function retrieve_date($calendar_name)
{
global $LANG;
return new Date(DATE_FROM_STRING,TIMEZONE_AUTO,retrieve(REQUEST,$calendar_name,'',TSTRING_UNCHANGE),$LANG['date_format_short']);
}

# Private #



var $num_instance=0;



var $style='';



var $form_name='';



var $date;
}


?>