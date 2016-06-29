<?php

/**
 * ----- badges4languages-plugin -----
 *
 * This plugin allows to a user to get a student or teacher certification by
 * himself or to receive it thanks to someone who has given him.
 *
 * @link              http://www.badges4languages.org
 * @since             1.0.0
 * @package           Badges4languages_Plugin
 *
 * @wordpress-plugin
 * Plugin Name:       Badges4languages-plugin
 * Plugin URI:        http://www.badges4languages.org
 * Description:       Gives a student or a teacher certification to someone.
 * Version:           1.2.1
 * Author:            Alexandre Levacher
 * Author URI:        http://www.badges4languages.org
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       badges4languages-plugin
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-badges4languages-plugin-activator.php
 */
function activate_badges4languages_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-badges4languages-plugin-activator.php';
	Badges4languages_Plugin_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-badges4languages-plugin-deactivator.php
 */
function deactivate_badges4languages_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-badges4languages-plugin-deactivator.php';
	Badges4languages_Plugin_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_badges4languages_plugin' );
register_deactivation_hook( __FILE__, 'deactivate_badges4languages_plugin' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-badges4languages-plugin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_badges4languages_plugin() {

	$plugin = new Badges4languages_Plugin();
	$plugin->run();

}
run_badges4languages_plugin();






/**************************************************************************
 ************************** CREATION/DECLARATION **************************
 *************************************************************************/

/**
 * Executes the 'b4l_create_db_tables' function
 * during the initialization phase.
 */
add_action('init', 'b4l_create_db_tables', 0);

/**
 * Creates the Database Tables for the Custom Post 'badge'.
 */
function b4l_create_db_tables() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/db_tables.php';
    b4l_create_db_table_b4l_languages();
    b4l_create_db_table_b4l_students();
    b4l_create_db_table_b4l_teachers();
    b4l_create_db_table_b4l_teacherLevels();
    b4l_create_db_table_b4l_studentLevels();
    b4l_create_db_table_b4l_skills();
    b4l_create_db_table_b4l_number_certifications();
    b4l_create_db_table_b4l_issuer_information();
}

/**
 * Executes the Custom Function named b4l_create_badges_register 
 * during the initialization phase.
 */
add_action('init', 'b4l_create_badges_register');

/**
 * Creates the Custom Post 'badge'
 */
function b4l_create_badges_register(){
 
        //Declaration of the labels
	$labels = array(
		'name' =>_x('Badge School', 'post type general name'),
		'singular_name' =>_x('Badge', 'post type singular name'),
		'add_new' =>_x('Add New', 'badge item'),
		'add_new_item' =>__('Add New Badge'),
		'edit_item' =>__('Edit Badge'),
		'new_item' =>__('New Badge'),
		'view_item' =>__('View Badge'),
		'search_items' =>__('Search Badge'),
		'not_found' =>__('Nothing found'),
		'not_found_in_trash' =>__('Nothing found in Trash'),
		'parent_item_colon' => ''
	);
 
        //Declaration of the arguments
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
                'show_in_nav_menus' => true,
		'query_var' => true,
		'menu_icon' => 'dashicons-welcome-learn-more',
		'rewrite' => true,
		'capability_type' => 'post',
		//Capabilities just for admin (only admin can see the custom post)
		'capabilities'=>array(
			'edit_post'=>'update_core',
			'read_post'=>'update_core',
			'delete_post'=>'update_core',
			'edit_posts'=>'update_core',
			'edit_others_posts'=>'update_core',
			'publish_posts'=>'update_core',
			'read_private_posts'=>'update_core'
		),
		
		'hierarchical' => false,
		'menu_position' => 15,
		'supports' => array('title','editor','thumbnail','page-attributes'),
		'has_archive'=>true
	  ); 
	
	//Registering the custom post type
	register_post_type( 'badge' , $args );
        
        flush_rewrite_rules();
}

/**
 * Executes b4l_create_my_taxonomies during the initialization phase.
 */
