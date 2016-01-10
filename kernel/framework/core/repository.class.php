<?php


























import('core/application');






class Repository
{




function Repository($url)
{
$this->url=$url;
if(function_exists('simplexml_load_file'))
{
$this->xml=@simplexml_load_file($this->url);
if($this->xml==false)
{
$this->xml=null;
}
}
}





function check($app)
{
global $CONFIG;
$xpath_query='//app[@id=\''.$app->get_id().'\' and @type=\''.$app->get_type().'\']/version[@language=\''.$app->get_language().'\']';

if($this->xml!=null)
{
$newerVersions=array();
$versions=$this->xml->xpath($xpath_query);
$nbVersions=$versions!=false?count($versions):0;

for($i=0;$i<$nbVersions;$i++)
{
$rep_app=clone($app);
$rep_app->load($versions[$i]);

if(version_compare($app->get_version(),$rep_app->get_version(),'<')>0)
{
if($rep_app->check_compatibility())
{
$newerVersions[$rep_app->get_version()]=$i;
}
}
}


$firstNewVersion=count($newerVersions)>0?min(array_keys($newerVersions)):'';
if(!empty($firstNewVersion))
{
$app->load($versions[$newerVersions[$firstNewVersion]]);
return $app;
}
}
return null;
}




function get_url(){return $this->url;}

var $url='';
var $xml=null;
};

?>