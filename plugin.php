<?php
/*
Plugin Name: wp2phone
Plugin URI: http://wp2phone.com
Version: 0.1.6
Description: wp2phone is a complete solution to publish the content of your WordPress website in a native iPhone/iPad app.
Author: wp2phone
Author URI: http://wp2phone.com
*/

/************************************************************************************************/
//	VERSION
/************************************************************************************************/

if (defined('WP2PHONE_VERSION')) return;
define('WP2PHONE_VERSION', '0.1.6');

/************************************************************************************************/
//	FOLDERS & PATHS
/************************************************************************************************/

global $wpdb;
define('WP2PHONE_UPLOAD_FOLDER_URL', get_bloginfo('url').'/wp-content/uploads/wp2phone/');
define('WP2PHONE_UPLOAD_FOLDER_PATH', ABSPATH.'wp-content/uploads/wp2phone/');
define('WP2PHONE_PLUGIN_PATH', dirname(__FILE__));
define('WP2PHONE_PLUGIN_FOLDER', basename(WP2PHONE_PLUGIN_PATH));
if (defined('WP_ADMIN') && defined('FORCE_SSL_ADMIN') && FORCE_SSL_ADMIN)
{
    define('WP2PHONE_PLUGIN_URL', rtrim(str_replace('http://','https://', WP_PLUGIN_URL), '/') . '/' . WP2PHONE_PLUGIN_FOLDER );
}
else
{
	define('WP2PHONE_PLUGIN_URL', WP_PLUGIN_URL . '/' . WP2PHONE_PLUGIN_FOLDER );
}

/************************************************************************************************/
//	INCLUDES
/************************************************************************************************/

require_once(dirname(__FILE__).'/includes/functions.php');

/************************************************************************************************/
//	ADD ADMIN MENU
/************************************************************************************************/

add_action('admin_menu', 'wp2p_admin_menu');
add_action('admin_init', 'wp2p_admin_init');
add_action('wp_ajax_wp2p_action', 'wp2p_action_callback');
add_action('wp_json_wp2p_json', ' wp2p_json');
add_action('publish_post', 'wp2p_publish_post');
add_action('wp_head', 'wp2p_head');

function wp2p_publish_post($post_id)
{ 
	wp2p_send_push_for_post($post_id);
}

function wp2p_admin_init()
{
	if (!get_option('wp2p_pref'))
	{ 
		$pref['app-token'] = '';
		$pref['push-post'] = 0;
		$pref['appstore-iphone'] = '';
		$pref['appstore-ipad'] = '';
		update_option('wp2p_pref', $pref);
	}
	
	$tab = get_option( 'wp2p_tab');
	if (gettype($tab) != "array")
	{
		$categories = get_categories(array('hide_empty' => 0, 'name' => 'category_parent', 'orderby' => 'id', 'selected' => $category->parent, 'hierarchical' => true, 'show_option_none' => __('None')));
		if (sizeof($categories) > 0)
		{
			$tab['id'] = (int) ($categories[0]->term_id);
			$tab['nav-color'] = wp2p_nav_color;
			$tab['back-color'] = wp2p_back_color;
			$tab['text-color'] = wp2p_text_color;
			$tab['select-color'] = wp2p_select_color;
			$tab['cell-color'] = wp2p_cell_color;
			$tab['icon-name'] = wp2p_icon_name;
			$tab['tab-title'] = "Home";
			$tab['nav-title'] = "Home";
			$tab['type'] = "category";
			$tab['tab-number'] = 1;
			$tab['header-link'] = "";
			$tab['show-comments'] = 1;
			$tab['show-image'] = 1;
			$tab['show-image-post'] = 1;
			$tab['show-date'] = 1;
			$tab['show-web'] = 1;
			$tab['show-image-header'] = 1;
			$tab['show-date-post'] = 1;
			$tab['show-title-post'] = 1;
			$tab['show-share'] = 1;
			$tab['latitude'] = "";
			$tab['longitude'] = "";
			$tab['url'] = "";
			$tab_table[0] = $tab;
		}
		else $tab_table = array();
		update_option( 'wp2p_tab', $tab_table);
		update_option( 'wp2p_tab_saved', $tab_table);
	}
	if (!get_option( 'wp2p_published'))
	{
		update_option( 'wp2p_published','true');
	}
	if (!get_option( 'wp2p_settings'))
	{
		$tab_settings['version'] = WP2PHONE_VERSION;
		$tab_settings['language'] = WPLANG;
		$tab_settings['share-email'] = 1;
		$tab_settings['share-facebook'] = 1;
		$tab_settings['share-twitter'] = 1;
		$tab_settings['share-safari'] = 1;
		update_option( 'wp2p_settings',$tab_settings);
		update_option( 'wp2p_settings_saved',$tab_settings);
	}
	load_theme_textdomain('wp2phone_conversion', WP2PHONE_PLUGIN_PATH.'/languages');
}

