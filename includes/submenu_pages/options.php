<?php
 /*
  * Create a submenu page in the administration menu for the settings
  * 
  * @author Alexandre Levacher
  * @package badges4languages-plugin
  * @subpackage includes/submenu_pages
  * @since 1.1.3
 */


/**
 * Adds b4l_badges_issuer_information_submenu_page during to the admin menu.
 */
add_action('admin_menu', 'b4l_settings_submenu_page');
 
/**
 * Creates the submenu page.
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
 */
function b4l_settings_submenu_page() {
    add_submenu_page(
        'edit.php?post_type=badge',
        'Badge Settings',
        'Badge Settings',
        'edit_posts',
        'badge_options',
        'b4l_settings_page_callback' 
    );
}


function b4l_settings_page_callback() {
    ?>
<div class="wrap">

	<h2><?php _e("Badges4Languages Plugin Settings", 'badge'); ?></h2>

	<div class="postbox-container" style="width:73%;margin-right:2%;">	
	
	<form name="post" action="" method="post" id="post">
	<div>
		<div class="postarea">
			
                    <div class="postbox">
                            <h3 class="hndle">&nbsp;<span><?php _e('Database Option', 'badge') ?></span></h3>
                            <div class="inside" style="padding:8px">
                            <?php 
                                    $check = get_option('badge_delete_db');
                            ?>
                            <label>&nbsp;<input type='checkbox' value="1" name='delete_db' <?php if($delete_db) echo 'checked'?> onclick="this.checked ? jQuery('#deleteDBConfirm').show() : jQuery('#deleteDBConfirm').hide();" />&nbsp;<?php _e('Delete stored Watu data when deinstalling the plugin.', 'badge')?> </label>

                                    <span id="deleteDBConfirm" style="display: <?php echo empty($delete_db) ? 'none' : 'inline';?>">
                                            <?php _e('Please confirm by typing "yes" in the box:', 'badge')?> <input type="text" name="delete_db_confirm" value="<?php echo get_option('badge_delete_db_confirm')?>">		
                                    </span>
                            </div>
                    </div>

                    <p class="submit">
                    <input type="hidden" id="user-id" name="user_ID" value="<?php echo (int) $user_ID ?>" />
                    <span id="autosave"></span>
                    <input type="submit" name="submit" value="<?php _e('Save Options', 'badge') ?>"  class="button-primary" />
                    </p>
	
                </div>
        </div>
        <?php wp_nonce_field('badge_options'); ?>
	</form>
	
	</div>
</div>	
<?php
}




global $wpdb;
if(!empty($_REQUEST['submit']) and check_admin_referer('badge_options')) {
	$delete_db = empty($_POST['delete_db']) ? 0 : 1;
	$delete_db_confirm = (empty($_POST['delete_db_confirm']) or $_POST['delete_db_confirm']!= 'yes') ? '' : 'yes';
        
	update_option( "badge_delete_db", $delete_db );
	update_option('badge_delete_db_confirm', $delete_db_confirm);
       
	print '<div id="message" class="updated fade"><p>' . __('Options updated', 'badge') . '</p></div>';	
}

$delete_db = get_option('badge_delete_db');
?>