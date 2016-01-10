<?php






























class Bench
{
## Public Methods ##



function Bench()
{
$this->start=Bench::get_microtime();
}



function stop()
{
$this->duration=Bench::get_microtime()-$this->start;
}






function to_string($digits=3)
{
$this->stop();
return number_round($this->duration,$digits);
}

## Private Methods ##






function get_microtime()
{
list($usec,$sec)=explode(" ",microtime());
return((float)$usec+(float)$sec);
}

## Private Attributes ##




var $start=0;




var $duration=0;
}

?>
