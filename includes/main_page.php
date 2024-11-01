<?php 

/************************************************************************************************/

//	wp2phone

//	main_page.php

//	http://wp2phone.com

/************************************************************************************************/

require_once(dirname(__FILE__).'/functions.php');

/************************************************************************************************/
/*										GENERAL SETTINGS										*/
/************************************************************************************************/

function wp2p_main_page()
{
	if (!current_user_can('manage_options'))
	{
		wp_die( __('You are not allowed to manage this plugin.','wp2phone_conversion') );
	}
	?>
	<div class="wrap">
		<div id="icon-options-general" class="icon32"></div>
		<h2><?php echo __( 'General Settings', 'wp2phone_conversion') ?></h2>
		
		<?php
			if ( isset($_POST['app-token'])) 
			{			
				$pref_table['app-token'] = stripcslashes($_POST['app-token']);
				if (isset($_POST['push-post'])) $pref_table['push-post'] = (int)$_POST['push-post'];
				else $pref_table['push-post'] = 0;
				$pref_table['push-tag'] = (int)$_POST['push-tag'];
				$pref_table['appstore-iphone'] = stripcslashes($_POST['appstore-iphone']);
				$pref_table['appstore-ipad'] = stripcslashes($_POST['appstore-ipad']);
				update_option('wp2p_pref', $pref_table); 
				?>
				<div class="updated"><p><strong><?php echo __('Settings saved.', 'wp2phone_conversion' ); ?></strong></p></div><?php 
			}
			else
			{
				$token = '';
				if (isset($_SESSION['wp2p-token']))
					$token = $_SESSION['wp2p-token'];
				if ($token == '')
				{
					$token = wp2p_get_token();
					$_SESSION['wp2p-token'] = $token;
				}
			}
		?>
		
		<table class="widefat" style="width:800px; margin-top:20px;">
			<thead>
				<tr>
					<th><?php echo __( 'Description', 'wp2phone_conversion')?></th>
				</tr>
			</thead>
			<tbody>
			 <tr>
				<td>
					<p style="margin:10px">
					<?php
					echo __('wp2phone is a complete solution to publish the content of your WordPress website in a native iPhone / iPad app.', 'wp2phone_conversion').'<br/>';
					echo '<br/>';
					echo __('Visit', 'wp2phone_conversion').' <a href="http://wp2phone.com" target="blank"><strong>wp2phone.com</strong></a> '.__('for more informations.', 'wp2phone_conversion') ;
					echo "<br />";
					?>
					</p>
					<img src="<?php echo WP2PHONE_PLUGIN_URL."/images/"; ?>wp2phone.png" width="220" height="44"  style="float:right" />
				</td>
			 </tr>
			</tbody>
		</table>
		
		<table class="widefat" style="width:800px; margin-top:20px;">
			<thead>
				<tr>
					<th><?php echo __( 'Plugin status', 'wp2phone_conversion').' ('.__( 'version', 'wp2phone_conversion').' '.WP2PHONE_VERSION.')';?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<p style="margin:10px">
						<?php
							$message = wp2p_check_plugin_installation_error();
							if(!$message)
								echo __( 'Plugin successfully installed.', 'wp2phone_conversion');
							else
								echo __( 'The plugin require folder permissions change :', 'wp2phone_conversion' ).' <span style="color:red;">'.$message.'</span>';
						?>
						</p>
					</td>
				</tr>
			</tbody>
		</table>
		
		<?php
			$opt_val = get_option( 'wp2p_pref' );
			if( $opt_val != "") 
			{
				foreach($opt_val as $key=>$value)
				{
					if ($key == 'push-post' and $value==1 )
						$opt_val[$key] = 'checked';
					else
						$opt_val[$key] = $value;
				}
			}
			$tags = get_tags(array('hide_empty' => 0, 'name' => 'category_parent', 'orderby' => 'id', 'selected' => $category->parent, 'hierarchical' => true, 'show_option_none' => __('None')));
		?>
		
		<form name = "form1" method = "post" action = "?page=wp2p-main-page">
		
			<table class = "widefat" style = "width:800px; margin-top:20px;">
				<thead>
					<tr>
						<th><?php echo __( 'wp2phone account', 'wp2phone_conversion');?></th>
					</tr>
				</thead>
				<tbody>
				 <tr>
					<td>
						<p style="margin:10px">
							<label for="app-token" ><?php echo __('App token', 'wp2phone_conversion' )." :"; ?> </label>
							<input type="text" name="app-token" id="app-token" value="<?php echo htmlspecialchars($opt_val['app-token']); ?>" size="32" maxlength="32" /><span class="description"> <?php echo __('Visit ', 'wp2phone_conversion' ); ?> <a href="http://wp2phone.com" target="_blank">wp2phone.com</a> <?php echo __('to create your own account.', 'wp2phone_conversion' ); ?></span>
							<span class="description"> </span>
						</p>
					</td>
				 </tr>
				</tbody>
			</table>
			
			<table class = "widefat" style = "width:800px; margin-top:20px;">
				<thead>
					<tr>
						<th><?php echo __( 'Push notifications', 'wp2phone_conversion').' <span style="color:#666">('.__( 'ultimate version only','wp2phone_conversion').")</span>";?></th>
					</tr>
				</thead>
				<tbody>
				 <tr>
					<td>
						<p style="margin:10px">
							<label for="push-post"><?php echo __( 'Send push when', 'wp2phone_conversion')." :"?></label>
							<input type="checkbox" name="push-post" id="push-post" value="1"  <?php echo $opt_val['push-post']; ?> />&nbsp;&nbsp;<?php echo __('publishing new posts with tag ', 'wp2phone_conversion' ); ?>&nbsp;&nbsp;
							<select name="push-tag" id="push-tag">
							<?php if($tags)
							{
								foreach ($tags as $tag)
								{
									if($opt_val['push-tag'] == $tag->term_id)
									{
										echo "<option value=".$tag->term_id." selected>".$tag->name."</option>";
									}
									else echo "<option value=".$tag->term_id.">".$tag->name."</option>";
								}
							}
							?>
							</select>
							<span class="description"> <?php echo __('Click ', 'wp2phone_conversion' ); ?> <a href="edit-tags.php?taxonomy=post_tag"><?php echo __('here ', 'wp2phone_conversion' ); ?></a> <?php echo __('to create new tag.', 'wp2phone_conversion' ); ?></span>
						</p>
					</td>
				 </tr>
				</tbody>
			</table>
			
			<table class = "widefat" style = "width:800px; margin-top:20px;">
				<thead>
					<tr>
						<th><?php echo __( 'Smart App Banners', 'wp2phone_conversion').' <span style="color:#666">('.__( 'Safari iOS 6 only','wp2phone_conversion').')</span>';?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							<p style="margin:10px">
								<label for="appstore-iphone" ><?php echo __('iPhone AppStore ID', 'wp2phone_conversion' )." :"; ?> </label>
								<input type="text" name="appstore-iphone" id="appstore-iphone" value="<?php echo htmlspecialchars($opt_val['appstore-iphone']); ?>" size="10" maxlength="10" />
								<span class="description"> </span>
							</p>
							<p style="margin:10px">
								<label for="appstore-ipad" ><?php echo __('iPad AppStore ID', 'wp2phone_conversion' )." :"; ?> </label>
								<input type="text" name="appstore-ipad" id="appstore-ipad" value="<?php echo htmlspecialchars($opt_val['appstore-ipad']); ?>" size="10" maxlength="10" />
								<span class="description"> </span>
							</p>
						</td>
					</tr>
				</tbody>
			</table>
			
			<p class="submit">
				<input type="submit" class="button-primary" name="wp2p_submit"  value="&nbsp;<?php echo __('Save', 'wp2phone_conversion' ); ?>&nbsp;" />
			</p>
		</form>
	</div>
	<?php 
}
?>