add_action( 'init', 'b4l_create_my_taxonomies', 0 );

/**
 * Creates the Custom Taxonomies (categories) for the Custom Post 'badge'.
 */
function b4l_create_my_taxonomies() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/taxonomies.php';
    b4l_create_TeacherLevels_taxonomies();
    b4l_create_StudentLevels_taxonomies();
    b4l_create_Skills_taxonomies();
    b4l_create_Tags_taxonomies();
}





/**************************************************************************
 *************** CUSTOM SUBMENUS - ADMINISTRATION PANEL *******************
 *************************************************************************/

/**
 * BADGES ISSUER INFORMATION CUSTOM SUBMENU
 * Submenu page for the admin to give information useful for the certification.
 */
require plugin_dir_path( __FILE__ ) . 'includes/badges_issuer_information.php';

/**
 * CSV FILE UPLOAD CUSTOM SUBMENU
 * This plugin is used to create the submenu 'CSV File Upload' for the
 * Custom Post 'badge'.
 */
require plugin_dir_path( __FILE__ ) . 'included_plugins/wp_csv_to_db/wp_csv_to_db.php';

/**
 * SEND BADGES TO STUDENTS CUSTOM SUBMENU
 * A teacher can send certifications by mails to a (group of) student(s) by the
 * administration panel.
 */
require plugin_dir_path( __FILE__ ) . 'includes/send_badges_students.php';





/**************************************************************************
 ***** 'ACCEPT BADGE' PAGE AFTER RECEIVING THE CERTIFICATION BY MAIL ******
 *************************************************************************/

/**
 * Create a page after receiving a certification by mail. This page is generic,
 * that is to say it will be always loaded, only the certification information
 * will change.
 */
require plugin_dir_path( __FILE__ ) . 'includes/accept_badge.php';





/**************************************************************************
 ****************************** TEMPLATES *********************************
 *************************************************************************/

/**
 * Executes b4l_include_template_function for initializing the Custom Post Template.
 */
add_filter( 'template_include', 'b4l_include_template_function', 1 );

/**
 * Adding the template for 'Single Badge' page to the list of templates.
 */
function b4l_include_template_function( $template_path ) {
    if ( get_post_type() == 'badge' ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'single-badge.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . 'templates/single-badge.php';
            }
        }
    }
    return $template_path;
}





/**************************************************************************
 ****************** ADD BADGES FIELD INTO USER PROFIL *********************
 *************************************************************************/

// !!!!!!!!!!!!!!!!!! NOT WORKING FOR THE MOMENT !!!!!!!!!!!!!!!!!!

/**
 * Executes b4l_badges_profile_fields while user's profile is visualised/edited.

add_action( 'show_user_profile', 'b4l_badges_profile_fields' );
add_action( 'edit_user_profile', 'b4l_badges_profile_fields' );

/**
 * Creates a custom field for badges into user's profile.

function b4l_badges_profile_fields( $user ) {
?>
  <h3><?php _e("You think you have the level(s) :", "blank"); ?></h3>
  <table class="form-table">
    <tr>
      <th><label for="badge"><?php _e("BADGE"); ?></label></th>
      <td>
        <!-- BADGE -->
        <input type="text" name="badge" id="phone" class="regular-text" 
            value="<?php echo esc_attr( get_the_author_meta( 'badge', $user->ID ) ); ?>" /><br />
        <span class="description"><?php _e("Please enter ........."); ?></span>
    </td>
    </tr>
  </table>
<?php
}

add_action( 'personal_options_update', 'b4l_save_badges_profile_fields' );
add_action( 'edit_user_profile_update', 'b4l_save_badges_profile_fields' );

/**
 * Saves a custom field for badges into user's profile.
 
function b4l_save_badges_profile_fields( $user_id ) {
  $saved = false;
  if ( current_user_can( 'edit_user', $user_id ) ) {
    update_user_meta( $user_id, 'badge', $_POST['badge'] );
    $saved = true;
  }
  return true;
}
*/


?>