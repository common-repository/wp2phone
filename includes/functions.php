<?php 

/************************************************************************************************/

//	wp2phone

//	function.php

//	http://wp2phone.com

/************************************************************************************************/

define('wp2p_nav_color',		'#000000');
define('wp2p_back_color',		'#424242');
define('wp2p_text_color',		'#444444');
define('wp2p_select_color',		'#999999');
define('wp2p_cell_color',		'#cccccc');
define('wp2p_icon_name',		'53-house.png');

define('wp2p_check_box_stat',	'checked');

/************************************************************************************************/
/*										ICONS SELECTOR			   		  						*/
/************************************************************************************************/

function wp2p_icon_reader()
{
	$dir = WP2PHONE_PLUGIN_PATH.'/images/tab/glyphish-icons/';
	$image_largeur = 38;
	$valide_extensions = array('jpg', 'jpeg', 'gif', 'png');	
	if( $Ressource = opendir($dir))
	{
		echo '<div id="image_container" style="display:none; background-color: #FFFFFF; float:left; width:'.(17*$image_largeur).'px; position:absolute; border:4px double; margin-top:20px;  z-index:1;">';
		while($fichier = readdir($Ressource))
		{
    		$berk = array('.', '..');
			$test_Fichier = WP2PHONE_PLUGIN_URL.'/images/tab/glyphish-icons/'.$fichier;
 	  		if(!in_array($fichier, $berk) && !is_dir($test_Fichier))
    		{
       			$ext = pathinfo($fichier,  PATHINFO_EXTENSION);
	      		if(in_array($ext, $valide_extensions))
    	   		 {
        	   		 echo '<div id="liste" style="float:left; margin:4px 6px 4px 4px; padding:0 0 0 0;border:1px double;">
                    <img src="'.$test_Fichier.'"  width="20px" height="20px" col="'.$fichier.'" />
                	</div>';
                }
    		}
		}
		echo '</div><br/>';
	}
	else echo '<div style="float:left;">'.__('foldre access problem', 'wp2phone_conversion' ).'</div>';
}

/************************************************************************************************/
/*											IMAGE UPLOAD										*/
/************************************************************************************************/

function wp2p_file_upload_area($label_name, $name, $image_name, $min_width, $min_height, $max_width, $max_height)
{
	?>
	<span style="float:left; width: 170px;">
	<label for="<?php echo $label_name ?>"><?php echo  $label_name ?> :</span>
	<input type="file" name="<?php echo $name ?>" id="<?php echo $name ?>"/></label>
	<input type="hidden" name="MAX_FILE_SIZE" value="307200" />
	<input type="hidden" name="<?php echo $name.'min_width'; ?>" value="<?php echo $min_width ?>" />
	<input type="hidden" name="<?php echo $name.'min_height'; ?>" value="<?php echo $min_height ?>" />
	<input type="hidden" name="<?php echo $name.'max_width'; ?>" value="<?php echo $max_width ?>" />
	<input type="hidden" name="<?php echo $name.'max_height'; ?>" value="<?php echo $max_height ?>" />
	<input type="hidden" name="<?php echo $name.'image'; ?>" id="<?php echo $name.'_hidden'; ?>" value="<?php echo $name.'image'; ?>" />
	<?php
	if($image_name != "")
	{
		if ($max_height > 44)
			$img_max_width = 100;
		else
			$img_max_width = 190;
		if(file_exists(WP2PHONE_UPLOAD_FOLDER_PATH.$image_name))
		{
			echo '<div style="max-width:'.$img_max_width.'px; max-height:90px ;float:right ;" ><a href="'.WP2PHONE_UPLOAD_FOLDER_URL.$image_name.'" target="_blank"><img id="'.$name.'_image" src="'.WP2PHONE_UPLOAD_FOLDER_URL.$image_name.'" style="width:100%;" /></a></div>';
			echo '<div style="float:right;margin-right:5px;" id="delete-action"><a class="submitdelete deletion" href="#" id="'.$name.'_publish" onclick="wp2p_change_value(\''.$name.'\');">'.__('Remove image', "wp2phone_conversion" ).'</a></div>';
		}
	}
	echo "<br/>";
	if ($min_height == $max_height and $min_width == $max_width )
	{
		echo __("requiered width ", 'wp2phone_conversion')." = $max_width <br/>";
		echo __("requiered height", 'wp2phone_conversion'). " = $max_height";
	}
	elseif ($min_width == 0 and $min_width == 0 )
	{
		echo __("max width ", 'wp2phone_conversion')." = $max_width <br/>";
		echo __("max height", 'wp2phone_conversion'). " = $max_height";
	}
	else
	{
		echo __("max width ", 'wp2phone_conversion')." = $max_width <br/>"; 
		echo __("max height", 'wp2phone_conversion'). " = $max_height <br/>";
		echo __("min width ", 'wp2phone_conversion')." = $min_width <br/>"; 
		echo __("min height", 'wp2phone_conversion'). " = $min_height";
	}
}

