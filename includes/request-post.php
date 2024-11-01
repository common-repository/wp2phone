<?php

/*************************************************************************************************/

//	wp2phone

//	request-post.php

//	(c) 2011, 2012 wp2phone

//	http://wp2phone.com

/*************************************************************************************************/

header('content-type: text/json; charset=utf-8');

/*************************************************************************************************/

if (isset($_GET['wp2p-type']) && isset($_GET['wp2p-id']))
{
	require_once('../../../../wp-load.php');
	
	$get_type = $_GET['wp2p-type'];
	$get_id = $_GET['wp2p-id'];
	
	if (isset($_GET['wp2p-version']))
		$version = $_GET['wp2p-version'];
	else
		$version = '';
	
	if ($get_type == 'post')
	{
		$result = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE ID=".$get_id." AND post_type='post' AND post_status='publish' LIMIT 1");
	}
	elseif ($get_type == 'page')
	{
		$result = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE ID=".$get_id." AND post_type='page' AND post_status='publish' LIMIT 1");
	}
	elseif ($get_type == 'category')
	{
		$result = $wpdb->get_results("SELECT DISTINCT p.* FROM $wpdb->posts AS p JOIN $wpdb->term_relationships AS tr ON p.ID=tr.object_id JOIN $wpdb->term_taxonomy AS tt ON tr.term_taxonomy_id=tt.term_taxonomy_id WHERE (tt.term_id=".$get_id." OR tt.parent=".$get_id.") AND tt.taxonomy='category' AND  p.post_status='publish' ORDER BY p.post_date DESC LIMIT 50");
	}
	elseif ($get_type == 'tag')
	{
		$result = $wpdb->get_results("SELECT DISTINCT p.* FROM $wpdb->posts AS p JOIN $wpdb->term_relationships AS tr ON p.ID=tr.object_id JOIN $wpdb->term_taxonomy AS tt ON tr.term_taxonomy_id=tt.term_taxonomy_id WHERE tt.term_id=".$get_id." AND tt.taxonomy='post_tag' AND  p.post_status='publish' ORDER BY p.post_date DESC LIMIT 50");
	}
	else
	{
		$result = array();
	}
	
	foreach ($result as $row/*$pos=>$value*/)
	{
		$result1 = $wpdb->get_results("SELECT pm.meta_value FROM $wpdb->postmeta pm WHERE pm.post_id=".$row->ID." AND pm.meta_key='wp2phone_thumbnail' LIMIT 1");
		if (count($result1) > 0)
		{
			$row->img_url = $result1[0]->meta_value;
		}
		else
		{
			$result2 = $wpdb->get_results("SELECT p.ID, p.guid FROM $wpdb->posts p, $wpdb->postmeta pm WHERE pm.post_id=".$row->ID." AND pm.meta_key='_thumbnail_id' AND p.ID=pm.meta_value AND p.post_type='attachment' LIMIT 1");
			if (count($result2) > 0)
			{
				$row->img_url = $result2[0]->guid;
			}
		}
	}
	
	echo json_encode($result);
}
else
{
	echo '[]';
}

?>
