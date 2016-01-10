<?php









































class FormField
{





function FormField($fieldId,&$fieldOptions)
{
$this->field_name=$fieldId;
$this->field_id=$fieldId;

foreach($fieldOptions as $attribute=>$value)
{
$attribute=strtolower($attribute);
switch($attribute)
{
case 'title':
$this->field_title=$value;
unset($fieldOptions['title']);
break;
case 'subtitle':
$this->field_sub_title=$value;
unset($fieldOptions['subtitle']);
break;
case 'value':
$this->field_value=$value;
unset($fieldOptions['value']);
break;
case 'id':
$this->field_id=$value;
unset($fieldOptions['id']);
break;
case 'class':
$this->field_css_class=$value;
unset($fieldOptions['class']);
break;
case 'required':
$this->field_required=$value;
unset($fieldOptions['required']);
break;
case 'required_alert':
$this->field_required_alert=$value;
unset($fieldOptions['required_alert']);
break;
case 'onblur':
$this->field_maxlength=$value;
unset($fieldOptions['onblur']);
break;
}
}
}






function throw_error($errstr,$errno)
{
$this->field_errors[]=array('errstr'=>$errstr,'errno'=>$errno);
}





function add_errors(&$array_errors)
{
$this->field_errors=array_merge($this->field_errors,$array_errors);
}





function get_errors(){return $this->field_errors;}




function get_id(){return $this->field_id;}




function get_required_alert(){return $this->field_required_alert;}


var $field_title='';
var $field_sub_title='';
var $field_name='';
var $field_value='';
var $field_id='';
var $field_css_class='';
var $field_required=false;
var $field_required_alert='';
var $field_on_blur='';
var $field_errors=array();
}

?>