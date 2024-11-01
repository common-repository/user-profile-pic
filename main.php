<?php

/*
Plugin Name: User Profile Pic
Plugin URI: http://fodboldspilleren.dk
Description: A plugin to add new field for user profile pic.
Tags: profile pic, user pic
Version: 1.0.0
Author: Kjeld Hansen
Author URI: #
Requires at least: 4.0
Tested up to: 4.8
Text Domain: user-profile-pic
*/

if ( ! defined( 'ABSPATH' ) ) exit; 

///////////////////////////////////////////////////////////////////////////////////

/**
 * Adds additional user fields
 * more info: http://justintadlock.com/archives/2009/09/10/adding-and-using-custom-user-profile-fields
 */
 
function upp_additional_user_fields( $user ) {
	
	?>
 
    <h3><?php _e( 'Additional User Meta', 'textdomain' ); ?></h3>
 
    <table class="form-table">
 
        <tr>
            <th><label for="upp_user_meta_image"><?php _e( 'A special image for each user', 'textdomain' ); ?></label></th>
            <td>
                <!-- Outputs the image after save -->
                <img id="user_image_1" src="<?php echo esc_url( get_the_author_meta( 'upp_user_meta_image', $user->ID ) ); ?>" style="width:150px;"><br />
                <!-- Outputs the text field and displays the URL of the image retrieved by the media uploader -->
                <input type="text" name="upp_user_meta_image" id="upp_user_meta_image" value="<?php echo esc_url_raw( get_the_author_meta( 'upp_user_meta_image', $user->ID ) ); ?>" class="regular-text" />
                <!-- Outputs the save button -->
                <input type='button' id="upload_image_button" class="additional-user-image button-primary" value="<?php _e( 'Upload Image', 'textdomain' ); ?>" id="uploadimage"/><br />
                <span class="description"><?php _e( 'Upload an additional image for your user profile.', 'textdomain' ); ?></span>
            </td>
        </tr>
 
    </table><!-- end form-table -->
<?php   } // additional_user_fields
 
add_action( 'show_user_profile', 'upp_additional_user_fields' );
add_action( 'edit_user_profile', 'upp_additional_user_fields' );

add_action( 'admin_footer', 'upp_wpdocs_scripts_method_add_js' );
//add_action( 'wp_enqueue_scripts', 'wpdocs_scripts_method_add_js' );

/**
* Saves additional user fields to the database
*/
function upp_save_additional_user_meta( $user_id ) {
 
    // only saves if the current user can edit user profiles
    if ( !current_user_can( 'edit_user', $user_id ) )
        return false;
 
    update_usermeta( $user_id, 'upp_user_meta_image', $_POST['upp_user_meta_image'] );
}
 
add_action( 'personal_options_update', 'upp_save_additional_user_meta' );
add_action( 'edit_user_profile_update', 'upp_save_additional_user_meta' );


function upp_wpdocs_scripts_method_add_js(){
	//wp_enqueue_media();
	?>
    <script type="text/javascript">
    	jQuery(document).ready( function( $ ) {
			jQuery('#upload_image_button').click(function() {
	
				window.send_to_editor = function(html) {
					imgurl = jQuery(html).attr('src')
					jQuery('#upp_user_meta_image').val(imgurl);
					jQuery('#user_image_1').attr("src",imgurl);
					tb_remove();
				}
	
				formfield = jQuery('#upp_user_meta_image').attr('name');
				tb_show( '', 'media-upload.php?type=image&amp;TB_iframe=true' );
				return false;
			});
	
		});
    </script>
    <?php	
}

add_shortcode('upp_user_pic', 'upp_dup_fn');
function upp_dup_fn($args){
	$userID = 0; $ret = 'Something Wrong!';
	if(isset($args[uder_id])){
		$userID = $args[uder_id];
	}else{
		if(get_current_user_id()){
			$userID = get_current_user_id();
		}
	}
	if($userID!=0){
		$ret = esc_url( get_the_author_meta( 'upp_user_meta_image', $userID ) );
	}
	
	return $ret;
}




