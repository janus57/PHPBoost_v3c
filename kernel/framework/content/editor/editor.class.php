<?php
































class ContentEditor
{
function ContentEditor($language_type=null)
{
global $CONFIG;
if($language_type!==null)
{
$this->set_language($language_type);
}

$this->forbidden_tags=&$CONFIG['forbidden_tags'];
}





function set_forbidden_tags(&$forbidden_tags)
{
$this->forbidden_tags=$forbidden_tags;
}





function get_forbidden_tags()
{
return $this->forbidden_tags;
}





function set_identifier($identifier)
{
$this->identifier=$identifier;
}





function set_template(&$template)
{
$this->template=$template;
}





function get_template()
{
if(!is_object($this->template)|| strtolower(get_class($this->template))!='template')
{
return new template('framework/content/editor.tpl');
}
else
{
return $this->template;
}
}

## Private ##
var $language_type=DEFAULT_LANGUAGE;
var $forbidden_tags=array();
var $identifier='contents';
var $template=null;
}

?>