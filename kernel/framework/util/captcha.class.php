<?php



























define('CAPTCHA_VERY_EASY',0);
define('CAPTCHA_EASY',1);
define('CAPTCHA_NORMAL',2);
define('CAPTCHA_HARD',3);
define('CAPTCHA_VERY_HARD',4);






class Captcha
{



function Captcha()
{
Captcha::update_instance();
if(@extension_loaded('gd'))
$this->gd_loaded=true;
}

## Public Methods ##




function is_available()
{
global $User;

if($this->gd_loaded&&$User->get_attribute('level')==-1)
return true;
return false;
}




function update_instance()
{
static $instance=0;

$this->instance=++$instance;
}










function set_difficulty($difficulty)
{







$this->difficulty=max(0,$difficulty);
}





function set_instance($instance)
{
$this->instance=$instance;
}





function set_width($width)
{
$this->width=$width;
}





function set_height($height)
{
$this->height=$height;
}





function set_font($font)
{
$this->font=$font;
}





function is_valid()
{
global $Sql;

if(!$this->is_available())
return true;

$get_code=retrieve(POST,'verif_code'.$this->instance,'',TSTRING_UNCHANGE);
$user_id=substr(strhash(USER_IP),0,13).$this->instance;
$captcha=$Sql->query_array(DB_TABLE_VERIF_CODE,'code','difficulty',"WHERE user_id = '".$user_id."'",__LINE__,__FILE__);


$Sql->query_inject("DELETE FROM ".DB_TABLE_VERIF_CODE." WHERE user_id = '".$user_id."'",__LINE__,__FILE__);

if(!empty($captcha['code'])&&$captcha['code']==$get_code&&$captcha['difficulty']==$this->difficulty)
return true;
else
return false;
}





function js_require()
{
global $LANG;

return $this->is_available()?'if (document.getElementById(\'verif_code'.$this->instance.'\').value == "") {
			alert("'.$LANG['require_verif_code'].'");
			return false;
		}':'';
}






function display_form($Template=false)
{
global $CONFIG;

$this->_save_user();

if(!is_object($Template)|| strtolower(get_class($Template))!='template')
$Template=new Template('framework/captcha.tpl');

if($this->is_available())
{
$Template->assign_vars(array(
'CAPTCHA_INSTANCE'=>$this->instance,
'CAPTCHA_WIDTH'=>$this->width,
'CAPTCHA_HEIGHT'=>$this->height,
'CAPTCHA_FONT'=>$this->font,
'CAPTCHA_DIFFICULTY'=>$this->difficulty,
));
return $Template->parse(TEMPLATE_STRING_MODE);
}
return '';
}




function display()
{
$this->_generate_code();

$rand=rand(0,1);

##Définition des couleurs##
$array_color=array(array(224,118,27),array(48,149,53),array(254,249,52),array(102,133,237),array(204,42,38),array(53,144,189),array(102,102,153),array(236,230,208),array(213,171,1),array(182,0,51),array(193,73,0),array(25,119,128),array(182,181,177),array(102,133,237));

switch($this->difficulty)
{
case 2:
$array_color=array(array(224,118,27),array(48,149,53),array(254,249,52),array(102,133,237));
break;
case 3:
case 4:
$array_color=array(array(224,118,27));
break;
}

##Création de l'image##
		if (!function_exists('imagecreatetruecolor'))
			$img = @imagecreate($this->width, $this->height);
		else
			$img = @imagecreatetruecolor($this->width, $this->height);

		//Choix aléatoire de couleur, et suppression du tableau pour éviter une réutilisation pour le texte.
		$bg_bis_index_color = array_rand($array_color);	
		list($r, $g, $b) = $this->_image_color_allocate_dark($array_color[$bg_bis_index_color], 150, 0.70); //Assombrissement de la couleur de fond.
		$bg_img = @imagecolorallocate($img, $r, $g, $b);
		if ($this->difficulty < 3)
			unset($array_color[$bg_bis_index_color]);

		$bg_index_color = array_rand($array_color);	
		list($r, $g, $b) = $array_color[$bg_index_color];
		$bg = @imagecolorallocate($img, $r, $g, $b);
		if ($this->difficulty < 3)
			unset($array_color[$bg_index_color]);

		$bg_bis_index_color = array_rand($array_color);	
		list($r, $g, $b) = $array_color[$bg_bis_index_color];
		$bg_bis = @imagecolorallocate($img, $r, $g, $b);
		if ($this->difficulty < 3)
			unset($array_color[$bg_bis_index_color]);

		$black = @imagecolorallocate($img, 0, 0, 0);

		##Création de l'arrère plan

@imagefilledrectangle($img,0,0,$this->width,$this->height,$bg_img);

$style=array($bg,$bg,$bg,$bg,$bg_bis,$bg_bis,$bg_bis,$bg_bis,$bg_bis,$bg_bis);
@imagesetstyle($img,$style);
if($this->difficulty>0)
{
if($rand)
for($i=0;$i<=$this->height;$i=($i+2))
@imageline($img,0,$i,$this->width,$i,IMG_COLOR_STYLED);
else
for($i=$this->height;$i>=0;$i=($i-2))
@imageline($img,0,$i,$this->width,$i,IMG_COLOR_STYLED);
}

##Attribut du code à écrire##

$global_font_size=24;
$array_size_ttf=@imagettfbbox($global_font_size+2,0,$this->font,$this->code);
$text_width=abs($array_size_ttf[2]-$array_size_ttf[0]);
$text_height=abs($array_size_ttf[7]-$array_size_ttf[1]);
$text_x=($this->width/2)-($text_width/2);
$text_y=($this->height/2)+($text_height/2);

preg_match_all('/.{1}/s',$this->code,$matches);
foreach($matches[0]as $key=>$letter)
{

$index_color=array_rand($array_color);
list($r,$g,$b)=$array_color[$index_color];
$text_color=@imagecolorallocate($img,$r,$g,$b);
list($r,$g,$b)=$this->_image_color_allocate_dark($array_color[$index_color]);
$text_color_dark=@imagecolorallocate($img,$r,$g,$b);
$font_size=rand($global_font_size-4,$global_font_size);
$angle=rand(-15,15);
$move_y=$text_y+rand(-15,4);
if($this->difficulty<2)
{
$angle=0;
$move_y=$text_y-2;
}


if($this->difficulty==4)
{
list($r,$g,$b)=$this->_image_color_allocate_dark($array_color[$index_color],90,0.50);
$text_color_dark=@imagecolorallocate($img,$r,$g,$b);
}
@imagettftext($img,$font_size,$angle,($text_x+1),($move_y+1),$text_color_dark,$this->font,$letter);


@imagettftext($img,$font_size,$angle,$text_x,$move_y,$text_color,$this->font,$letter);
$array_size_ttf=@imagettfbbox($font_size,$angle,$this->font,$this->code);
$text_width=max(abs($array_size_ttf[2]-$array_size_ttf[0]),5);
$text_x+=$global_font_size-6;
}


@imagerectangle($img,0,0,$this->width-1,$this->height-1,$black);

##Envoi de l'image##
		imagejpeg($img);
		imagedestroy($img);
		
		//Enregistrement du code pour l'utilisateur dans la base de données;
$this->_update_code();
}

