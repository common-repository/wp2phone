<?php

/*************************************************************************************************/

//	wp2phone

//	request-interface.php

//	(c) 2011, 2012 wp2phone

//	http://wp2phone.com

/*************************************************************************************************/

header('content-type: text/json; charset=utf-8');

/*************************************************************************************************/

require_once('../../../../wp-load.php');

/*************************************************************************************************/

if (isset($_GET['wp2p-version']))
	$version = $_GET['wp2p-version'];
else
	$version = '';

$settings = get_option('wp2p_settings_saved');
$tab = get_option('wp2p_tab_saved');

if ($settings && $tab)
{
	$upload_folder_url = get_bloginfo('url').'/wp-content/uploads/wp2phone/';
	
	foreach ($tab as $key=>$value)
	{
		if ($tab[$key]['nav-image'] != '') $tab[$key]['nav-url'] = $upload_folder_url . $value['nav-image'];
		if ($tab[$key]['header-image'] != '') $tab[$key]['header-url'] = $upload_folder_url . $value['header-image'];
	}
	if ($settings['ad-image'] != '') $settings['ad-url'] = $upload_folder_url . $settings['ad-image'];
	
	$json['settings'] = $settings;
	$json['tab'] = $tab;
	
	echo json_encode($json);
}
else
{
	echo '{}';
}

?>