/************************************************************************************************/
/*											RESIZE IMAGES			   		  					*/
/************************************************************************************************/

function wp2p_image_resizer($fichier, $nouvelle_taille) 
{
    global $error;
    $longueur = $nouvelle_taille;
    $largeur = $nouvelle_taille;
    $taille = getimagesize($fichier);
    if ($taille) 
    {
        if ($taille['mime'] == 'image/jpeg' ) 
        {
            $img_big = imagecreatefromjpeg($fichier); 
            $img_new = imagecreate($longueur, $largeur);    
            $img_petite = imagecreatetruecolor($longueur, $largeur) or $img_petite = imagecreate($longueur, $largeur);
            imagecopyresized($img_petite,$img_big,0,0,0,0,$longueur,$largeur,$taille[0],$taille[1]);
            imagejpeg($img_petite,$fichier);
        }
        else if ($taille['mime'] == 'image/png' ) 
        {
            $img_big = imagecreatefrompng($fichier);
            $img_new = imagecreate($longueur, $largeur);    
            $img_petite = imagecreatetruecolor($longueur, $largeur) OR $img_petite = imagecreate($longueur, $largeur);
            imagecopyresized($img_petite,$img_big,0,0,0,0,$longueur,$largeur,$taille[0],$taille[1]);
            imagepng($img_petite,$fichier);
        }
    }
}

/************************************************************************************************/
/*										IMAGE UPLOAD VALIDATION			  	  					*/
/************************************************************************************************/

