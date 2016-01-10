<?php

























import('builder/form/form_builder');









class FormFieldset
{






function FormFieldset($fieldset_title)
{
$this->fieldset_title=$fieldset_title;
}





function add_field($form_field)
{
if(isset($this->fieldset_fields[$form_field->field_id]))
$this->throw_error(sprintf('Field with identifier "<strong>%s</strong>" already exists, please chose a different one!',$form_field->field_id),E_USER_WARNING);
else
$this->fieldset_fields[$form_field->field_id]=$form_field;
}






function display($Template=false)
{
global $LANG,$Errorh;

if(!is_object($Template)|| strtolower(get_class($Template))!='template')
$Template=new Template('framework/builder/forms/fieldset.tpl');

$Template->assign_vars(array(
'C_DISPLAY_WARNING_REQUIRED_FIELDS'=>$this->fieldset_display_required,
'L_FORMTITLE'=>$this->fieldset_title,
'L_REQUIRED_FIELDS'=>$LANG['require'],
));


foreach($this->fieldset_errors as $error)
{
$Template->assign_block_vars('errors',array(
'ERROR'=>$Errorh->display($error['errstr'],$error['errno'])
));
}


foreach($this->fieldset_fields as $Field)
{
foreach($Field->get_errors()as $error)
{
$Template->assign_block_vars('errors',array(
'ERROR'=>$Errorh->display($error['errstr'],$error['errno'])
));
}

$Template->assign_block_vars('fields',array(
'FIELD'=>$Field->display(),
));
}

return $Template->parse(TEMPLATE_STRING_MODE);
}






function throw_error($errstr,$errno)
{
$this->fieldset_errors[]=array('errstr'=>$errstr,'errno'=>$errno);
}





function display_preview_button($field_identifier_preview){$this->field_identifier_preview=$field_identifier_preview;}




function set_title($fieldset_title){$this->fieldset_title=$fieldset_title;}




function set_display_required($fieldset_display_required){$this->fieldset_display_required=$fieldset_display_required;}




function get_title(){return $this->fieldset_title;}




function get_fields(){return $this->fieldset_fields;}




function get_display_required(){return $this->fieldset_display_required;}

var $fieldset_title='';
var $fieldset_fields=array();
var $fieldset_errors=array();
var $fieldset_display_required=false;
}

?>