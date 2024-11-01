<?php

/*************************************************************************************************/

//	wp2phone

//	request-comment.php

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
	
	if ($get_type == 'post') // all comments from post_ID
	{
		$result = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_post_ID=".$get_id." AND comment_approved='1' ORDER BY comment_date DESC LIMIT 50");
	}
	elseif ($get_type == 'comment') // a single comment from its comment_ID
	{
		$result = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_ID=".$get_id." AND comment_approved='1' LIMIT 1");
	}
	else
	{
		$result = array();
	}
	
	echo json_encode($result);
}
else
{
	echo '[]';
}

?>
