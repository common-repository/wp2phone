<?php

/************************************************************************************************/

//	wp2phone

//	settings_page.php

//	http://wp2phone.com

/************************************************************************************************/

require_once(dirname(__FILE__).'/functions.php');

/************************************************************************************************/
/*										APP SETTINGS							   				*/
/************************************************************************************************/

function wp2p_settings_page()
{
	if (!current_user_can('manage_options'))
	{
		wp_die( __('You are not allowed to manage this plugin.','wp2phone_conversion') );
	}
	echo "<div class='wrap' >";
	$option_name = 'wp2p_settings';
	$hidden_field_name = 'wp2p_submit_hidden';
    if (isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y')
	{
		$message = "";
		wp2p_settings_stat('edited');
		$app_settings['version'] = WP2PHONE_VERSION;
		$app_settings['language'] = WPLANG;

		if (isset($_POST['share-email']))
			$app_settings['share-email'] = (int)$_POST['share-email'];
		else
			$app_settings['share-email'] = 0;
		if (isset($_POST['share-facebook']))
			$app_settings['share-facebook'] = (int)$_POST['share-facebook'];
		else
			$app_settings['share-facebook'] = 0;
		if (isset($_POST['share-twitter']))
			$app_settings['share-twitter'] = (int)$_POST['share-twitter'];
		else
			$app_settings['share-twitter'] = 0;
		if (isset($_POST['share-safari']))
			$app_settings['share-safari'] = (int)$_POST['share-safari'];
		else
			$app_settings['share-safari'] = 0;
		
		$app_settings['facebook-login'] = stripcslashes($_POST['facebook-login']);
		$app_settings['twitter-login'] = stripcslashes($_POST['twitter-login']);
		$app_settings['ad-id'] = stripcslashes($_POST['ad-id']);
		$app_settings['ad-network'] = stripcslashes($_POST['ad-network']);
		$app_settings['stat-id'] = stripcslashes($_POST['stat-id']);
		$app_settings['stat-network'] = stripcslashes($_POST['stat-network']);
		$app_settings['ad-link'] = stripcslashes($_POST['ad-link']);
		$option_value = get_option( $option_name );
		foreach ($_FILES as $key=>$value)
		{
			if ($_FILES[$key]['name'] != "")
			{
				$filename = WP2PHONE_UPLOAD_FOLDER_PATH;
				$error = wp2p_check_plugin_installation_error();
				if (!$error)
				{
					$maxt = 307200;
					$msg = wp2p_image_upload($key,$filename,$maxt,$_POST[$key.'min_width'],$_POST[$key.'min_height'], $_POST[$key.'max_width'],$_POST[$key.'max_height']);
					if ($msg['status'])
					{
						if ($msg['error'] <= 0)
						{
							$app_settings[$key] = $msg['file-name'];
							$img_edited = true;
						}
					}
					elseif($option_value and isset($option_value[$key]))
					{
						if($_POST[$key.'image'] == "deleted")
						{
							$img_deleted = true;
						}
						else $app_settings[$key] = $option_value[$key];
					}
					if($message != '')
					{
						$message .= '<br/>';
					}
					$message .= $msg['msg'];
				}
				else
				{
					$error = __('Ad URL : ', 'wp2phone_conversion' ).$error;
					echo '<div class="updated" style="border-color: #c00;background-color: #ffebe8"><p><strong>'. $error.'</strong></p></div>';
				}
			}
			else 
			{
				if($option_value and isset($option_value[$key]) and $option_value[$key] != "")
				{
					if($_POST[$key.'image'] == "deleted")
					{
						$img_deleted = true;
						
					}
					else
					{
						$app_settings[$key] = $option_value[$key];
					}
				}
			}
			if($option_value and isset($option_value[$key]) and $option_value[$key] != "")
			{
				if( $img_deleted or $img_edited)
				{
					$filename_to_delete = WP2PHONE_UPLOAD_FOLDER_PATH.$option_value[$key];
					if (file_exists($filename_to_delete))
					{
						unlink($filename_to_delete);
					}
				}
			}
		}
		update_option( $option_name, $app_settings); 
		if($message != '')
		{
			echo '<div class="updated" style="border-color: #c00;background-color: #ffebe8"><p><strong>'. $message.'</strong></p></div>';
		}
		?>
		<div class="updated"><p><strong><?php echo __('Settings saved.', 'wp2phone_conversion' ); ?></strong></p></div><?php 
	}
    	$option_value = get_option( $option_name );
    	
		if( $option_value != "") 
		{
			foreach($option_value as $key=>$value)
			{
				if (($key == 'share-email' or $key == 'share-facebook' or $key == 'share-safari' or $key == 'share-twitter') and $value==1 )
					$option_value[$key] = 'checked';
				else
					$option_value[$key] = $value;
			}
		}
		if( isset($_GET) and $_GET['action'] == "publish") 
				{
					wp2p_settings_stat('save');
					$tab_table = get_option( 'wp2p_tab');
					$settings_table = get_option('wp2p_settings');
					update_option( 'wp2p_tab_saved', $tab_table);
					update_option( 'wp2p_settings_saved', $settings_table);
				}
		?>	
    	<div id="icon-tools" class="icon32"><br /></div><h2><?php echo __( 'App Settings', 'wp2phone_conversion') ?></h2>
    	<?php $statut=get_option( 'wp2p_published');
			if($statut=='true') $var="none";
			?>
			<div  class="updated" style="display:<?php echo $var ?>" id="show_publish">
				<p>
					<strong>
					<?php echo  __('You made some changes, click Publish to make them available on your app.', 'wp2phone_conversion' ); ?>
					<span style="float:right; margin-right:0px">
					<a class="button-primary" href="?page=wp2p-app-settings&action=publish" id="publish" title="This button will activate changes on devices." style=" color:#FFFFFF"> 
					<?php echo __( 'Publish', 'wp2phone_conversion') ?>
					</a>
					</span>
					</strong>
				</p>
			</div>
			<br/>
		<form name="form1" method="post" enctype="multipart/form-data" action="?page=wp2p-app-settings">
			<table class="widefat" style="width:800px;">
				<thead>
					<tr>
					<th><?php echo __( 'Allow', 'wp2phone_conversion'); ?></th>
				  </tr>
				</thead>
				<tbody>
					 <tr>
						<td>
							<p style="margin:10px;">
								<label for="share-facebook"><?php echo __( 'Share on Facebook', 'wp2phone_conversion')." :"?></label>
								<input type="checkbox" name="share-facebook" id="share-facebook" value="1"  <?php echo $option_value['share-facebook']; ?> >
								</input>
							</p>
							<p style="margin:10px;">
								<label for="share-twitter"><?php echo __( 'Share on Twitter', 'wp2phone_conversion')." :"?></label>
								<input type="checkbox" name="share-twitter" id="share-twitter" value="1" <?php echo $option_value['share-twitter']; ?> >
								</input>
							</p>
							<p style="margin:10px;">
								<label for="share-email"><?php echo __( 'Send by Email', 'wp2phone_conversion')." :"?></label>
								<input type="checkbox" name="share-email" id="share-email" value="1"  <?php echo $option_value['share-email']; ?> >
								</input>
							</p>
							<p style="margin:10px;">
								<label for="share-safari"><?php echo __( 'Open with Safari', 'wp2phone_conversion')." :"?></label>
								<input type="checkbox" name="share-safari" id="share-safari" value="1" <?php echo $option_value['share-safari']; ?> > 
								</input>
							</p>
						</td>
					 </tr>
				</tbody>
			</table>
			<table class="widefat" style="width:800px; margin-top:20px;">
				<thead>
					<tr>
						<th><?php echo __( 'Social accounts', 'wp2phone_conversion')?></th>
					</tr>
				</thead>
				<tbody>
					 <tr>
						<td>
							<p style="margin:10px;">
								<label for="facebook-login" ><?php echo __('Facebook', 'wp2phone_conversion' )." :"; ?> </label>
								<input type="text" name="facebook-login" id="facebook-login" size="40" value="<?php echo htmlspecialchars($option_value['facebook-login']); ?>" />
								<span class="description"> (<?php echo __( 'optionnal', 'wp2phone_conversion')?>)</span>
							</p>			
							<p style="margin:10px;">
								<label for="twitter-login" ><?php echo __('Twitter', 'wp2phone_conversion' )." :"; ?> </label>
								<input type="text" name="twitter-login" id="twitter-login" size="20" value="<?php echo htmlspecialchars($option_value['twitter-login']); ?>" />
								<span class="description"> (<?php echo __( 'optionnal', 'wp2phone_conversion')?>)</span>
							</p>
						</td>
					 </tr>
				</tbody>
			</table>
			
			<table class="widefat" style="width:800px; margin-top:20px;">
				<thead>
					<tr>
						<th><?php echo __( 'Ad-Network account','wp2phone_conversion')." <span style='color:#666'>(".__( 'premium or ultimate version only','wp2phone_conversion').")</span>"; ?></th>
				  </tr>
				</thead>
				<tbody>
					 <tr>
						<td>
							<p style="margin:10px;">
								<label for="ad-id" ><?php  echo __('AdMob Publisher ID', 'wp2phone_conversion' )." :"; ?> </label>
								<input type="text" name="ad-id" id="ad-id"  size="20" maxlength="32" value="<?php echo htmlspecialchars($option_value['ad-id']); ?>" />
								<span class="description"> <?php echo __( 'Visit ', 'wp2phone_conversion')?> <a href="http://www.admob.com" target="_blank">www.admob.com</a> <?php echo __( 'to create your own account', 'wp2phone_conversion')?>.</span>	
								<input type="hidden" name="ad-network" value="admob" />
							</p>
						</td>
					 </tr>
				</tbody>
			</table>
			
			<table class="widefat" style="width:800px; margin-top:20px;">
				<thead>
					<tr>
						<th><?php echo __( 'Analytics account','wp2phone_conversion')?></th>
					</tr>
				</thead>
				<tbody>
					 <tr>
						<td>
							<p style="margin:10px;">
								<label for="stat-id" ><?php echo __('Flurry App Key', 'wp2phone_conversion' )." :"; ?></label>
								<input type="text" name="stat-id" id="stat-id"  size="20" maxlength="32" value="<?php echo htmlspecialchars($option_value['stat-id']); ?>" />
								<span class="description"> <?php echo __( 'Visit ', 'wp2phone_conversion')?> <a href="http://www.flurry.com" target="_blank">www.flurry.com</a> <?php echo __( 'to create your own account', 'wp2phone_conversion')?>.</span>	
								<input type="hidden" name="stat-network" value="flurry" />
							</p>
						</td>
					 </tr>
				</tbody>
			</table>
			
			<table class="widefat" style="width:800px; margin-top:20px;">
				<thead>
					<tr>
						<th><?php echo __( 'Ad settings','wp2phone_conversion')." <span style='color:#666'>(".__( 'premium or ultimate version only','wp2phone_conversion').")</span>"?></th>
					</tr>
				</thead>
				<tbody>
					 <tr>
						<td>
							<div style="margin:10px">
								<?php wp2p_file_upload_area(__('Ad image', 'wp2phone_conversion' ),"ad-image",$option_value['ad-image'], 320, 460, 768, 1004); ?>
								<br/><br/>
								<p>
									<label for="ad-link"><?php echo __('Ad link', 'wp2phone_conversion' )." :"; ?><span class="description"></span></label>
									<input type="text" name="ad-link" id="ad-link" size="40" value="<?php echo $option_value['ad-link']?>" />
								</p>
							</div>
						</td>
					 </tr>
				</tbody>
			</table>
			<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y" />
			<p class="submit">
				<input type="submit" class="button-primary" name="wp2p_submit" value="&nbsp;<?php echo __('Save', 'wp2phone_conversion' ); ?>&nbsp;" />
			</p>
		</form>
	</div>
	<?php
}
?>