## Private Methods ##



function _generate_code()
{
global $LANG;

$rand=rand(0,1);

##Génération du code##
$words=$LANG['_code_dictionnary'];

switch($this->difficulty)
{
case 0;
$this->code=$words[array_rand($words)];
break;
case 1:
$this->code=str_shuffle($words[array_rand($words)]);
break;
case 2:
$this->code=str_shuffle($words[array_rand($words)].substr(rand(0,99),0,rand(1,2)));
break;
case 3:
case 4:
$this->code=str_shuffle($words[array_rand($words)].substr(rand(0,99),0,rand(1,2)));
break;
default:
$this->code=str_shuffle($words[array_rand($words)].substr(rand(0,99),0,rand(1,2)));
}
if($this->difficulty>0)
{
$this->code=substr($this->code,0,6);
$this->code=str_replace(array('l','1','o','0'),array('','','',''),$this->code);
}
}




function _save_user()
{
global $Sql;

$this->_generate_code();

$code=substr(md5(uniqid(mt_rand(),true)),0,20);
$user_id=substr(strhash(USER_IP),0,13).$this->instance;
$check_user_id=$Sql->query("SELECT COUNT(*) FROM ".DB_TABLE_VERIF_CODE." WHERE user_id = '".$user_id."'",__LINE__,__FILE__);
if($check_user_id==1)
$Sql->query_inject("UPDATE ".DB_TABLE_VERIF_CODE." SET code = '".$code."', difficulty = '".$this->difficulty."' WHERE user_id = '".$user_id."'",__LINE__,__FILE__);
else
$Sql->query_inject("INSERT INTO ".DB_TABLE_VERIF_CODE." (user_id, code, difficulty, timestamp) VALUES ('".$user_id."', '".$this->code."', '".$this->difficulty."', '".time()."')",__LINE__,__FILE__);
}




function _update_code()
{
global $Sql;

$user_id=substr(strhash(USER_IP),0,13).$this->instance;
$check_user_id=$Sql->query("SELECT COUNT(*) FROM ".DB_TABLE_VERIF_CODE." WHERE user_id = '".$user_id."'",__LINE__,__FILE__);
if($check_user_id==1)
$Sql->query_inject("UPDATE ".DB_TABLE_VERIF_CODE." SET code = '".$this->code."' WHERE user_id = '".$user_id."'",__LINE__,__FILE__);
else
$Sql->query_inject("INSERT INTO ".DB_TABLE_VERIF_CODE." (user_id, code, difficulty, timestamp) VALUES ('".$user_id."', '".$this->code."', '4', '".time()."')",__LINE__,__FILE__);
}










function _image_color_allocate_dark($array_color,$mask_color=0,$similar_color=0.40)
{
list($r,$g,$b)=$array_color;
$rd=round($r*$similar_color)+round($mask_color*(1-$similar_color));
$gd=round($g*$similar_color)+round($mask_color*(1-$similar_color));
$bd=round($b*$similar_color)+round($mask_color*(1-$similar_color));

return array($rd,$gd,$bd);
}

## Private Attributes ##
var $instance=0;
var $gd_loaded=false;
var $width=160;
var $code='';
var $height=50;
var $font='../kernel/data/fonts/impact.ttf';
var $difficulty=2;
}

?>