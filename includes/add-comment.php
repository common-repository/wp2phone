<?php

/*************************************************************************************************/

//	wp2phone

//	add-comment.php

//	(c) 2011, 2012 wp2phone

//	http://wp2phone.com

/*************************************************************************************************/

header('content-type: text/json; charset=utf-8');

/*************************************************************************************************/

if (isset($_POST['wp2p-post-id']) && isset($_POST['wp2p-author']) && isset($_POST['wp2p-email']) && isset($_POST['wp2p-content']))
{
	require_once('../../../../wp-load.php');
	
	if (isset($_GET['wp2p-version']))
		$version = $_GET['wp2p-version'];
	else
		$version = '';
	
	$data = array(	'comment_post_ID'		=> (int)$_POST['wp2p-post-id'],
					'comment_author'		=> trim($_POST['wp2p-author']),
					'comment_author_email'	=> trim($_POST['wp2p-email']),
					'comment_author_url'	=> '',
					'comment_content'		=> trim($_POST['wp2p-content']),
					'comment_type'			=> '',
					'comment_parent'		=> 0,
					'user_id'				=> 0,
					'comment_author_IP'		=> $_SERVER['REMOTE_ADDR'],
					'comment_agent'			=> 'wp2phone',
					'comment_date'			=> current_time('mysql')
					/*'comment_approved'	=> 1,*/
	);
	
	$comment_ID = wp_insert_comment($data);
	if ($comment_ID != 0)
	{
		$result['status'] = 'OK';
		$result['comment_ID'] = $comment_ID;
	
		echo json_encode($result);
	}
	else
	{
		echo '{"status":"ERR", "error":"WP insert comment error"}';
	}
}
else
{
	echo '{"status":"ERR", "error":"invalid request"}';
}

?>