function wp2p_admin_scripts()
{
    wp_register_script( 'wp2p_script', WP2PHONE_PLUGIN_URL.'/js/script.js' );
    wp_enqueue_script('wp2p_script');
    wp_enqueue_script( array('jquery', 'jquery-ui-core', 'wp-lists', 'jquery-ui-sortable', 'farbtastic') );
    wp_localize_script( 'functions', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}

function wp2p_stylesheet() 
{
    wp_register_style('wp2p_StyleSheets', WP2PHONE_PLUGIN_URL.'/css/style.css');
    wp_enqueue_style( 'wp2p_StyleSheets');
    wp_enqueue_style( 'farbtastic' );
}

function wp2p_admin_menu()
{
	session_start();
	add_menu_page(__('wp2phone plugin','wp2phone_conversion'), __('wp2phone','wp2phone_conversion'), 'manage_options', 'wp2p-main-page', 'wp2p_main_page', WP2PHONE_PLUGIN_URL.'/images/icon16.png' );
	$wp2p_main =add_submenu_page('wp2p-main-page', __('General Settings &lsaquo; wp2phone','wp2phone_conversion'), __('General','wp2phone_conversion'), 'manage_options', 'wp2p-main-page', 'wp2p_main_page');
	$wp2p_general = add_submenu_page('wp2p-main-page', __('App Settings &lsaquo; wp2phone','wp2phone_conversion'), __('App','wp2phone_conversion'), 'manage_options', 'wp2p-app-settings', 'wp2p_settings_page');
	$wp2p_content = add_submenu_page('wp2p-main-page', __('Content Settings &lsaquo; wp2phone','wp2phone_conversion'), __('Content','wp2phone_conversion'), 'manage_options', 'wp2p-content', 'wp2p_content_page');
	add_action('admin_print_scripts-'.$wp2p_content, 'wp2p_admin_scripts');
	add_action('admin_print_styles-'.$wp2p_content, 'wp2p_stylesheet');
	add_action('admin_print_styles-'.$wp2p_main, 'wp2p_stylesheet');
	add_action('admin_print_styles-'.$wp2p_general, 'wp2p_stylesheet');
	add_action('admin_print_scripts-'.$wp2p_general, 'wp2p_admin_scripts');
}

function wp2p_head()
{
	$pref = get_option('wp2p_pref');
	
	if ($pref && isset($pref['appstore-iphone'])) $iPhoneAppID = $pref['appstore-iphone']; else $iPhoneAppID = '';
	if ($pref && isset($pref['appstore-ipad'])) $iPadAppID = $pref['appstore-ipad']; else $iPadAppID = '';
	
	if (($iPhoneAppID != '') || ($iPadAppID != ''))
	{
		?>
		<script type="text/javascript">
		<?php
		if ($iPhoneAppID != '')
		{
			?>
			if (navigator.userAgent.match(/iPhone/i))
			{
				document.write('<meta name="apple-itunes-app" content="app-id=<?php echo $iPhoneAppID; ?>">');
			}
			<?php
		}
		if ($iPadAppID != '')
		{
			?>
			if (navigator.userAgent.match(/iPad/i))
			{
				document.write('<meta name="apple-itunes-app" content="app-id=<?php echo $iPadAppID; ?>">');
			}
			<?php
		}
		?>
		</script>
		<?php
	}
}

/************************************************************************************************/
//	DEFINE ADMIN PAGES
/************************************************************************************************/

include(dirname(__FILE__).'/includes/main_page.php'); 
include(dirname(__FILE__).'/includes/settings_page.php'); 
include(dirname(__FILE__).'/includes/content_page.php');

?>