<?php

/************************************************************************************************/

//	wp2phone

//	content_page.php

//	http://wp2phone.com

/************************************************************************************************/

require_once(dirname(__FILE__).'/functions.php');

/************************************************************************************************/
/*										ADD & EDIT CONTENT						   				*/
/************************************************************************************************/

function wp2p_add_edit_tab($var)
{
	if (!current_user_can('manage_options'))
	{
		wp_die( __('You are not allowed to manage this plugin.','wp2phone_conversion') );
	}
	global $wpdb;
	$hidden_field_name = 'wp2p_submit_hidden';
	$hidden_field_name2 = 'wp2p_submit_hidden_action';
	$opt_name = 'wp2p_tab';
	$tags = get_tags(array('hide_empty' => 0, 'name' => 'category_parent', 'orderby' => 'id', 'selected' => $category->parent, 'hierarchical' => true, 'show_option_none' => __('None')));
	$pages = get_pages();
	$tab = "";
	if ($var == 'add')
	{
		$check = array("checked","","");
		$display = array("inline","inline","inline");
		$contenue = array("","","");
		$hidden = $var;
		$tab_num = "";
		$action_name = __("Add a new tab", 'wp2phone_conversion' );
		$tab['show-comments'] = wp2p_check_box_stat;
		$tab['show-image'] = wp2p_check_box_stat;
		$tab['show-image-post'] = wp2p_check_box_stat;
		$tab['show-date'] = wp2p_check_box_stat;
		$tab['show-web-version'] = wp2p_check_box_stat;
		$tab['show-image-header'] = wp2p_check_box_stat;
		$tab['show-date-post'] = wp2p_check_box_stat;
		$tab['show-title-post'] = wp2p_check_box_stat;
		$tab['show-share'] = wp2p_check_box_stat;
		$tab['tab-title']= '';
		$tab['nav-title']= '';
		if (isset($_SESSION) and !empty($_SESSION['wp2p-colors']))
		{
			$tab['nav-color'] = $_SESSION['wp2p-colors']['nav-color'];
			$tab['back-color'] = $_SESSION['wp2p-colors']['back-color'];
			$tab['text-color'] = $_SESSION['wp2p-colors']['text-color'];
			$tab['select-color'] = $_SESSION['wp2p-colors']['select-color'];
			$tab['cell-color'] = $_SESSION['wp2p-colors']['cell-color'];
		}
		else
		{
			$tab['nav-color'] = wp2p_nav_color;
			$tab['back-color'] = wp2p_back_color;
			$tab['text-color'] = wp2p_text_color;
			$tab['select-color'] = wp2p_select_color;
			$tab['cell-color'] = wp2p_cell_color;
		}
		$tab['icon-name'] = wp2p_icon_name; 
	}
	
	if ($var == 'edit')
	{
		$action_name = __("Edit tab", 'wp2phone_conversion' );
		$hidden = $var;
		$display = array("inline","inline","inline");
		$tab_num = $_GET['tab'];
		if($tab_table = get_option( 'wp2p_tab'));
		{
			foreach($tab_table as $pos=>$value)
			{
				if($value['tab-number'] == (int)$tab_num)
				{
					$result = $pos;
					break;
				}
			}
			$tab['tab-title'] = $tab_table[(int)$result]['tab-title'];
			$tab['nav-title'] = $tab_table[(int)$result]['nav-title']; 	
			$tab['nav-color'] = $tab_table[(int)$result]['nav-color'];
			$tab['back-color'] = $tab_table[(int)$result]['back-color']; 	
			$tab['text-color'] = $tab_table[(int)$result]['text-color'];
			$tab['select-color'] = $tab_table[(int)$result]['select-color'];
			$tab['cell-color'] = $tab_table[(int)$result]['cell-color']; 
			$tab['header-link'] = $tab_table[(int)$result]['header-link'];
			
			$tab['nav-image'] = $tab_table[(int)$result]['nav-image'];
			$tab['header-image'] = $tab_table[(int)$result]['header-image'];
			$tab['icon-name'] = $tab_table[(int)$result]['icon-name']; 	
			$tab['type'] = $tab_table[(int)$result]['type'];
			$tab['latitude'] = $tab_table[(int)$result]['latitude'];
			$tab['longitude'] = $tab_table[(int)$result]['longitude'];
			$tab['url'] = $tab_table[(int)$result]['url'];
			
			if ($tab_table[(int)$result]['show-comments'] == 1 )
			{
				$tab['show-comments'] = "checked";
			}
			if ($tab_table[(int)$result]['show-image'] == 1 )
			{
				$tab['show-image'] ='checked';
			}
			if ($tab_table[(int)$result]['show-image-post'] == 1 )
			{
				$tab['show-image-post'] ='checked';
			}
			if ($tab_table[(int)$result]['show-date'] == 1 )
			{
				$tab['show-date'] ='checked';
			}
			if ($tab_table[(int)$result]['show-web'] == 1 )
			{
				$tab['show-web-version'] ='checked';
			}
			
			
			if ($tab_table[(int)$result]['show-date-post'] == 1 )
			{
				$tab['show-date-post'] ='checked';
			}
			if ($tab_table[(int)$result]['show-title-post'] == 1 )
			{
				$tab['show-title-post'] ='checked';
			}
			if ($tab_table[(int)$result]['show-image-header'] == 1 )
			{
				$tab['show-image-header'] ='checked';
			}
			if ($tab_table[(int)$result]['show-share'] == 1 )
			{
				$tab['show-share'] ='checked';
			}
			
			if($tab['type'] == "category")
			 {
				$tab['type'] = "category";
				$check[1] = "checked"; 
				$display[0] = "inline";
				$contenue[0] = $tab_table[(int)$result]['id'];
			}
			elseif($tab['type'] == "tag") 
			{
				$tab['type'] = "tag"; 
				$check[2] = "checked";
				$display[1] = "inline";
				$contenue[1] = $tab_table[(int)$result]['id'];
			}
			elseif ($tab['type'] == "page")
			{
				$check[0] = "checked";
				$tab['type'] = "page"; 
				$display[2] = "inline";
				$contenue[2] = $tab_table[(int)$result]['id'];
			}
			elseif ($tab['type'] == "map")
			{
				$check[3] = "checked";
				$tab['type'] = "map"; 
			}
			elseif ($tab['type'] == "web")
			{
				$check[4] = "checked";
				$tab['type'] = "web"; 
			}
		}
	}
?>
	<div id="icon-themes" class="icon32"><br /></div><h2><?php echo $action_name ?></h2>
	<br/>
	<form name="form1" method="post" action="?page=wp2p-content" enctype="multipart/form-data" onsubmit="return wp2p_not_empty_form ();">
		<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="<?php echo $hidden; ?>" />
		<input type="hidden" name="<?php echo $hidden_field_name2; ?>" value="<?php echo $tab_num; ?>" />
		<table class="widefat" style="width:800px;">
			<thead>
				<tr>
				<th><?php echo  __('Tab bar', 'wp2phone_conversion' ); ?></th>
			  </tr>
			</thead>
			<tbody>
				 <tr>
					<td>
						<p style="margin:10px">
							<label for="tabTitle"><?php echo  __('Title', 'wp2phone_conversion' )." :"; ?><span class="description"> (<?php echo  __('required', 'wp2phone_conversion' ); ?>)</span></label>
							<input type="text" name="tab-title" id="tabTitle" size="20" maxlength="20" value="<?php echo htmlspecialchars($tab['tab-title'])?>" />
						</p>
						<p style="margin:10px">
							<label for="button1" style="line-height:35px;vertical-align:middle;"><?php echo  __('Icon', 'wp2phone_conversion' )." :"; ?></label>
								<div id="listeh" style="float:left; width:45px; height:35px;margin-right:auto; margin-left:auto; text-align:center; "><img src="<?php echo WP2PHONE_PLUGIN_URL ; ?>/images/tab/glyphish-icons/<?php echo $tab['icon-name']?>" style=" margin-top:5px;text-align:center; " /></div>
								<input type="hidden" id="url" value="<?php echo WP2PHONE_PLUGIN_URL ; ?>/images/tab/glyphish-icons/"></input>
								<input id="image_from_list" name="icon-name" type="hidden" value="<?php echo $tab['icon-name']?>" />&nbsp;&nbsp;
								<input style="line-height:20px;vertical-align:buttom;" type="button" class="button-secondary" value="Change" id="button1" />
							<?php wp2p_icon_reader();?>
						</p>
					</td>
				</tr>
			</tbody>
		</table>

		<table class="widefat" style="width:800px; margin-top:20px;">
			<thead>
				<tr>
				<th><?php echo  __('Navigation bar', 'wp2phone_conversion' ); ?></th>
			  </tr>
			</thead>
			<tbody>
				 <tr>
					<td >
						<div style="margin:10px">
							<p>
								<label for="navTitle"><?php echo __('Title', 'wp2phone_conversion' )." :"; ?><span class="description"> (<?php echo  __('required', 'wp2phone_conversion' ); ?>)</span></label>
								<input type="text" name="nav-title" id="navTitle" size="20" maxlength="20" value="<?php echo htmlspecialchars($tab['nav-title'])?>" />
							</p>
								<div id="ilctabscolorpicker" style="float:left; margin-left:190px ;margin-top:35px; background:#eee; border:1px solid #ccc; position:absolute; z-index:1;"></div>
							<p >
								<label for="nav-color"><?php echo  __('Color', 'wp2phone_conversion' )." :"; ?></label>
								<input type="text" style="text-transform: uppercase;" id="nav-color" size="23" name="nav-color" value="<?php echo $tab['nav-color']?>" />
							</p>
							<?php wp2p_file_upload_area( __('Image', 'wp2phone_conversion' ),"nav-image",$tab['nav-image'], 0, 0, 260, 44); ?>
						</div>
					</td>
				</tr>
			</tbody>
		</table>

		<table class="widefat" style="width:800px; margin-top:20px;">
			<thead>
				<tr>
				<th><?php echo  __('Type of content', 'wp2phone_conversion' ); ?></th>
			  </tr>
			</thead>
			<tbody>
				 <tr>
					<td>
						<p style="margin:10px">
						<label>
							<input type="radio" name="type" id="type1"  value="page"  <?php echo $check[0]; ?>  />
							<span ><?php echo  __('Page', 'wp2phone_conversion' ); ?></span>
						</label>
						<select name="selector0" id="selection3" style="display:<?php echo $display[2];?>; ">
							<?php if($pages)
									{
										foreach ( $pages as $result )
										{
											if($contenue[2] == $result->ID)
											{
												echo "<option value=".$result->ID." selected>".$result->post_title."</option><br/>";
											}
											else echo "<option value=".$result->ID.">".$result->post_title."</option><br/>";
										}
									}
							?>
						</select>
						<br/>
						<label>
							<input type="radio" name="type" id="type2" value="category" <?php echo $check[1]; ?> />
							<span><?php echo  __('Posts of Category', 'wp2phone_conversion' ); ?></span><br/>
						</label>
						<?php wp_dropdown_categories(array('hide_empty' => 0, 'name' => 'selector1', 'orderby' => 'id', 'selected' =>$contenue[0], 'hierarchical' => true , 'width'=>'170')); ?>
						<br/>
						<label>
								<input type="radio" name="type" id="type3" value="tag" <?php echo $check[2]; ?> />
								<span ><?php echo  __('Posts with Tag', 'wp2phone_conversion' ); ?></span><br/>
						</label>
						<select name="selector2" id="selection2" style="display:<?php echo $display[1];?>;">
							<?php	if($tags)
									{
										foreach ($tags as $tag)
										{
											if($contenue[1] == $tag->term_id)
											{
												echo "<option value=".$tag->term_id." selected>".$tag->name."</option><br/>";
											} 
											else echo "<option value=".$tag->term_id.">".$tag->name."</option><br/>";
										}
									}
								?>
						</select>
						<br/>
						<label>
								<input type="radio" name="type" id="type4" value="map" <?php echo $check[3]; ?> />
								<span ><?php echo  __('Map', 'wp2phone_conversion' ); ?></span><br/>
						</label>
						<br/>
						<div style="margin:10px">
						<label>
								<input type="radio" name="type" id="type5" value="web" <?php echo $check[4]; ?> />
								<span ><?php echo  __('Web', 'wp2phone_conversion' ); ?></span><br/>
						</label>
						</div>
						<br/>
						<br>
						</p>
					</td>
				</tr>
			</tbody>
		</table>

		<table id="post_options" class="widefat" style="width:800px; margin-top:20px;">
			<thead>
				<tr>
				<th><?php echo  __('Post options', 'wp2phone_conversion' ); ?></th>
				<th></th>
				<th></th>
				<th></th>
			  </tr>
			</thead>
			<tbody>
				 <tr>
					<td >
						<div style="margin:10px">
							<p>
								<label for="show-image-post"><?php echo __( 'Show thumbnail', 'wp2phone_conversion')." :"?></label>
								<input type="checkbox" name="show-image-post" id="show-image-post" value="1" <?php echo $tab['show-image-post']; ?> ></input>
							</p>
							<p>
								<label for="show-date-post"><?php echo __( 'Show date', 'wp2phone_conversion')." :"?></label>
								<input type="checkbox" name="show-date-post" id="show-date-post" value="1" <?php echo $tab['show-date-post']; ?> ></input>
							</p>
							<p>
								<label for="show-title-post"><?php echo __( 'Show title', 'wp2phone_conversion')." :"?></label>
								<input type="checkbox" name="show-title-post" id="show-title-post" value="1" <?php echo $tab['show-title-post']; ?> ></input>
							</p>
							<p>
								<label for="show-comments"><?php echo __( 'Show comments', 'wp2phone_conversion')." :"?></label>
								<input type="checkbox" name="show-comments" id="show-comments" value="1" <?php echo $tab['show-comments']; ?> ></input>
							</p>
							<p>
								<label for="show-web-version"><?php echo __( 'Show web version', 'wp2phone_conversion')." :"?></label>
								<input type="checkbox" name="show-web-version" id="show-web-version" value="1" <?php echo $tab['show-web-version']; ?> ></input>
							</p>
							<p>
								<label for="show-share"><?php echo __( 'Show share button', 'wp2phone_conversion')." :"?></label>
								<input type="checkbox" name="show-share" id="show-share" value="1" <?php echo $tab['show-share']; ?> ></input>
							</p>
							</DIV>
					</td>
				</tr>
			</tbody>
		</table>

		<table id="map_options" class="widefat" style="width:800px; margin-top:20px;">
			<thead>
				<tr>
					<th><?php echo  __('Map options', 'wp2phone_conversion' ); ?></th>
				</tr>
			</thead>
			<tbody>
				 <tr>
					<td>
						<div style="margin:10px">
						<p>
							<label for="latitude"><?php echo  __('Latitude', 'wp2phone_conversion' )." :"; ?><span class="description"> (<?php echo  __('required', 'wp2phone_conversion' ); ?>)</span></label>
							<input type="text" name="latitude" id="latitude" size="10" value="<?php echo $tab['latitude']?>" maxlength="10" />
						</p>
						<p>
							<label for="longitude"><?php echo  __('Longitude', 'wp2phone_conversion' )." :"; ?><span class="description"> (<?php echo  __('required', 'wp2phone_conversion' ); ?>)</span></label>
							<input type="text" name="longitude" id="longitude" size="10" value="<?php echo $tab['longitude']?>" maxlength="10" />
						</p>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
		
		<table id="web_options" class="widefat" style="width:800px; margin-top:20px;">
			<thead>
				<tr>
					<th><?php echo  __('Web options', 'wp2phone_conversion' ); ?></th>
				</tr>
			</thead>
			<tbody>
				 <tr>
					<td>
						<div style="margin:10px">
						<p>
							<label for="url"><?php echo  __('URL', 'wp2phone_conversion' )." :"; ?><span class="description"> (<?php echo  __('required', 'wp2phone_conversion' ); ?>)</span></label>
							<input type="text" name="url" id="web_url" size="50" value="<?php echo $tab['url']?>"/>
						</p>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
		
		<table  id="body_settings" class="widefat" style="width: 800px; margin-top:20px;">
			<thead>
				<tr>
				<th><?php echo  __('Table options', 'wp2phone_conversion' ); ?></th>		
			  </tr>
			</thead>
			<tbody>
				 <tr>
					<td>
						<div style="margin:10px">
							<table style="boder-width:0px; padding:0px ;border-spacing:0px; margin-bottom:30px">
								<tr >
									<td style="border:none; boder-width:0px; padding: 0px ;border-spacing: 0px; margin:0px" >
										<div id="ilctabscolorpicker2" style="float:left; margin-left:0px ;margin-top:45px;background:#eee; border:1px solid #ccc; position:absolute; z-index:1;"></div>
										<label for="back-color">
											<span style="float:left;"> <?php echo  __('Background color', 'wp2phone_conversion' ); ?></span>
											<input type="text" style="text-transform: uppercase;" maxlength="7" size="18" id="back-color" name="back-color" value="<?php echo $tab['back-color']?>" />
										</label>
									</td>
									<td style="border:none; boder-width:0px; padding:0px ;border-spacing: 0px;" >
										<div id="ilctabscolorpicker5" style="float:left; margin-left:0px ;margin-top:45px;background:#eee; border:1px solid #ccc; position:absolute; z-index:1;"></div>
										<label for="line-color">
											<span style="float:left;"> <?php echo  __('Cell color', 'wp2phone_conversion' ); ?></span>
											<input type="text" style="text-transform: uppercase;" maxlength="7" size="18" id="line-color" name="cell-color" value="<?php echo $tab['cell-color']?>" />
										</label>
									</td>
									<td style="border:none; boder-width:0px; padding:0px ;border-spacing: 0px;" >
										<div id="ilctabscolorpicker3" style="float:left; margin-left:0px ;margin-top:45px;background:#eee; border:1px solid #ccc; position:absolute; z-index:1;"></div>
										<label for="text-color">
											<span style="float:left; "> <?php echo  __('Text color', 'wp2phone_conversion' ); ?></span>
											<input type="text" style="text-transform: uppercase;" maxlength="7" size="18" id="text-color" name="text-color" value="<?php echo $tab['text-color']?>" />
										</label>
									</td>
									<td style="border:none; boder-width:0px; padding:0px ;border-spacing: 0px;">
										<div id="ilctabscolorpicker4" style="float:left; margin-left:0px ;margin-top:45px;background:#eee; border:1px solid #ccc; position:absolute; z-index:1;"></div>
										<label for="select-color">
											<span style="float:left; "> <?php echo  __('Selected cell color', 'wp2phone_conversion' ); ?></span>
											<input type="text" style="text-transform: uppercase;" maxlength="7" size="18" id="select-color" name="select-color" value="<?php echo $tab['select-color']?>" />
										</label>
									</td>
									</p>
								</tr>
							</table>
							<?php wp2p_file_upload_area( __('Header image', 'wp2phone_conversion' ),"header-image",$tab['header-image'], 0, 0, 768, 400);
							if ($tab['header-image'] != "")
							{
								echo '<div style="height:50px;">&nbsp;</div>';
							}
							?>
							<p>
							<label for="header-link"><?php echo __('Header link', 'wp2phone_conversion' )." :"; ?><span class="description"></span></label>
							<input  type="text" name="header-link" id="header-link" size="50" value="<?php echo $tab['header-link']?>" />
							</p>
							<p >
								<label for="show-image-header"><?php echo __( 'Show thumbnails', 'wp2phone_conversion')." :"?></label>
								<input type="checkbox" name="show-image-header" id="show-image-header" value="1"  <?php echo $tab['show-image-header']; ?> >
								&nbsp;&nbsp;<?php echo __('in header', 'wp2phone_conversion' ); ?>&nbsp;&nbsp;
								</input>
							</p>
							<p >
								<label for="show-image"><?php echo __( 'Show thumbnail', 'wp2phone_conversion')." :"?></label>
								<input type="checkbox" name="show-image" id="show-image" value="1"  <?php echo $tab['show-image']; ?> >
								&nbsp;&nbsp;<?php echo __('in rows', 'wp2phone_conversion' ); ?>&nbsp;&nbsp;
								</input>
							</p>
							<p >
									<label for="show-date"><?php echo __( 'Show date', 'wp2phone_conversion')." :"?></label>
									<input type="checkbox" name="show-date" id="show-date" value="1"  <?php echo $tab['show-date']; ?> >
									&nbsp;&nbsp;<?php echo __('in rows', 'wp2phone_conversion' ); ?>&nbsp;&nbsp;
									</input>
								</p>
								<br/>
							</p>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
		
		<p class="submit">
			<input type="submit" class="button-primary" name="wp2p-submit" value="<?php echo  __('Save', 'wp2phone_conversion' ); ?>" />
		</p>
	</form>
	
	<script type="text/javascript">
		jQuery(document).ready(function()
		{
			if((jQuery("#type1").attr("checked") != "undefined" && jQuery("#type1").attr("checked") == "checked"))
			{
				jQuery('#body_settings').hide();
				jQuery('#map_options').hide();
				jQuery('#web_options').hide();
			}
			if((jQuery("#type2").attr("checked") != "undefined" && jQuery("#type2").attr("checked") == "checked"))
			{
				jQuery('#map_options').hide();
				jQuery('#web_options').hide();
			}
			if((jQuery("#type3").attr("checked") != "undefined" && jQuery("#type3").attr("checked") == "checked"))
			{
				jQuery('#map_options').hide();
				jQuery('#web_options').hide();
			}
		});
		jQuery(document).ready(function()
		{
			if((jQuery("#type4").attr("checked") != "undefined" && jQuery("#type4").attr("checked") == "checked"))
			{
				jQuery('#body_settings').hide();
				jQuery('#post_options').hide();
				jQuery('#web_options').hide();
			}
			if((jQuery("#type5").attr("checked") != "undefined" && jQuery("#type5").attr("checked") == "checked"))
			{
				jQuery('#body_settings').hide();
				jQuery('#post_options').hide();
				jQuery('#map_options').hide();
			}
		});
		jQuery("#type1").click(function () 
		{
			jQuery('#body_settings').hide();
			jQuery('#map_options').hide();
			jQuery('#web_options').hide();
			jQuery('#post_options').fadeIn("10");		
		});
		jQuery("#type2").click(function ()
		{
			jQuery('#body_settings').fadeIn("slow");
			jQuery('#map_options').hide();
			jQuery('#web_options').hide();
			jQuery('#post_options').fadeIn("slow");		
		});
		jQuery("#type3").click(function ()
		{
			jQuery('#body_settings').fadeIn("slow");
			jQuery('#map_options').hide();
			jQuery('#web_options').hide();
			jQuery('#post_options').fadeIn("slow");		
		});
		jQuery("#type4").click(function ()
		{
			jQuery('#map_options').fadeIn("10");
			jQuery('#web_options').hide();
			jQuery('#body_settings').hide();
			jQuery('#post_options').hide();
		});
		jQuery("#type5").click(function ()
		{
			jQuery('#web_options').fadeIn("10");
			jQuery('#map_options').hide();
			jQuery('#body_settings').hide();
			jQuery('#post_options').hide();
		});
	</script>

	<script type="text/javascript">
		jQuery(document).ready(function() 
		{		
			var wp2p_color_picker = ['#ilctabscolorpicker', '#ilctabscolorpicker2', '#ilctabscolorpicker3', '#ilctabscolorpicker4', '#ilctabscolorpicker5'];
			var wp2p_color_field = ['#nav-color', '#back-color', '#text-color', '#select-color', '#line-color'];
			jQuery.each(wp2p_color_picker, function(indice, value)
			{
				jQuery(wp2p_color_picker[indice]).hide();
				jQuery(wp2p_color_picker[indice]).farbtastic(wp2p_color_field[indice]);
				jQuery(wp2p_color_field[indice]).click(function(){jQuery(wp2p_color_picker[indice]).fadeIn("100");});
				jQuery(wp2p_color_field[indice]).blur(function(){jQuery(wp2p_color_picker[indice]).hide()});
				jQuery(wp2p_color_field[indice]).keyup(function(){var b = jQuery(wp2p_color_field[indice]).val(),a = b;if(a.charAt(0) != "#"){a = "#"+a}a = a.replace(/[^#a-fA-F0-9]+/,"");if(a != b){jQuery(wp2p_color_field[indice]).val(a)}if(a.length == 4||a.length == 7){pickColor(a)}});
			});
  		});
		function wp2p_change_value(obj)
		{
			if ( confirm( "<?php echo __('Confirm', 'wp2phone_conversion' ) ?> ?") ) 
			{
				document.getElementById(obj+'_hidden').value = "deleted";
				document.getElementById(obj+'_publish').style.display = "none";
				document.getElementById(obj+'_image').style.display = "none";
				return true; 
			}
				return false;
		}
		function wp2p_not_empty_form ()
		{
			if(document.getElementById('tabTitle').value == "")
			{
				alert("<?php echo __('Please fill the Tab title field', 'wp2phone_conversion' ) ?>");

				document.getElementById('tabTitle').focus();
				jQuery("#tabTitle").animate({ backgroundColor: "#E7A2A2" }, 1000).animate({ backgroundColor: "#fff" }, 6000);
				return false;
			}
			if(document.getElementById('navTitle').value == "")
			{
				alert("<?php echo __('Please fill the Navigation bar title field', 'wp2phone_conversion' ) ?>");
				document.getElementById('navTitle').focus();
				jQuery("#navTitle").animate({ backgroundColor: "#E7A2A2" }, 1000).animate({ backgroundColor: "#fff" }, 6000);
				return false;
			}
			var latitude = parseFloat(document.getElementById('latitude').value);
			if(document.getElementById('type4').checked && (document.getElementById('latitude').value == "" || isNaN(document.getElementById('latitude').value) || latitude > 90.0 || latitude < -90.0 ))
			{
				alert("<?php echo __('Please fill a correct data in the Latitude field', 'wp2phone_conversion' ) ?>");
				document.getElementById('latitude').focus();
				jQuery("#latitude").animate({ backgroundColor: "#E7A2A2" }, 1000).animate({ backgroundColor: "#fff" }, 6000);
				return false;
			}
			var longitude = parseFloat(document.getElementById('longitude').value);
			if(document.getElementById('type4').checked && (document.getElementById('longitude').value == "" || isNaN(document.getElementById('longitude').value) || longitude > 180.0 || longitude < -180.0))
			{
				alert("<?php echo __('Please fill a correct data in the longitude field', 'wp2phone_conversion' ) ?>");
				document.getElementById('longitude').focus();
				jQuery("#longitude").animate({ backgroundColor: "#E7A2A2" }, 1000).animate({ backgroundColor: "#fff" }, 6000);
				return false;
			}
			var http = document.getElementById('web_url').value.substr(0,7);
			var https = document.getElementById('web_url').value.substr(0,8);
			if(document.getElementById('type5').checked && (http != "http://"))
			{
				if(document.getElementById('type5').checked && (https != "https://"))
				{
					alert("<?php echo __('The URL field should start with \"http://\" or \"https://\"', 'wp2phone_conversion' ) ?>.");
					document.getElementById('web_url').focus();
					jQuery("#web_url").animate({ backgroundColor: "#E7A2A2" }, 1000).animate({ backgroundColor: "#fff" }, 6000);
					return false;
				}
			}
			return true;
		}
	</script>
	<?php
}

/****************************************************************************************************/
/*											CONTENT SETTINGS										*/
/****************************************************************************************************/

function wp2p_content_page()
{
	if (!current_user_can('manage_options'))
	{
	 	wp_die( __('You are not allowed to manage this plugin.','wp2phone_conversion') );
	}
	echo '<div class="wrap">';
	global $wpdb;
	$opt_name = 'wp2p_tab';
	$hidden_field_name = 'wp2p_submit_hidden';
	$hidden_field_name2 = 'wp2p_submit_hidden_action';
	if (isset ($_GET) and ($_GET['action'] == 'add' or $_GET['action'] == 'edit'))
	{
		wp2p_add_edit_tab($_GET['action']);
	}
	else
	{
		if (isset($_GET) and $_GET['action'] == 'supp' and !isset($_POST[ $hidden_field_name ]))
		{
			$result = -1;
			wp2p_settings_stat('edited');
			if($tab_table = get_option( 'wp2p_tab'))
			{
				$key = $_GET['tab'];
				if($tab_table != null and $tab_table != "")
				{
					foreach($tab_table as $pos=>$value)
					{
						if($value['tab-number'] == (int)$key)
						{
							$result = $pos;
							break;
						}
					}
					if($result >= 0)
					{
						if(isset($tab_table[(int)$result]['nav-image']) and $tab_table[(int)$result]['nav-image'] != "" and $tab_table[(int)$result]['nav-image'] != null)
						{
							$nav_filename = WP2PHONE_UPLOAD_FOLDER_PATH.$tab_table[(int)$result]['nav-image'];
							if (file_exists($nav_filename))
							{	
								unlink($nav_filename);
							}
						}
						if(isset($tab_table[(int)$result]['header-image']) and $tab_table[(int)$result]['header-image'] != "" and $tab_table[(int)$result]['header-image'] != null)
						{
							$header_filename = WP2PHONE_UPLOAD_FOLDER_PATH.$tab_table[(int)$result]['header-image'];
							if (file_exists($header_filename))
							{	
								unlink($header_filename);
							}
						}
						array_splice($tab_table,(int)$result,1);
						update_option('wp2p_tab', $tab_table);
						$_GET = array();
					}
				}
			}
		}
		$message = "";
		if( isset($_POST[ $hidden_field_name ]) and $_POST[ $hidden_field_name ] != "")
		{
			$tab = "";
			$tab['nav-color'] = substr($_POST['nav-color'],0,7);
			$tab['back-color'] = substr($_POST['back-color'],0,7);
			$tab['text-color'] = substr($_POST['text-color'],0,7);
			$tab['select-color'] = substr($_POST['select-color'],0,7);
			$tab['cell-color'] = substr($_POST['cell-color'],0,7);
			$_SESSION['wp2p-colors'] = $tab;
			
			wp2p_settings_stat('edited');
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
								$tab[$key] = $msg['file-name'];
								$tab_file_name[$key] = 1;
							}
							else $tab_file_name[$key] = 0;
						}
						if($message != '')
						{
							$message .= '<br/>';
						}
						$message .= $msg['msg'];
					}
					else
					{
						if($key == 'nav-image' )
						{
							$error = __('Navigation bar image : ', 'wp2phone_conversion' ).$error;
						}
						elseif($key == 'header-image' )
						{
							$error = __('Table header image : ', 'wp2phone_conversion' ).$error;
						}
						echo '<div class="updated" style="border-color: #c00;background-color: #ffebe8"><p><strong>'. $error.'</strong></p></div>';
					}
				}
			}
			
			$tab['tab-title'] = stripcslashes($_POST['tab-title']);
			$tab['header-link'] = stripcslashes($_POST['header-link']); 
			$tab['nav-title'] = stripcslashes($_POST['nav-title']);
			$tab['icon-name'] = $_POST['icon-name'];
			$tab['type'] = $_POST['type'];
			
			if($_POST['type'] == "map")
			{
				$tab['latitude'] = $_POST['latitude'];
				$tab['longitude'] = $_POST['longitude'];
			}
			if($_POST['type'] == "web")
			{
				$tab['url'] = $_POST['url'];
			}
			
			if($_POST['type'] == "category" || $_POST['type'] == "tag")
			{
				if(isset($_POST['show-image-header'])) $tab['show-image-header'] = (int)$_POST['show-image-header'];
				else $tab['show-image-header'] = 0;
				if(isset($_POST['show-image'])) $tab['show-image'] = (int)$_POST['show-image'];
				else $tab['show-image'] = 0;
				if(isset($_POST['show-date'])) $tab['show-date'] = (int)$_POST['show-date'];
				else $tab['show-date'] = 0;
			}
			
			if(isset($_POST['show-image-post'])) $tab['show-image-post'] = (int)$_POST['show-image-post'];
			else $tab['show-image-post'] = 0;
			if(isset($_POST['show-comments'])) $tab['show-comments'] = (int)$_POST['show-comments'];
			else $tab['show-comments'] = 0;
			if(isset($_POST['show-web-version'])) $tab['show-web'] = (int)$_POST['show-web-version'];
			else $tab['show-web'] = 0;
			if(isset($_POST['show-date-post'])) $tab['show-date-post'] = (int)$_POST['show-date-post'];
			else $tab['show-date-post'] = 0;
			if(isset($_POST['show-title-post'])) $tab['show-title-post'] = (int)$_POST['show-title-post'];
			else $tab['show-title-post'] = 0;
			if(isset($_POST['show-share'])) $tab['show-share'] = (int)$_POST['show-share'];
			else $tab['show-share'] = 0;
			
			if($tab['type'] == "category") $tab['id'] = (int)$_POST['selector1'];
			if($tab['type'] == "tag") $tab['id'] = (int)$_POST['selector2'];
			if($tab['type'] == "page") $tab['id'] = (int)$_POST['selector0'];
			$tab_table = get_option( 'wp2p_tab');
			$max = -1;
			if ($tab_table != null and $tab_table != "")
			{
				foreach ($tab_table as $key=>$resline)
				{
					if ($max <= $resline['tab-number'])
					{
						$max = $resline['tab-number'];
					}
				}
				$compteur = count($tab_table);
			}
			else
			{
				$max = 0;
				$compteur = 0;
			}
			if ($_POST[ $hidden_field_name ] == 'add')
			{
				$tab['tab-number'] = $max+1;
				$tab_table[$compteur] = $tab;
				update_option( $opt_name, $tab_table);
			}
			elseif ($_POST[ $hidden_field_name ] == 'edit' and isset($_POST[ $hidden_field_name2 ]))
			{
				$key=$_POST[ $hidden_field_name2 ];
				foreach($tab_table as $pos=>$value)
				{
					if($value['tab-number'] == (int)$key)
					{
						$result = $pos;
						break;
					}
				}
				foreach($_FILES as $key2=>$value)
				{
					if( $tab_file_name[$key2] == 0 and isset($tab_table[(int)$result][$key2]))
					{
						if(isset($_POST[$key2.'image']) and $_POST[$key2.'image'] != "deleted")
						{
							$tab[$key2] = $tab_table[(int)$result][$key2];
						}
						else 
						{
							$filename_to_delete = WP2PHONE_UPLOAD_FOLDER_PATH.$tab_table[(int)$result][$key2];
							if (file_exists($filename_to_delete))
							{
								unlink($filename_to_delete);
							}
						}
					}
					else
					{
						if (isset($tab_table[(int)$result][$key2]) and $tab_table[(int)$result][$key2] != "" and $tab_table[(int)$result][$key2] != null)
						{
							$filename = WP2PHONE_UPLOAD_FOLDER_PATH.$tab_table[(int)$result][$key2];
							if (file_exists($filename))
							{	
								unlink($filename);
							}
						}
					}
				}
				$tab['tab-number'] = intval($key);
				$tab_table[(int)$result] = $tab;
				update_option( $opt_name, $tab_table);
			}
			if($message != '')
			{
				echo '<div class="updated" style="border-color: #c00;background-color: #ffebe8"><p><strong>'. $message.'</strong></p></div>';
			}
			?>
			<div class="updated"><p><strong><?php echo __('Settings saved.', 'wp2phone_conversion' ); ?></strong></p></div>
			<?php
			}
			if( isset($_GET) and $_GET['action'] == "publish")
			{
				$tab_table = get_option('wp2p_tab');
				$settings_table = get_option('wp2p_settings');
				update_option('wp2p_tab_saved', $tab_table);
				update_option('wp2p_settings_saved', $settings_table);
				wp2p_settings_stat('save');
			}
			if($statut=get_option( 'wp2p_published'))
			{
				if($statut == 'true') $published = "none";
			}
			if(get_bloginfo('version') >= '3.2') $class_button = ''; else $class_button = 'button ';
 			?>
			<div  class = "updated" style = "display:<?php echo $published ?>" id = "show_publish">
			<p>
			<strong>
			<?php echo  __('You made some changes, click Publish to make them available on your app.', 'wp2phone_conversion' ); ?>
			<span style="float:right; margin-right:0px"><a class="button-primary" href="?page=wp2p-content&action=publish" id="publish" title="<?php echo __('This button will activate changes on devices', 'wp2phone_conversion' ); ?>." style=" color:#FFFFFF"><?php echo __( 'Publish', 'wp2phone_conversion') ?> </a></span>
			</strong>
			</p>
			</div>
			<div id="icon-themes" class="icon32"><br /></div>
			<h2><?php echo __('Content Settings', 'wp2phone_conversion' ); ?> <a id="wp2p_to_discribe_0" href="?page=wp2p-content&action=add" class="<?php echo $class_button; ?>add-new-h2" title="<?php echo __('Add a new tab bar item', 'wp2phone_conversion' ); ?>."> &nbsp;&nbsp;<?php echo __('Add', 'wp2phone_conversion' )." "; ?>&nbsp;&nbsp;</a> </h2>
				<table class="widefat" id="sort" style="width:100%; margin-top:20px;">
					<thead>
						<tr>
							<th><img src="<?php bloginfo('url'); ?>/wp-admin/images/loading.gif" id="loading-animation" style="display:none" width="30px" /></th>
							<th></th>
							<th><?php echo  __('Title', 'wp2phone_conversion' ); ?></th>
							<th><?php echo __('Content', 'wp2phone_conversion' ); ?></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th></th>
							<th></th>
							<th><?php echo __('Title', 'wp2phone_conversion' ); ?></th>
							<th><?php echo __('Content', 'wp2phone_conversion' ); ?></th>
						</tr>
					</tfoot>
				<tbody>
				<?php
					$cmp = 0;
					$tab_table = get_option( 'wp2p_tab');
					if ($tab_table != null and $tab_table != "")
					{
				?>
					<?php
						foreach ($tab_table as $key=>$resline) 
						{
							?>
						  <tr  id="listItem_<?php echo $resline['tab-number'];?>" valign="top"  style="width:90%" > 
							  <td style="width:1%; align:center"><img id="wp2p_to_discribe_1" src="<?php echo WP2PHONE_PLUGIN_URL."/images/"; ?>sort.png" alt="move" width="25" height="19" class="handle changeCursor" title="<?php echo __('Drag it to re-order tab bar items', 'wp2phone_conversion' ); ?>."/></td>
							  <td style="width:10% ;align:center ; text-align:center;" class="handle changeCursor"><img src="<?php echo WP2PHONE_PLUGIN_URL.'/images/tab/glyphish-icons/'.$resline['icon-name'] ; ?>" id="wp2p_to_discribe_3" alt="move" title="<?php echo __('Drag it to re-order tab bar items', 'wp2phone_conversion' ); ?>." /></td>
							 <td class="handle" style="width:30%">
								<strong><a  href='?page=wp2p-content&action=edit&tab=<?php echo $resline['tab-number']; ?>' title=''><?php echo $resline['tab-title'];?></a></strong>
								<div class="row-actions">
									<span class='edit'><a href="?page=wp2p-content&action=edit&tab=<?php echo $resline['tab-number']; ?>"><?php echo  __('Edit', 'wp2phone_conversion' ); ?></a> |
									</span>
									<span class='delete'><a class='submitdelete' href="?page=wp2p-content&action=supp&tab=<?php echo $resline['tab-number']; ?>" onclick="if ( confirm( '<?php echo __('Click OK to remove this tab', 'wp2phone_conversion' ); ?>' ) ) { return true;}return false;"><?php echo  __('Delete', 'wp2phone_conversion' ); ?> </a>
									</span>
								</div>
							</td>
							<!--<td class="handle" style="width:30%">
								<strong><a  href='?page=wp2p-content&action=edit&tab=<?php echo $resline['tab-number']; ?>' title=''><?php echo $resline['nav-title'];?></a></strong>
							</td>-->
							<td class="handle" style="width:58%" >
								<?php
								if ($resline['type'] == "page")
								{
									$title = 'Page';
									$subtitle = get_page($resline['id'])->post_title;
								}
								elseif ($resline['type'] == "map")
								{
									$title = 'Map';
									$subtitle = $resline['latitude'].', '.$resline['longitude'];
								}
								elseif ($resline['type'] == "web")
								{
									$title = 'Web';
									$subtitle = '<a href="'.$resline['url'].'" target="_blank">'.$resline['url'].'</a>';
								}
								elseif ($resline['type'] == "category")
								{
									$title = 'Category';
									$subtitle = get_cat_name($resline['id']); 
								}
								elseif ($resline['type'] == "tag")
								{
									$title = 'Tag';
									$subtitle = wp2p_get_tag_name($resline['id']);
								}
								else
								{
									$title = ucfirst($resline['type']);
									$subsitle = '';
								}
								echo '<span style="font-weight:bold">'.__($title, 'wp2phone_conversion').'</span>';
								if (!empty($subtitle)) echo ' &gt; '.$subtitle;
								?>
							</td>
						 </tr>	 
				<?php 
					}
				} 
				else echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>".__('Click the Add button to define the content of your mobile app', 'wp2phone_conversion' )."</td><td>&nbsp;</td></tr>";
				?>
				</tbody>
			</table>
		<div id="wp2p_description" class="wp2p_description"></div>
	</div>
	<?php
	} 
}

function wp2p_action_callback()
{
    $order = explode(',', $_POST['order']);
    $counter = 0;
 	$tab_table = get_option( 'wp2p_tab');
 	$tab2 = $tab_table;
    foreach ($order as $position=>$item_id)
    {
    	$result = -1;
		$rest = substr($item_id, strlen("listItem_")-strlen($item_id));
		foreach ($tab2 as $pos=>$value)
		{
			if ($value['tab-number'] == (int)$rest)
			{
				$result = $pos;
				break;
			}
		}
		if ($result != -1) $tab_table[(int)$position] = $tab2[$result];
    }    
    update_option( 'wp2p_tab', $tab_table);
	update_option( 'wp2p_published','false');
    die(1);
}

?>