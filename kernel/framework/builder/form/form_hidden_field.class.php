<?php

























import('builder/form/form_field');







class FormHiddenField extends FormField
{
function FormHiddenField($fieldId,$field_options)
{
parent::FormField($fieldId,$field_options);
}

function display()
{
$field='<input type="hidden" ';
$field.=!empty($this->field_name)?'name="'.$this->field_name.'" ':'';
$field.=!empty($this->field_id)?'id="'.$this->field_id.'" ':'';
$field.=!empty($this->field_value)?'value="'.$this->field_value.'" ':'';
$field.='/>';

return $field;
}
}

?>