<?php


























import('content/syndication/feed_item');
import('util/date');
import('util/url');

define('FEED_DATA__CLASS','FeedData');







class FeedData
{
## Public Methods ##





function FeedData($data=null)
{
if($data!==null&&of_class($data,FEED_DATA__CLASS))
{
$this->title=$data->title;
$this->link=$data->link;
$this->date=$data->date;
$this->desc=$data->desc;
$this->lang=$data->lang;
$this->host=$data->host;
$this->items=$data->items;
}
}

## Setters ##




function set_title($value){$this->title=strip_tags($value);}




function set_date($value){$this->date=$value;}




function set_desc($value){$this->desc=$value;}




function set_lang($value){$this->lang=$value;}




function set_host($value){$this->host=$value;}




function set_auth_bit($value){$this->auth_bit=$value;}




function set_link($value)
{
if(!of_class($value,URL__CLASS))
{
$value=new Url($value);
}
$this->link=$value->absolute();
}

function add_item($item){array_push($this->items,$item);}

## Getters ##
function get_title(){return $this->title;}
function get_link(){return $this->link;}
function get_date(){return $this->date->format(DATE_FORMAT_TINY,TIMEZONE_USER);}
function get_date_rfc822(){return $this->date->format(DATE_RFC822_F,TIMEZONE_USER);}
function get_date_rfc3339(){return $this->date->format(DATE_RFC3339_F,TIMEZONE_USER);}
function get_desc(){return $this->desc;}
function get_lang(){return $this->lang;}
function get_host(){return $this->host;}





function get_items()
{
global $User;
$items=array();
foreach($this->items as $item)
{
if((gettype($item->get_auth())!='array' || $this->auth_bit==0)|| $User->check_auth($item->get_auth(),$this->auth_bit))
$items[]=$item;
}

return $items;
}

function serialize()
{
return serialize($this);
}








function subitems($number=10,$begin_at=0)
{
$secured_items=$this->get_items();
$nb_items=count($secured_items);

$items=array();
$end_at=$begin_at+$number;
for($i=$begin_at;($i<$nb_items)&&($i<$end_at);$i++)
$items[]=&$secured_items[$i];

return $items;
}

## Private Methods ##

## Private attributes ##
var $title='';
var $link='';
var $date=null;
var $desc='';
var $lang='';
var $host='';
var $items=array();
var $auth_bit=0;
}

?>