<?php

























import('builder/form/form_field');













class FormTextEdit extends FormField
{
function FormTextEdit($fieldId,$field_options)
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
case 'maxlength':
$this->field_maxlength=$value;
break;
default:
$this->throw_error(sprintf('Unsupported option %s with field '.__CLASS__,$attribute),E_USER_NOTICE);
}
}
}




function display()
{
$Template=new Template('framework/builder/forms/field.tpl');

$field='<input type="text" ';
$field.=!empty($this->field_size)?'size="'.$this->field_size.'" ':'';
$field.=!empty($this->field_maxlength)?'maxlength="'.$this->field_maxlength.'" ':'';
$field.=!empty($this->field_name)?'name="'.$this->field_name.'" ':'';
$field.=!empty($this->field_id)?'id="'.$this->field_id.'" ':'';
$field.=!empty($this->field_value)?'value="'.$this->field_value.'" ':'';
$field.=!empty($this->field_css_class)?'class="'.$this->field_css_class.'" ':'';
$field.=!empty($this->field_on_blur)?'onblur="'.$this->field_on_blur.'" ':'';
$field.='/>';

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
var $field_maxlength='';
}

?>