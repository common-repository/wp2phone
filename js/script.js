/*************************************************************************************************/

//	wp2phone

//	script.js

//	http://wp2phone.com

/*************************************************************************************************/
function wp2p_change_value(obj)
{
	if ( confirm( "Confirm ?") ) 
	{
		document.getElementById(obj+'_hidden').value = "deleted";
		document.getElementById(obj+'_publish').style.display = "none";
		document.getElementById(obj+'_image').style.display = "none";
		return true; 
	}
		return false;
}

jQuery(document).ready(function(){
  /*************************************************************************/
	jQuery("#sort tbody").disableSelection();
	jQuery("#sort tbody").sortable(
	{
		handle : '.handle',
 		update : function (event, ui) 
		{  
			jQuery('#loading-animation').show();
			jQuery.ajax(
			{ 
				url: ajaxurl, 
				type: 'POST',
				async: true,
				cache: false,
				dataType: 'json',
				data:
				{
					action: 'wp2p_action', 
					order: jQuery('#sort tbody').sortable('toArray').toString() 
				},   
				success: function(response) 
				{	jQuery('#show_publish').show(); 
					jQuery('#loading-animation').hide();
					jQuery('#publish_message').hide(); 
					return; 
				},
				error: function(xhr,textStatus,event) 
				{  
					alert("<?php echo __('There was an error saving the updates', 'wp2phone_conversion' ) ?> ?"+textStatus);
					jQuery('#loading-animation').hide();
					return; 
				}
			}); 
		}
    });
  /*************************************************************************/
    jQuery('img[id*="wp2p_to_discribe"],a[id*="wp2p_to_discribe"],.button-primary').hover
		(
  			function () 
  			{
				jQuery(".wp2p_description").css({"borderColor":"#e6db55","backgroundColor":"#ffffe0"});
				jQuery('.wp2p_description').append(jQuery('<span>'+jQuery(this).attr("title")+'</span>'));
 			 }, 
 		 	function () 
 		 	{
				jQuery(".wp2p_description").css({"borderColor":"#fff","backgroundColor":"#ffffff"});
   				jQuery('.wp2p_description').find("span:last").remove();
  			}
		);
  /*************************************************************************/
		jQuery("div#image_container img").click(function () 
		{
			jQuery("div#image_container div#imagetoselect").attr("border","0");
			jQuery(this).attr("border","4");
			jQuery("input#image_from_list").val(jQuery(this).attr("col"));
			jQuery('div#listeh').html('<img id ="id2" src="' + jQuery(this).attr("src") + '"style="margin-top:5px;" >');
			//jQuery('div#listeh').css("background-color",jQuery('#nav-color').attr("value"));
		}); 
		
		jQuery("div#image_container  img").hover(function () 
		{
			jQuery("div#image_container div#imagetoselect").attr("border","0");
			jQuery(this).attr("border","4");
			jQuery('div#listeh').html('<img id ="id1" src="' + jQuery(this).attr("src") + '"style="margin-top:5px; ">');
			//jQuery('div#listeh').css("background-color",jQuery('#nav-color').attr("value"));
		}); 
		jQuery(document).ready(function()
		{
			jQuery('div#listeh').css("background-color","none");
		});
		jQuery("#button1").click(function()
		{
			jQuery("#image_container").toggle();
			jQuery('div#listeh').html('<img src="'+jQuery("#url").attr("value")+ jQuery("#image_from_list").attr("value") + '"style="margin-top:5px; " >');
			return false;
		});
			/*jQuery('#image_container').click(function(e) {
				e.stopPropagation();
			});*/
		jQuery(document).click(function() 
		{
			jQuery('#image_container').hide();
			jQuery('div#listeh').html('<img src="'+jQuery("#url").attr("value")+ jQuery("#image_from_list").attr("value") + '"style="margin-top:5px; " >');
		});	
  /*************************************************************************/
});