function wp2p_image_upload($champ,$filename,$max_taille,$min_width,$min_height,$max_width,$max_height)
{
	$name = $_FILES[$champ]['name']    ; 
	$type = $_FILES[$champ]['type']   ;  
	$size = $_FILES[$champ]['size']  ;   
	$tmp_name = $_FILES[$champ]['tmp_name'] ;
	$error = $_FILES[$champ]['error'] ;
	$message = '';
	$status = true;
	
	if ($_FILES[$champ]['error'] > 0)  
	{
		switch ($_FILES[$champ]['error'])
		{    
		   case 1: // UPLOAD_ERR_INI_SIZE    
				$message .= __('The file exceeds the limit allowed by the server.', 'wp2phone_conversion' );    
			break;   
			case 2: // UPLOAD_ERR_FORM_SIZE    
				$message .= __('The file exceeds the limit allowed in the HTML form.', 'wp2phone_conversion');
			break;    
			case 3: // UPLOAD_ERR_PARTIAL    
				 $message .= __('Sending the file has been interrupted during transfer.', 'wp2phone_conversion');    
		  	break;    
		}
		$status = false;
	}
	else
	{
		$extensions_valides = array( 'jpg', 'jpeg' , 'png', 'gif');
		$extension_upload = strtolower(  substr(  strrchr($_FILES[$champ]['name'], '.')  ,1)  );
		if ( !in_array($extension_upload,$extensions_valides) ) 
		{
			$message .= __('The image extension should be PNG, JPG or GIF.', 'wp2phone_conversion' ); 
			 $status = false;
		}
		else
		{
			if(filesize($_FILES[$champ]['tmp_name']) > $max_taille)
       		{
				$message .= sprintf(__("Your file must be less than %s Ko.", 'wp2phone_conversion' ), $max_taille);
				$status = false;
      		}
       		else 
      		{
      			if( preg_match('#[\x00-\x1F\x7F-\x9F/\\\\]#', $name_file) )
				{
   					 $message .= __('Invalid file name.', 'wp2phone_conversion' );
   					 $status = false;	
				}
				else
				{
					$size = getimagesize($_FILES[$champ]['tmp_name']);
					if($size[0] > $max_width or  $size[1] > $max_height)
					{
						$message .= __("Max width or height exceeded.", 'wp2phone_conversion');
						$status = false;
					}
					elseif($size[0] < $min_width or  $size[1] < $min_height)
					{
						$message .= __("Image dimensions are not inside the required limits.", 'wp2phone_conversion');
						$status = false;
					}
					else 
					{
						//$myimage=$_FILES[$champ]['tmp_name'];
						//wp2p_image_resizer($_FILES[$champ]['tmp_name'],$size[1]);
						$time = time();
						$time .= $champ;
						$nom = "$filename/$time.{$extension_upload}";
						$resultat = move_uploaded_file($_FILES[$champ]['tmp_name'], $nom);
						if ($resultat) 
						{	
							$result['file-name'] = $time.'.'.$extension_upload;
							$result['width'] = $size[0];
							$result['height'] = $size[1];
							$status = true;
						}
						else 
						{
							$message .= __('Server error during image upload.', 'wp2phone_conversion' );
						}
					}	
				}				
			}
		}
	}
	$result['error'] = $_FILES[$champ]['error'];
	if($message != '' )
	{
		if($champ == 'nav-image' )
		{
			$message = __('Navigation bar image : ', 'wp2phone_conversion' ).$message;	
		}
		if($champ == 'header-image' )
		{
			$message = __('Table header image : ', 'wp2phone_conversion' ).$message;	
		}
		if($champ == 'ad-image' )
		{
			$message = __('Ad URL : ', 'wp2phone_conversion' ).$message;	
		}
	}
	$result['msg'] = $message;
	$result['status'] = $status;
	return $result;
}

/************************************************************************************************/
/*										FILE & FOLDER PERMISSIONS	  							*/
/************************************************************************************************/

function wp2p_file_permission_test($folderName)
{
	$time = time();
	if($fp = fopen($folderName.$time.".txt","a+"))
	{
		fputs($fp, "$time");
		fclose($fp);
		unlink($folderName."$time.txt");
		return true	;
	}
	else
	{
		return false;
	}
}

function wp2p_is_folder_exist($filename)
{
	if(file_exists($filename)){
		return true;
	}
	else return false;
}

function wp2p_create_folder($filename)
{
	if(!wp2p_is_folder_exist($filename))
	{
		if(mkdir($filename))
		{
			return true;
		}
		else return false;
	}
}

function wp2p_test_folder_permissions($filename)
{
	if(is_writable($filename))
	{
		return true;
	}
	else
	{
		return false;
	}
}

