<?php

























import('builder/form/form_field');












class FormCheckboxOption extends FormField
{
function FormCheckboxOption($field_options)
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
case 'checked':
$this->option_checked=$value;
break;
default:
$this->throw_error(sprintf('Unsupported option %s in field option type '.__CLASS__,$attribute),E_USER_NOTICE);
}
}
}




function display()
{
$option='<label><input type="checkbox" ';
$option.=!empty($this->field_name)?'name="'.$this->field_name.'" ':'';
$option.=!empty($this->field_value)?'value="'.$this->field_value.'" ':'';
$option.=(boolean)$this->option_checked?'checked="checked" ':'';
$option.='/> '.$this->option_title.'</label><br />'."\n";

return $option;
}

var $option_title='';
var $option_checked=false;
}

?>