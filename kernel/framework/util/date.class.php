<?php



























define('DATE_TIMESTAMP',0);
define('DATE_NOW',1);
define('DATE_YEAR_MONTH_DAY',2);
define('DATE_YEAR_MONTH_DAY_HOUR_MINUTE_SECOND',3);
define('DATE_FROM_STRING',4);
define('DATE_FORMAT_TINY',1);
define('DATE_FORMAT_SHORT',2);
define('DATE_FORMAT',3);
define('DATE_FORMAT_LONG',4);
define('DATE_RFC822_F',5);
define('DATE_RFC3339_F',6);

define('DATE_RFC822_FORMAT','D, d M Y H:i:s O');
define('DATE_RFC3339_FORMAT','Y-m-d\TH:i:s');

define('TIMEZONE_AUTO',TIMEZONE_USER);












class Date
{








































function Date()
{
global $CONFIG;


$num_args=func_num_args();

if($num_args==0)
$format=DATE_NOW;
else
$format=func_get_arg(0);

if($format!=DATE_NOW)
{

if($num_args>=2)
$referencial_timezone=func_get_arg(1);
else
$referencial_timezone=TIMEZONE_USER;

$time_difference=$this->_compute_server_user_difference($referencial_timezone);
}

switch($format)
{
case DATE_NOW:
$this->timestamp=time();
break;


case DATE_YEAR_MONTH_DAY:
if($num_args>=5)
{
$year=func_get_arg(2);
$month=func_get_arg(3);
$day=func_get_arg(4);
$this->timestamp=mktime(0,0,0,$year,$month,$day)-$time_difference*3600;
}
else
{
$this->timestamp=0;
}
break;


case DATE_YEAR_MONTH_DAY_HOUR_MINUTE_SECOND:
if($num_args>=7)
{
$year=func_get_arg(2);
$month=func_get_arg(3);
$day=func_get_arg(4);
$hour=func_get_arg(5);
$minute=func_get_arg(6);
$seconds=func_get_arg(7);
$this->timestamp=mktime($hour,$minute,$seconds,$month,$day,$year)-$time_difference*3600;
}
else
{
$this->timestamp=0;
}
break;

case DATE_TIMESTAMP:
if($num_args>=3)
$this->timestamp=func_get_arg(2)-$time_difference*3600;
else
$this->timestamp=0;
break;

case DATE_FROM_STRING:
if($num_args<4)
{
$this->timestamp=0;
break;
}
list($month,$day,$year)=array(0,0,0);
$str=func_get_arg(2);
$date_format=func_get_arg(3);
$array_timestamp=explode('/',$str);
$array_date=explode('/',$date_format);
for($i=0;$i<3;$i++)
{
switch($array_date[$i])
{
case 'd':
$day=(isset($array_timestamp[$i]))?numeric($array_timestamp[$i]):0;
break;

case 'm':
$month=(isset($array_timestamp[$i]))?numeric($array_timestamp[$i]):0;
break;

case 'y':
$year=(isset($array_timestamp[$i]))?numeric($array_timestamp[$i]):0;
break;
}
}


if($this->Check_date($month,$day,$year))
$this->timestamp=@mktime(0,0,1,$month,$day,$year)-$time_difference*3600;
else
$this->timestamp=time();
break;

default:
$this->timestamp=0;
}
}




















function format($format=DATE_FORMAT_TINY,$referencial_timezone=TIMEZONE_USER)
{
global $LANG,$CONFIG;

$timestamp=$this->timestamp+$this->_compute_server_user_difference($referencial_timezone)*3600;

if($timestamp<=0)
return '';

switch($format)
{
case DATE_FORMAT_TINY:
return date($LANG['date_format_tiny'],$timestamp);
break;

case DATE_FORMAT_SHORT:
return date($LANG['date_format_short'],$timestamp);
break;
case DATE_FORMAT:
return date($LANG['date_format'],$timestamp);
break;

case DATE_FORMAT_LONG:
return date($LANG['date_format_long'],$timestamp);
break;

case DATE_TIMESTAMP:
return $timestamp;
break;

case DATE_RFC822_F:
return date(DATE_RFC822_FORMAT,$timestamp);
break;

case DATE_RFC3339_F:
return date(DATE_RFC3339_FORMAT,$timestamp).($CONFIG['timezone']<0?'-':'+').sprintf('%02d:00',$CONFIG['timezone']);
break;

default:
return '';
}
}





function get_timestamp()
{
return $this->timestamp;
}





function get_year()
{
return(int)date('Y',$this->timestamp+$this->_compute_server_user_difference(TIMEZONE_USER)*3600);
}





function get_month()
{
return(int)date('m',$this->timestamp+$this->_compute_server_user_difference(TIMEZONE_USER)*3600);
}





function get_day()
{
return(int)date('d',$this->timestamp+$this->_compute_server_user_difference(TIMEZONE_USER)*3600);
}





function get_hours()
{
return(int)date('H',$this->timestamp+$this->_compute_server_user_difference(TIMEZONE_USER)*3600);
}





function get_minutes()
{
return(int)date('i',$this->timestamp+$this->_compute_server_user_difference(TIMEZONE_USER)*3600);
}





function get_seconds()
{
return(int)date('s',$this->timestamp+$this->_compute_server_user_difference(TIMEZONE_USER)*3600);
}





function to_date()
{
return date('Y-m-d',$this->timestamp);
}

# This should be static#








function check_date($month,$day,$year)
{
return checkdate($month,$day,$year);
}

## Private ##





function _compute_server_user_difference($referencial_timezone=0)
{
global $CONFIG,$User;


$server_hour=intval(date('Z')/3600)-intval(date('I'));

switch($referencial_timezone)
{

case TIMEZONE_SITE:
$timezone=$CONFIG['timezone']-$server_hour;
break;


case TIMEZONE_SYSTEM:
$timezone=0;
break;

case TIMEZONE_USER:
$timezone=$User->get_attribute('user_timezone')-$server_hour;
break;

default:
$timezone=0;
}
return $timezone;
}




var $timestamp=0;
}
?>