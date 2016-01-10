<?php














































































define('FIELD_INPUT__TEXT','text');
define('FIELD_INPUT__RADIO','radio');
define('FIELD_INPUT__CHECKBOX','checkbox');
define('FIELD_INPUT__HIDDEN','hidden');
define('FIELD_INPUT__FILE','file');
define('FIELD__TEXTAREA','textarea');
define('FIELD__SELECT','select');

import('builder/form/form_fieldset');
import('builder/form/form_text_edit');
import('builder/form/form_hidden_field');
import('builder/form/form_file_uploader');
import('builder/form/form_textarea');
import('builder/form/form_radio_choice');
import('builder/form/form_checkbox');
import('builder/form/form_select');

class FormBuilder
{






function FormBuilder($form_name,$form_action='')
{
global $LANG;

$this->form_name=$form_name;
$this->form_action=$form_action;
$this->form_submit=$LANG['submit'];
}





function add_fieldset($fieldset)
{
$this->form_fieldsets[]=$fieldset;
}






function display($Template=false)
{
global $LANG;

if(!is_object($Template)|| strtolower(get_class($Template))!='template')
$Template=new Template('framework/builder/forms/form.tpl');

$Template->assign_vars(array(
'C_DISPLAY_PREVIEW'=>$this->display_preview,
'C_DISPLAY_RESET'=>$this->display_reset,
'FORMCLASS'=>$this->form_class,
'U_FORMACTION'=>$this->form_action,
'L_FORMNAME'=>$this->form_name,
'L_FIELD_CONTENT_PREVIEW'=>$this->field_identifier_preview,
'L_SUBMIT'=>$this->form_submit,
'L_PREVIEW'=>$LANG['preview'],
'L_RESET'=>$LANG['reset'],
));

foreach($this->form_fieldsets as $Fieldset)
{
foreach($Fieldset->get_fields()as $Field)
{
$field_required_alert=$Field->get_required_alert();
if(!empty($field_required_alert))
{
$Template->assign_block_vars('check_form',array(
'FIELD_ID'=>$Field->get_id(),
'FIELD_REQUIRED_ALERT'=>str_replace('"','\"',$field_required_alert)
));
}
}

$Template->assign_block_vars('fieldsets',array(
'FIELDSET'=>$Fieldset->display(),
));
}

return $Template->parse(TEMPLATE_STRING_MODE);
}





function display_preview_button($field_identifier_preview)
{
$this->display_preview=true;
$this->field_identifier_preview=$field_identifier_preview;
}





function display_reset($value)
{
$this->display_reset=$value;
}


function set_form_name($form_name){$this->form_name=$form_name;}
function set_form_submit($form_submit){$this->form_submit=$form_submit;}
function set_form_action($form_action){$this->form_action=$form_action;}
function set_form_class($form_class){$this->form_class=$form_class;}


function get_form_name(){return $this->form_name;}
function get_form_submit(){return $this->form_submit;}
function get_form_action(){return $this->form_action;}
function get_form_class(){return $this->form_class;}

var $form_fieldsets=array();
var $form_name='';
var $form_submit='';
var $form_action='';
var $form_class='fieldset_mini';
var $display_preview=false;
var $field_identifier_preview='contents';

var $display_reset=true;
}

?>