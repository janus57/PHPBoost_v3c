<?php

























import('builder/form/form_field');







class FormTextarea extends FormField
{
function FormTextarea($fieldId,$fieldOptions)
{
parent::FormField($fieldId,$fieldOptions);
foreach($fieldOptions as $attribute=>$value)
{
$attribute=strtolower($attribute);
switch($attribute)
{
case 'rows':
$this->field_rows=$value;
break;
case 'cols':
$this->field_cols=$value;
break;
case 'editor':
$this->field_editor=$value;
break;
case 'forbiddentags':
$this->field_forbidden_tags=$value;
break;
default:
$this->throw_error(sprintf('Unsupported option %s with field '.__CLASS__,$attribute),E_USER_NOTICE);
}
}
}




function display()
{
$Template=new Template('framework/builder/forms/field_extended.tpl');

$field='<textarea type="text" ';
$field.=!empty($this->field_rows)?'rows="'.$this->field_rows.'" ':'';
$field.=!empty($this->field_cols)?'cols="'.$this->field_cols.'" ':'';
$field.=!empty($this->field_name)?'name="'.$this->field_name.'" ':'';
$field.=!empty($this->field_id)?'id="'.$this->field_id.'" ':'';
$field.=!empty($this->field_css_class)?'class="'.$this->field_css_class.'"> ':'>';
$field.=!empty($this->field_value)?$this->field_value:'';
$field.='</textarea>';

$Template->assign_vars(array(
'ID'=>$this->field_id,
'FIELD'=>$field,
'KERNEL_EDITOR'=>$this->field_editor?display_editor($this->field_id,$this->field_forbidden_tags):'',
'L_FIELD_TITLE'=>$this->field_title,
'L_EXPLAIN'=>$this->field_sub_title,
'L_REQUIRE'=>$this->field_required?'* ':''
));

return $Template->parse(TEMPLATE_STRING_MODE);
}

var $field_rows='';
var $field_cols='';
var $field_editor=true;
var $field_forbidden_tags=array();
}

?>