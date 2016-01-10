<?php

























import('builder/form/form_field');







class FormSelectOption extends FormField
{
function FormSelectOption($field_options)
{
parent::FormField('',$field_options);

foreach($field_options as $attribute=>$value)
{
$attribute=strtolower($attribute);
switch($attribute)
{
case 'optiontitle':
$this->option_title=$value;
break;
case 'selected':
$this->option_selected=$value;
break;
default:
$this->throw_error(sprintf('Unsupported option %s with field option '.__CLASS__,$attribute),E_USER_NOTICE);
}
}
}




function display()
{
$option='<option ';
$option.=!empty($this->field_value)?'value="'.$this->field_value.'"':'';
$option.=(boolean)$this->option_selected?' selected="selected"':'';
$option.='> '.$this->option_title.'</option>'."\n";

return $option;
}

var $option_title='';
var $option_selected=false;
}

?>