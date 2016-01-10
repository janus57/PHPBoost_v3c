<?php

























import('builder/form/form_field');
import('builder/form/form_checkbox_option');







class FormCheckbox extends FormField
{
function FormCheckbox()
{
$fieldId=func_get_arg(0);
$field_options=func_get_arg(1);

parent::FormField($fieldId,$field_options);
foreach($field_options as $attribute=>$value)
$this->throw_error(sprintf('Unsupported option %s with field '.__CLASS__,strtolower($attribute)),E_USER_NOTICE);

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
$Template=new Template('framework/builder/forms/field_box.tpl');

$Template->assign_vars(array(
'ID'=>$this->field_id,
'FIELD'=>$this->field_options,
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
}

?>