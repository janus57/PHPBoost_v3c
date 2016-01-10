<?php


























import('content/syndication/feed');
import('content/syndication/feeds_cat');







class FeedsList
{
function FeedsList(){}






function add_feed($cat_tree,$feed_type)
{
$this->list[$feed_type]=$cat_tree;
}





function get_feeds_list()
{
return $this->list;
}




var $list=array();
}

?>