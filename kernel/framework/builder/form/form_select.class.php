<?php

























import('builder/form/form_field');
import('builder/form/form_select_option');











class FormSelect extends FormField
{
function FormSelect()
{
$fieldId=func_get_arg(0);
$field_options=func_get_arg(1);

parent::FormField($fieldId,$field_options);
foreach($field_options as $attribute=>$value)
{
$attribute=strtolower($attribute);
switch($attribute)
{
case 'multiple':
$this->field_multiple=$value;
break;
default:
$this->throw_error(sprintf('Unsupported option %s with field option '.__CLASS__,$attribute),E_USER_NOTICE);
}
}

$nbr_arg=func_num_args()-1;
for($i=2;$i<=$nbr_arg;$i++)
{
$option=func_get_arg($i);
$this->add_errors($option->get_errors());
$this->field_options[]=$option;
}
}





function add_option(&$option)
{
$this->field_options[]=$option;
}




function display()
{
$Template=new Template('framework/builder/forms/field_select.tpl');

if($this->field_multiple)
$field='<select name="'.$this->field_name.'[]" multiple="multiple">'.$this->field_options.'</select>';
else
$field='<select name="'.$this->field_name.'">'.$this->field_options.'</select>';

$Template->assign_vars(array(
'ID'=>$this->field_id,
'C_SELECT_MULTIPLE'=>$this->field_multiple,
'L_FIELD_NAME'=>$this->field_name,
'L_FIELD_TITLE'=>$this->field_title,
'L_EXPLAIN'=>$this->field_sub_title,
'L_REQUIRE'=>$this->field_required?'* ':''
));

foreach($this->field_options as $Option)
{
$Option->field_name=$this->field_name;
$Template->assign_block_vars('field_options',array(
'OPTION'=>$Option->display(),
));
}

return $Template->parse(TEMPLATE_STRING_MODE);
}

var $field_options=array();
var $field_multiple=false;
}

?>