function wp2p_check_plugin_installation_error()
{
	$wp2phone_folder = ABSPATH.'wp-content/uploads/wp2phone';
	$uploads_folder = ABSPATH.'wp-content/uploads';
	$message = '';
	if(wp2p_is_folder_exist($uploads_folder))
	{
		if(wp2p_is_folder_exist($wp2phone_folder))
		{
			if(wp2p_test_folder_permissions($wp2phone_folder))
			{
				return false;
			}
			else
			{
				return $message .= __( 'The folder "/wp-contents/uploads/wp2phone" should be writable.', 'wp2phone_conversion' );
			}
		}
		else
		{
			if(wp2p_create_folder($wp2phone_folder))
			{
				return false;
			}
			else
			{
				return $message .= __( 'The folder "/wp-contents/uploads" should be writable.', 'wp2phone_conversion' );
			}
		}
	}
	else
	{
		if(wp2p_create_folder($uploads_folder))
		{
			if(wp2p_create_folder($wp2phone_folder))
			{
				return false;
			}
			else
			{
				return $message .= __( 'The folder "/wp-contents/uploads" should be writable.', 'wp2phone_conversion' );
			}
		}
		else
		{
			return $message .= __( 'The folder "/wp-contents" should be writable.', 'wp2phone_conversion' );
		}
	}
}

/************************************************************************************************/
/*											PUBLISH			   				  					*/
/************************************************************************************************/

function wp2p_settings_stat($str)
{
	if ($str == "save")
	{
		update_option( 'wp2p_published','true');
	}
	elseif ($str == "edited")
	{
		update_option( 'wp2p_published','false');
	}
}

/************************************************************************************************/
/*											PUSH			   				  					*/
/************************************************************************************************/

function wp2p_send_push_for_post($post_id)
{
	$pref = get_option('wp2p_pref');
	if ($pref and ($pref['app-token'] != '') and ($pref['push-post'] != 0) and isset($_POST['publish'] ))
	{
		if(has_tag( $pref['push-tag'], $post_id))
		{
			global $wpdb;
			$post = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID = ".$post_id." LIMIT 1");
			if ($post)
			{
				$tuCurl = curl_init();
				$url = "http://api.wp2phone.com/api/send-push-new-post.php";
				$data['app_token'] = $pref['app-token'];
				$data['message'] = $post->post_title;
				$data['action'] = "post";
				$data['param'] = "$post_id";
				
				curl_setopt($tuCurl, CURLOPT_URL, $url); 
				curl_setopt($tuCurl, CURLOPT_HEADER, 0);
				curl_setopt($tuCurl, CURLOPT_POST, 1); 
				curl_setopt($tuCurl, CURLINFO_HEADER_OUT,0);
				curl_setopt($tuCurl, CURLOPT_TIMEOUT, 40);
				curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER, 1); 
				curl_setopt($tuCurl, CURLOPT_POSTFIELDS, $data);
				$tuData = curl_exec($tuCurl);
				curl_close($tuCurl);
			}
		}
	}
}

/************************************************************************************************/
/*										WP2P-TOKEN			   				  					*/
/************************************************************************************************/

function wp2p_get_token()
{
	$curl = curl_init();
	$data['wp_url'] = site_url();
	$data['wp_version'] = get_bloginfo('version');
	$data['wp_language'] = get_bloginfo('language');
	$data['wp_email'] = get_bloginfo('admin_email');
	$data['wp2p_version'] = WP2PHONE_VERSION;
	curl_setopt($curl, CURLOPT_URL, 'http://api.wp2phone.com/api/request-app-token.php'); 
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_POST, 1); 
	curl_setopt($curl, CURLINFO_HEADER_OUT,0);
	curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	$json = curl_exec($curl);
	curl_close($curl);
	
	if ($json != '')
	{
		$obj = json_decode($json);
		if ($obj != NULL)
		{
			if (($obj->status == 'OK') && ($obj->token != NULL))
			{
				return $obj->token;
			}
		}
	}
	
	return '';
}

/************************************************************************************************/
/*											WP TOOLS		   				  					*/
/************************************************************************************************/

function wp2p_get_tag_name( $tag_id )
{
     $tag_id = (int) $tag_id;
     $tag = get_term( $tag_id, 'post_tag' );
 
     if ( ! $tag || is_wp_error( $tag ) )
          return '';
 
     return $tag->name;
}

?>