<?php

/*************************************************************************************************/

//	wp2phone

//	request-taxonomy.php

//	(c) 2011, 2012 wp2phone

//	http://wp2phone.com

/*************************************************************************************************/

header('content-type: text/json; charset=utf-8');

/*************************************************************************************************/

$array = array();

if (isset($_GET['wp2p-type']))
{
	require_once('../../../../wp-load.php');
	
	$array['status'] = 'OK';
	
	$args = array(	'type'				=> 'post',
					'child_of'			=> 0,
					'parent'			=> '',
					'orderby'			=> 'name',
					'order'				=> 'ASC',
					'hide_empty'		=> 0,
					'hierarchical'		=> 1,
					'exclude'			=> '',
					'include'			=> '',
					'number'			=> '',
					'taxonomy'			=> '',
					'pad_counts'		=> false	);
	
	if (($_GET['wp2p-type'] == 'all') || ($_GET['wp2p-type'] == 'category'))
	{
		$args['taxonomy'] = 'category';
		$array['category'] = get_categories($args);
	}
	
	if (($_GET['wp2p-type'] == 'all') || ($_GET['wp2p-type'] == 'tag'))
	{
		$args['taxonomy'] = 'post_tag';
		$array['tag'] = get_categories($args);
	}
}
else
{
	$array['status'] = 'ERR';
}

echo json_encode($array);

?>
