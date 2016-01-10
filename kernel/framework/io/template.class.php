<?php



























define('TEMPLATE_STRING_MODE',true);
define('TEMPLATE_WRITE_MODE',false);
define('AUTO_LOAD_FREQUENT_VARS',true);
define('DO_NOT_AUTO_LOAD_FREQUENT_VARS',false);















































class Template
{






function Template($tpl='',$auto_load_vars=AUTO_LOAD_FREQUENT_VARS)
{
if(!empty($tpl))
{
global $User,$Session;
$this->tpl=$this->_check_file($tpl);
$this->files[$this->tpl]=$this->tpl;
if($auto_load_vars)
{
$member_connected=$User->check_level(MEMBER_LEVEL);
$this->assign_vars(array(
'SID'=>SID,
'THEME'=>get_utheme(),
'LANG'=>get_ulang(),
'C_USER_CONNECTED'=>$member_connected,
'C_USER_NOTCONNECTED'=>!$member_connected,
'PATH_TO_ROOT'=>TPL_PATH_TO_ROOT,
'PHP_PATH_TO_ROOT'=>PATH_TO_ROOT,
'TOKEN'=>!empty($Session)?$Session->get_token():''
));
}
}
}







function set_filenames($array_tpl)
{
foreach($array_tpl as $parse_name=>$filename)
{
$this->files[$parse_name]=$this->_check_file($filename);
}

global $Session;
$this->assign_vars(array(
'TOKEN'=>!empty($Session)?$Session->get_token():''
));
}






function get_module_data_path($module)
{
if(isset($this->module_data_path[$module]))
{
return $this->module_data_path[$module];
}
return '';
}





function assign_vars($array_vars)
{
foreach($array_vars as $key=>$val)
{
$this->_var[$key]=$val;
}
}







function assign_block_vars($block_name,$array_vars)
{
if(strpos($block_name,'.')!==false)
{
$blocks=explode('.',$block_name);
$blockcount=count($blocks)-1;

$str=&$this->_block;
for($i=0;$i<$blockcount;$i++)
{
$str=&$str[$blocks[$i]];
$str=&$str[count($str)-1];
}
$str[$blocks[$blockcount]][]=$array_vars;
}
else
{
$this->_block[$block_name][]=$array_vars;
}
}





function unassign_block_vars($block_name)
{
if(isset($this->_block[$block_name]))
{
unset($this->_block[$block_name]);
}
}







function parse($return_mode=TEMPLATE_WRITE_MODE)
{
if($return_mode)
{
return $this->pparse($this->tpl,$return_mode);
}
else
{
$this->pparse($this->tpl,$return_mode);
}
}









function pparse($parse_name,$return_mode=false)
{
if(!isset($this->files[$parse_name]))
{
return '';
}

$this->return_mode=$return_mode;

$file_cache_path=PATH_TO_ROOT.'/cache/tpl/'.trim(str_replace(array('/','.','..','tpl','templates'),array('_','','','','tpl'),$this->files[$parse_name]),'_');
if($return_mode)
{
$file_cache_path.='_str';
}
$file_cache_path.='.php';


if(!$this->_check_cache_file($this->files[$parse_name],$file_cache_path))
{

if(!$this->_load($parse_name))
{
return '';
}


$this->_parse($parse_name,$return_mode);
$this->_clean();
$this->_save($file_cache_path);
}

include($file_cache_path);

if($this->return_mode)
{
return $tplString;
}
}





function copy()
{
$copy=new Template();

$copy->tpl=$this->tpl;
$copy->template=$this->template;
$copy->files=$this->files;
$copy->module_data_path=$this->module_data_path;
$copy->return_mode=$this->return_mode;

$copy->_var=$this->_var;
$copy->_block=$this->_block;

return $copy;
}


## Protected Methods ##






function _check_file($filename)
{
global $CONFIG;



















$i=strpos($filename,'/');
$module=substr($filename,0,$i);
$file=trim(substr($filename,$i),'/');
$folder=trim(substr($file,0,strpos($file,'/')),'/');
$file_name=trim(substr($filename,strrpos($filename,'/')));

$default_templates_folder=PATH_TO_ROOT.'/templates/default/';
$theme_templates_folder=PATH_TO_ROOT.'/templates/'.get_utheme().'/';
if(strpos($filename,'/')===0)
{


if(file_exists(PATH_TO_ROOT.$filename))
{
return PATH_TO_ROOT.$filename;
}
}
elseif(empty($module)|| in_array($module,array('admin')))
{


if(file_exists($file_path=$theme_templates_folder.$filename))
{
return $file_path;
}
return $default_templates_folder.$filename;
}
elseif($module=='framework')
{


if(file_exists($file_path=$theme_templates_folder.$filename))
{
return $file_path;
}

return $default_templates_folder.$filename;
}
elseif($module=='menus')
{


$menu=substr($folder,0,strpos($folder,'/'));
if(empty($menu))
{
$menu=$folder;
}
if(file_exists($file_path=$theme_templates_folder.'/menus/'.$menu.'/'.$file_name))
{
return $file_path;
}

return PATH_TO_ROOT.'/menus/'.$menu.'/templates/'.$file_name;
}
else
{
$theme_module_templates_folder=$theme_templates_folder.'modules/'.$module.'/';
$module_templates_folder=PATH_TO_ROOT.'/'.$module.'/templates/';

if($folder=='framework')
{




if(file_exists($file_path=$theme_module_templates_folder.$file))
{
return $file_path;
}
if(file_exists($file_path=$theme_templates_folder.$filename))
{
return $file_path;
}
if(file_exists($file_path=($module_templates_folder.'framework/'.$file)))
{
return $file_path;
}
return $default_templates_folder.$file;
}


if(!isset($this->module_data_path[$module]))
{
if(is_dir($theme_module_templates_folder.'/images'))
{
$this->module_data_path[$module]=TPL_PATH_TO_ROOT.'/templates/'.get_utheme().'/'.'modules/'.$module;
}
else
{
$this->module_data_path[$module]=TPL_PATH_TO_ROOT.'/'.trim($module.'/templates/','/');
}
}

if(file_exists($file_path=$theme_module_templates_folder.$file))
{
return $file_path;
}
else
{
return $module_templates_folder.$file;
}
}
}








function _check_cache_file($tpl_path,$file_cache_path)
{

if(file_exists($file_cache_path))
{
if(@filemtime($tpl_path)>@filemtime($file_cache_path)|| @filesize($file_cache_path)===0)
{
return false;
}
else
{
return true;
}
}
return false;
}







function _load($parse_name)
{
if(!isset($this->files[$parse_name]))
return false;

$this->template=@file_get_contents_emulate($this->files[$parse_name]);
if($this->template===false)
{
die('Template::_load(): The '.$this->files[$parse_name].' file loading to parse '.$parse_name.' failed.');
}
if(empty($this->template))
{
die('Template::_load(): The file '.$this->files[$parse_name].' to parse '.$parse_name.' is empty.');
}

return true;
}









function _include($parse_name)
{
if(isset($this->files[$parse_name]))
{
if($this->return_mode)
{
return $this->pparse($parse_name,$this->return_mode);
}
else
{
$this->pparse($parse_name,$this->return_mode);
}
}
}





function _parse($parse_name)
{
if($this->return_mode)
{
$this->template='<?php $tplString = \''.str_replace(array('\\','\''),array('\\\\','\\\''),$this->template).'\'; ?>';

$this->template=preg_replace('`{([\w]+)}`i','\'; if (isset($this->_var[\'$1\'])) $tplString .= $this->_var[\'$1\']; $tplString .=\'',$this->template);
$this->template=preg_replace_callback('`{([\w\.]+)}`i',array($this,'_parse_blocks_vars'),$this->template);


$this->template=preg_replace_callback('`# START ([\w\.]+) #`',array($this,'_parse_blocks'),$this->template);
$this->template=preg_replace('`# END [\w\.]+ #`','\';'."\n".'}'."\n".'$tplString .= \'',$this->template);


$this->template=preg_replace_callback('`# IF (NOT )?([\w\.]+) #`',array($this,'_parse_conditionnal_blocks'),$this->template);
$this->template=preg_replace_callback('`# ELSEIF (NOT )?([\w\.]+) #`',array($this,'_parse_conditionnal_blocks_bis'),$this->template);
$this->template=preg_replace('`# ELSE #`','\';'."\n".'} else {'."\n".'$tplString .= \'',$this->template);
$this->template=preg_replace('`# ENDIF #`','\';'."\n".'}'."\n".'$tplString .= \'',$this->template);


$this->template=preg_replace('`# INCLUDE ([\w]+) #`','\';'."\n".'$tplString .= $this->_include(\'$1\');'."\n".'$tplString .= \'',$this->template);

$this->template=preg_replace_callback('`(?<!^)<\?php(.*)\?>(?!$)`isU',array($this,'_accept_php_block'),$this->template);
}
else
{

$this->template=preg_replace_callback('`\<\?(?!php)(\s.*)\?\>`i',array($this,'_protect_from_inject'),$this->template);


$this->template=preg_replace('`{([\w]+)}`i','<?php if (isset($this->_var[\'$1\'])) echo $this->_var[\'$1\']; ?>',$this->template);
$this->template=preg_replace_callback('`{([\w\.]+)}`i',array($this,'_parse_blocks_vars'),$this->template);


$this->template=preg_replace_callback('`# START ([\w\.]+) #`',array($this,'_parse_blocks'),$this->template);
$this->template=preg_replace('`# END [\w\.]+ #`','<?php } ?>',$this->template);


$this->template=preg_replace_callback('`# IF (NOT )?([\w\.]+) #`',array($this,'_parse_conditionnal_blocks'),$this->template);
$this->template=preg_replace_callback('`# ELSEIF (NOT )?([\w\.]+) #`',array($this,'_parse_conditionnal_blocks_bis'),$this->template);
$this->template=preg_replace('`# ELSE #`','<?php } else { ?>',$this->template);
$this->template=preg_replace('`# ENDIF #`','<?php } ?>',$this->template);


$this->template=preg_replace('`# INCLUDE ([\w]+) #`','<?php $this->_include(\'$1\'); ?>',$this->template);
}
}

function _accept_php_block($mask)
{
return '\';'.str_replace(array('\\\\','\\\''),array('\\','\''),$mask[1]).'$tplString.=\'';
}






function _protect_from_inject($mask)
{
return '<?php echo \'<?'.str_replace(array('\\','\''),array('\\\\','\\\''),trim($mask[1])).'?>\'; ?>';
}






function _parse_blocks_vars($blocks)
{
if(isset($blocks[1]))
{
$array_block=explode('.',$blocks[1]);
$varname=array_pop($array_block);
$last_block=array_pop($array_block);

if($this->return_mode)
{
return '\'; if (isset($_tmpb_'.$last_block.'[\''.$varname.'\'])) $tplString .= $_tmpb_'.$last_block.'[\''.$varname.'\']; $tplString .= \'';
}
else
{
return '<?php if (isset($_tmpb_'.$last_block.'[\''.$varname.'\'])) echo $_tmpb_'.$last_block.'[\''.$varname.'\']; ?>';
}
}
return '';
}






function _parse_blocks($blocks)
{
if(isset($blocks[1]))
{
if(strpos($blocks[1],'.')!==false)
{
$array_block=explode('.',$blocks[1]);
$current_block=array_pop($array_block);
$previous_block=array_pop($array_block);

if($this->return_mode)
{
return '\';'."\n".'if (!isset($_tmpb_'.$previous_block.'[\''.$current_block.'\']) || !is_array($_tmpb_'.$previous_block.'[\''.$current_block.'\'])) $_tmpb_'.$previous_block.'[\''.$current_block.'\'] = array();'."\n".'foreach ($_tmpb_'.$previous_block.'[\''.$current_block.'\'] as $'.$current_block.'_key => $'.$current_block.'_value) {'."\n".'$_tmpb_'.$current_block.' = &$_tmpb_'.$previous_block.'[\''.$current_block.'\'][$'.$current_block.'_key];'."\n".'$tplString .= \'';
}
else
{
return '<?php if (!isset($_tmpb_'.$previous_block.'[\''.$current_block.'\']) || !is_array($_tmpb_'.$previous_block.'[\''.$current_block.'\'])) $_tmpb_'.$previous_block.'[\''.$current_block.'\'] = array();'."\n".'foreach ($_tmpb_'.$previous_block.'[\''.$current_block.'\'] as $'.$current_block.'_key => $'.$current_block.'_value) {'."\n".'$_tmpb_'.$current_block.' = &$_tmpb_'.$previous_block.'[\''.$current_block.'\'][$'.$current_block.'_key]; ?>';
}
}
else
{
if($this->return_mode)
{
return '\';'."\n".'if (!isset($this->_block[\''.$blocks[1].'\']) || !is_array($this->_block[\''.$blocks[1].'\'])) $this->_block[\''.$blocks[1].'\'] = array();'."\n".'foreach ($this->_block[\''.$blocks[1].'\'] as $'.$blocks[1].'_key => $'.$blocks[1].'_value) {'."\n".'$_tmpb_'.$blocks[1].' = &$this->_block[\''.$blocks[1].'\'][$'.$blocks[1].'_key];'."\n".'$tplString .= \'';
}
else
{
return '<?php if (!isset($this->_block[\''.$blocks[1].'\']) || !is_array($this->_block[\''.$blocks[1].'\'])) $this->_block[\''.$blocks[1].'\'] = array();'."\n".'foreach ($this->_block[\''.$blocks[1].'\'] as $'.$blocks[1].'_key => $'.$blocks[1].'_value) {'."\n".'$_tmpb_'.$blocks[1].' = &$this->_block[\''.$blocks[1].'\'][$'.$blocks[1].'_key]; ?>';
}
}
}
return '';
}






function _parse_conditionnal_blocks($blocks)
{
if(isset($blocks[2]))
{
$not=($blocks[1]=='NOT '?'!':'');
if(strpos($blocks[2],'.')!==false)
{
$array_block=explode('.',$blocks[2]);
$varname=array_pop($array_block);
$last_block=array_pop($array_block);

if($this->return_mode)
{
return '\';'."\n".'if (isset($_tmpb_'.$last_block.'[\''.$varname.'\']) && '.$not.'$_tmpb_'.$last_block.'[\''.$varname.'\']) {'."\n".'$tplString .= \'';
}
else
{
return '<?php if (isset($_tmpb_'.$last_block.'[\''.$varname.'\']) && '.$not.'$_tmpb_'.$last_block.'[\''.$varname.'\']) { ?>';
}
}
else
{
if($this->return_mode)
{
return '\';'."\n".'if (isset($this->_var[\''.$blocks[2].'\']) && '.$not.'$this->_var[\''.$blocks[2].'\']) {'."\n".'$tplString .= \'';
}
else
{
return '<?php if (isset($this->_var[\''.$blocks[2].'\']) && '.$not.'$this->_var[\''.$blocks[2].'\']) { ?>';
}
}
}
return '';
}






function _parse_conditionnal_blocks_bis($blocks)
{
if(isset($blocks[2]))
{
$not=($blocks[1]=='NOT '?'!':'');
if(strpos($blocks[2],'.')!==false)
{
$array_block=explode('.',$blocks[2]);
$varname=array_pop($array_block);
$last_block=array_pop($array_block);

if($this->return_mode)
return '\';'."\n".'} elseif (isset($_tmpb_'.$last_block.'[\''.$varname.'\']) && '.$not.'$_tmpb_'.$last_block.'[\''.$varname.'\']) {'."\n".'$tplString .= \'';
else
return '<?php } elseif (isset($_tmpb_'.$last_block.'[\''.$varname.'\']) && '.$not.'$_tmpb_'.$last_block.'[\''.$varname.'\']) { ?>';
}
else
{
if($this->return_mode)
return '\';'."\n".'} elseif (isset($this->_var[\''.$blocks[2].'\']) && '.$not.'$this->_var[\''.$blocks[2].'\']) {'."\n".'$tplString .= \'';
else
return '<?php } elseif (isset($this->_var[\''.$blocks[2].'\']) && '.$not.'$this->_var[\''.$blocks[2].'\']) { ?>';
}
}
return '';
}




function _clean()
{
$this->template=preg_replace(
array('`# START [\w\.]+ #(.*)# END [\w\.]+ #`s','`# START [\w\.]+ #`','`# END [\w\.]+ #`','`{[\w\.]+}`'),
array('','','',''),
$this->template);


if($this->return_mode)
{
$this->template=str_replace('$tplString .= \'\';','',$this->template);
$this->template=preg_replace(array('`[\n]{2,}`','`[\r]{2,}`','`[\t]{2,}`','`[ ]{2,}`'),array('','','',''),$this->template);
}
else
{
$this->template=preg_replace('` \?><\?php `','',$this->template);
$this->template=preg_replace('` \?>[\s]+<\?php `',"echo ' ';",$this->template);
$this->template=preg_replace("`echo ' ';echo `","echo ' ' . ",$this->template);
$this->template=preg_replace("`''\);echo `","'') . ",$this->template);
}
}





function _save($file_cache_path)
{
import('io/filesystem/file');
$file=new File($file_cache_path);
$file->open(WRITE);
$file->lock();
$file->write($this->template);
$file->unlock();
$file->close();
$file->change_chmod(0666);
}


## Private Attribute ##



var $tpl='';




var $template='';




var $files=array();




var $module_data_path=array();




var $return_mode;




var $_var=array();




var $_block=array();
}

?>
