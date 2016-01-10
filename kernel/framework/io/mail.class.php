<?php


























define('CRLF',"\r\n");
define('MIME_FORMAT_TEXT','text/plain');
define('MIME_FORMAT_HTML','text/html');







class Mail
{



function Mail()
{
}







function set_sender($sender,$sender_name='admin')
{
global $LANG,$CONFIG;
$this->sender_name=str_replace('"','',$CONFIG['site_name'].' - '.($sender_name=='admin'?$LANG['admin']:$LANG['user']));

if(Mail::check_validity($sender))
{
$this->sender_mail=$sender;
return true;
}
else
{
return false;
}
}





function set_recipients($recipients)
{
$this->recipients='';

$recipients_list=explode(';',$recipients);
$recipients_list=array_map('trim',$recipients_list);


foreach($recipients_list as $recipient)
{
if(Mail::check_validity($recipient))
{
$this->recipients[]=$recipient;
}
}


if(!empty($this->recipients))
{
return true;
}
else
{
return false;
}
}





function set_object($object)
{
$this->object=$object;
}





function set_content($content)
{
$this->content=$content;
}





function set_headers($headers)
{
$this->headers=$headers;
}





function get_sender_mail()
{
return $this->sender_mail;
}





function get_sender_name()
{
return $this->sender_name;
}





function get_recipients()
{
return $this->recipients;
}





function get_object()
{
return $this->object;
}





function get_content()
{
return $this->content;
}





function get_headers()
{
return $this->headers;
}





function set_mime($mime)
{
$this->format=$mime;
}





function get_mime()
{
return $this->format();
}












function send_from_properties($mail_to,$mail_object,$mail_content,$mail_from,$mail_header=null,$sender_name='admin')
{

$recipient=$this->set_recipients($mail_to);
$sender=$this->set_sender($mail_from,$sender_name);
if(!$recipient ||!$sender)
{
return false;
}

$this->set_object($mail_object);
$this->set_content($mail_content);

$this->set_headers($mail_header);


return $this->send();
}





function send()
{
if(empty($this->headers))
{
$this->_generate_headers();
}

$recipients=trim(implode(', ',$this->recipients),', ');
return @mail($recipients,$this->object,$this->content,$this->headers);
}






function check_validity($mail_address)
{
return preg_match('`^(?:[a-z0-9_!#$%&\'*+/=?^|~-]+\.)*[a-z0-9_!#$%&\'*+/=?^|~-]+@(?:[a-z0-9_-]{2,}\.)+([a-z0-9_-]{2,}\.)*[a-z]{2,4}$`i',$mail_address);
}


## Protected Methods ##




function _generate_headers()
{
global $LANG;

$this->header='';


$this->_add_header_field('From','"'.$this->sender_name.' '.HOST.'" <'.$this->sender_mail.'>');


$recipients='';
$nb_recipients=count($this->recipients);
for($i=0;$i<$nb_recipients;$i++)
{
$recipients.='"'.$this->recipients[$i].'" <'.$this->recipients[$i].'>';
if($i<$nb_recipients-1)
{
$recipients.=', ';
}
}

$this->_add_header_field('MIME-Version','1.0');
$this->_add_header_field('Content-type',$this->format.'; charset=ISO-8859-1');
}







function _add_header_field($field,$value){
$this->headers.=wordwrap($field.': '.$value,78,"\n ").CRLF;
}

## Private Attributes ##



var $object='';




var $content='';




var $sender_mail='';




var $sender_name='';




var $headers='';




var $recipients=array();




var $format=MIME_FORMAT_TEXT;
}

?>
