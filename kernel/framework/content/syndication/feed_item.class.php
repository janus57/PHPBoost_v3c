<?php


























import('util/url');







class FeedItem
{
## Public Methods ##



function FeedItem(){}

## Setters ##




function set_title($value){$this->title=strip_tags($value);}




function set_date($value){$this->date=$value;}




function set_desc($value){$this->desc=$value;}




function set_image_url($value){$this->image_url=$value;}




function set_auth($auth){$this->auth=$auth;}




function set_link($value)
{
if(!of_class($value,URL__CLASS))
{
$value=new Url($value);
}
$this->link=$value->absolute();
}




function set_guid($value)
{
if(of_class($value,URL__CLASS))
{
$this->guid=$value->absolute();
}
else
{
$this->guid=$value;
}
}

## Getters ##
function get_title(){return $this->title;}
function get_link(){return $this->link;}
function get_guid(){return $this->guid;}
function get_date(){return $this->date->format(DATE_FORMAT_TINY,TIMEZONE_USER);}
function get_date_rfc822(){return $this->date->format(DATE_RFC822_F,TIMEZONE_USER);}
function get_date_rfc3339(){return $this->date->format(DATE_RFC3339_F,TIMEZONE_USER);}
function get_desc(){return $this->desc;}
function get_image_url(){return $this->image_url;}
function get_auth(){return $this->auth;}

## Private Methods ##
## Private attributes ##
var $title='';
var $link='';
var $date=null;
var $desc='';
var $guid='';
var $image_url='';
var $auth=null;
}

?>