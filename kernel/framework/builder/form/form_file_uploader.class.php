<?php

























import('builder/form/form_field');











class FormFileUploader extends FormField
{
function FormFileUploader($fieldId,$field_options)
{
parent::FormField($fieldId,$field_options);

foreach($field_options as $attribute=>$value)
{
$attribute=strtolower($attribute);
switch($attribute)
{
case 'size':
$this->field_size=$value;
break;
default:
$this->throw_error(sprintf('Unsupported option %s with field '.__CLASS__,$attribute),E_USER_NOTICE);
}
}
}




function display()
{
$Template=new Template('framework/builder/forms/field.tpl');

$field='<input type="file" ';
$field.=!empty($this->field_size)?'size="'.$this->field_size.'" ':'';
$field.=!empty($this->field_name)?'name="'.$this->field_name.'" ':'';
$field.=!empty($this->field_id)?'id="'.$this->field_id.'" ':'';
$field.=!empty($this->field_css_class)?'class="'.$this->field_css_class.'" ':'';
$field.='/>
		<input name="max_file_size" value="2000000" type="hidden">';

$Template->assign_vars(array(
'ID'=>$this->field_id,
'FIELD'=>$field,
'L_FIELD_TITLE'=>$this->field_title,
'L_EXPLAIN'=>$this->field_sub_title,
'L_REQUIRE'=>$this->field_required?'* ':''
));

return $Template->parse(TEMPLATE_STRING_MODE);
}

var $field_size='';
}

?>