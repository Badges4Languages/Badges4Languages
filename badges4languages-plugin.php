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
 * Version:           1.1.0
 * Author:            Alexandre LEVACHER
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
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
 */
function b4l_create_db_tables() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/initialisation/db_tables.php';
    b4l_create_db_table_b4l_languages();
    b4l_create_db_table_b4l_students();
    b4l_create_db_table_b4l_teachers();
    b4l_create_db_table_b4l_teacherLevels();
    b4l_create_db_table_b4l_studentLevels();
    b4l_create_db_table_b4l_skills();
    b4l_create_db_table_b4l_number_certifications();
    b4l_create_db_table_b4l_issuer_information();
    b4l_create_db_table_b4l_userBadgesProfil();
}

/**
 * Executes the Custom Function named b4l_create_badges_register 
 * during the initialization phase.
 */
add_action('init', 'b4l_create_badges_register');

/**
 * Creates the Custom Post 'badge'
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
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
			'edit_posts'=>'b4l_edit_badges',
			'edit_others_posts'=>'b4l_edit_others_badges',
			'publish_posts'=>'b4l_publish_badges',
			'read_private_posts'=>'b4l_read_private_badges',
                        'b4l_send_badges_to_students'=>'b4l_send_badges_to_students', //Displays 'send_badges_to_students' page for users who have this capability
                        'b4l_import_csv_to_db'=>'b4l_import_csv_to_db', //Displays 'import_csv_to_db' page for users who have this capability
                        'b4l_badges_issuer_information'=>'b4l_badges_issuer_information' //Displays 'badges_issuer_information' page for users who have this capability
		),
		
		'hierarchical' => false,
		'menu_position' => 15,
		'supports' => array('title','editor','thumbnail','page-attributes'),
		'has_archive'=>true
	  ); 
	
	//Registering the custom post type
	register_post_type( 'badge' , $args );
        
        //Automatic flushing of the WordPress rewrite rules
        flush_rewrite_rules();
}






/**
 * Repeatable Custom Fields in a Metabox
 * Author: Helen Hou-Sandi
 * https://gist.github.com/helen/1593065
 *
 * From a bespoke system, so currently not modular - will fix soon
 * Note that this particular metadata is saved as one multidimensional array (serialized)
 */
 
function hhs_get_sample_options() {
	$options = array (
		'Option 1' => 'option1',
		'Option 2' => 'option2',
		'Option 3' => 'option3',
		'Option 4' => 'option4',
	);
	
	return $options;
}
add_action('admin_init', 'b4l_badge_add_meta_boxes', 1);
function b4l_badge_add_meta_boxes() {
	add_meta_box( 
                'repeatable-fields', 
                'Repeatable Fields', 
                'b4l_display_badge_meta_box', 
                'badge', 
                'normal', 
                'default'
        );
}

function b4l_display_badge_meta_box() {
	global $post;
	$badge = get_post_meta($post->ID, 'badge', true);
	$options = hhs_get_sample_options();
	wp_nonce_field( 'b4l_badge_meta_box_nonce', 'b4l_badge_meta_box_nonce' );
	?>
	<script type="text/javascript">
	jQuery(document).ready(function( $ ){
		$( '#add-row' ).on('click', function() {
			var row = $( '.empty-row.screen-reader-text' ).clone(true);
			row.removeClass( 'empty-row screen-reader-text' );
			row.insertBefore( '#repeatable-fieldset-one tbody>tr:last' );
			return false;
		});
  	
		$( '.remove-row' ).on('click', function() {
			$(this).parents('tr').remove();
			return false;
		});
	});
	</script>
  
	<table id="repeatable-fieldset-one" width="100%">
	<thead>
		<tr>
			<th width="32%">Language</th>
			<th width="50%">URL</th>
			<th width="8%"></th>
		</tr>
	</thead>
	<tbody>
            
	<?php
	if ( $badge ) :
	foreach ( $badge as $field ) {
	?>
	<tr>
            <td>
                <select name="select[]">
                <?php foreach ( $options as $label => $value ) : ?>
                <option value="<?php echo $value; ?>"<?php selected( $field['select'], $value ); ?>><?php echo $label; ?></option>
                <?php endforeach; ?>
                </select>
            </td>

            <td>
                <input type="text" class="widefat" name="url[]" value="<?php if ($field['url'] != '') echo esc_attr( $field['url'] ); else echo 'http://'; ?>" />
            </td>

            <td><a class="button remove-row" href="#">Remove</a></td>
	</tr>
	<?php
	}
	else :
	// show a blank one
	?>
	<tr>
		<td><input type="text" class="widefat" name="name[]" /></td>
	
		<td>
			<select name="select[]">
			<?php foreach ( $options as $label => $value ) : ?>
			<option value="<?php echo $value; ?>"><?php echo $label; ?></option>
			<?php endforeach; ?>
			</select>
		</td>
	
		<td><input type="text" class="widefat" name="url[]" value="http://" /></td>
	
		<td><a class="button remove-row" href="#">Remove</a></td>
	</tr>
	<?php endif; ?>
	
	<!-- empty hidden one for jQuery -->
	<tr class="empty-row screen-reader-text">
		<td><input type="text" class="widefat" name="name[]" /></td>
	
		<td>
			<select name="select[]">
			<?php foreach ( $options as $label => $value ) : ?>
			<option value="<?php echo $value; ?>"><?php echo $label; ?></option>
			<?php endforeach; ?>
			</select>
		</td>
		
		<td><input type="text" class="widefat" name="url[]" value="http://" /></td>
		  
		<td><a class="button remove-row" href="#">Remove</a></td>
	</tr>
	</tbody>
	</table>
	
	<p><a id="add-row" class="button" href="#">Add another</a></p>
	<?php
}

add_action('save_post', 'b4l_badge_meta_box_save');
function b4l_badge_meta_box_save($post_id) {
	if ( ! isset( $_POST['b4l_badge_meta_box_nonce'] ) ||
	! wp_verify_nonce( $_POST['b4l_badge_meta_box_nonce'], 'b4l_badge_meta_box_nonce' ) )
		return;
	
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return;
	
	if (!current_user_can('edit_post', $post_id))
		return;
	
	$old = get_post_meta($post_id, 'badge', true);
	$new = array();
	$options = hhs_get_sample_options();
	
	$selects = $_POST['select'];
	$urls = $_POST['url'];
	
	$count = count( $selects );
	
	for ( $i = 0; $i < $count; $i++ ) {
		if ( $selects[$i] != '' ) :
			
			if ( in_array( $selects[$i], $options ) )
				$new[$i]['select'] = $selects[$i];
			else
				$new[$i]['select'] = '';
		
			if ( $urls[$i] == 'http://' )
				$new[$i]['url'] = '';
			else
				$new[$i]['url'] = stripslashes( $urls[$i] ); // and however you want to sanitize
		endif;
	}
	if ( !empty( $new ) && $new != $old )
		update_post_meta( $post_id, 'badge', $new );
	elseif ( empty($new) && $old )
		delete_post_meta( $post_id, 'badge', $old );
}









/*
function display_badge_meta_box( $badge ) {
    // Retrieve current name of the Director and Movie Rating based on review ID
    $array = array();
    $link_for_more_information = esc_html( get_post_meta( $badge->ID, 'link_for_more_information', true ) );
    ?>
    <table>
        <tr>
            <td style="width: 100%">Link for more information</td>
            <td>
            <select style="width: 100px" id="language_certification" name="badge_language_for_more_information">
                <?php
                    //Display all the languages possible stored in the ($wpdb->prefix)b4l_languages table. 
                    global $wpdb;
                    $query = "SELECT language_name FROM ".$wpdb->prefix."b4l_languages WHERE
                                language_id = 'eng' OR
                                language_id = 'spa' OR
                                language_id = 'fra' OR
                                language_id = 'cmn' OR
                                language_id = 'rus' OR
                                language_id = 'por' OR
                                language_id = 'deu' OR
                                language_id = 'ita' OR
                                language_id = 'jpn' OR
                                language_id = 'arb'
                            ";
                    $results2 = $wpdb->get_results($query, ARRAY_A);
                    foreach($results2 as $result) {
                ?>
                    <option value="<?php echo $result[language_name]; ?>">
                        <?php echo $result[language_name]; } ?>
                    <option value="<?php echo $result[language_name]; ?>">
            </select>
            </td>
            <td><input type="text" size="50" name="badge_link_for_more_information" value="<?php echo $link_for_more_information; ?>" /></td>
        </tr>
    </table>
    <?php
}


add_action( 'save_post', 'add_link_for_more_information', 10, 2 );

function add_link_for_more_information( $badge_id, $badge ) {
    // Check post type for movie reviews
    if ( $badge->post_type == 'badge' ) {
        // Store data in post meta table if present in post data
        if ( isset( $_POST['badge_language_for_more_information'] ) && isset( $_POST['badge_link_for_more_information'] ) && $_POST['badge_link_for_more_information'] != '' ) {
            update_post_meta( $badge_id, 'link_for_more_information', $array );
        }
    }
}
 * *
 */












/**
 * Executes b4l_create_my_taxonomies during the initialization phase.
 */
add_action( 'init', 'b4l_create_my_taxonomies', 0 );

/**
 * Creates the Custom Taxonomies (categories) for the Custom Post 'badge'.
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
 */
function b4l_create_my_taxonomies() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/initialisation/taxonomies.php';
    b4l_create_TeacherLevels_taxonomies();
    b4l_create_StudentLevels_taxonomies();
    b4l_create_Skills_taxonomies();
    b4l_create_Badges_Categories_taxonomies();
}

/**
 * Executes b4l_create_roles_and_capabilities during the initialization phase.
 */
add_action( 'init', 'b4l_create_roles_and_capabilities', 0 );

/**
 * Creates the roles 'Teacher', 'Academy' and 'Students' and give them some 
 * capabilities like submenu visible or not, etc.
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.1
 */
function b4l_create_roles_and_capabilities() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/initialisation/users_roles_and_capabilities.php';
    b4l_add_roles();
    b4l_add_caps();
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/shortcodes/send_badges_students_shortcode.php';





/**************************************************************************
 *************** CUSTOM SUBMENUS - ADMINISTRATION PANEL *******************
 *************************************************************************/

/**
 * BADGES ISSUER INFORMATION CUSTOM SUBMENU
 * Submenu page for the admin to give information useful for the certification.
 */
require plugin_dir_path( __FILE__ ) . 'includes/submenu_pages/badges_issuer_information.php';

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
require plugin_dir_path( __FILE__ ) . 'includes/submenu_pages/send_badges_students.php';





/**************************************************************************
 ***** 'ACCEPT BADGE' PAGE AFTER RECEIVING THE CERTIFICATION BY MAIL ******
 *************************************************************************/

/**
 * Create a page after receiving a certification by mail. This page is generic,
 * that is to say it will be always loaded, only the certification information
 * will change.
 */
require plugin_dir_path( __FILE__ ) . 'includes/site_pages/accept_badge.php';





/**************************************************************************
 ****************************** TEMPLATES *********************************
 *************************************************************************/

/**
 * Executes b4l_include_template_function for initializing the Custom Post Template.
 */
add_filter( 'template_include', 'b4l_include_template_function', 1 );

/**
 * Adding the template for 'Single Badge' page to the list of templates.
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
 * @param File $template_path Template path
 * @return File $template_path Template path
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

/**
 * NOT AVAILABLE FOR THE MOMENT
 * 
 * SEND BADGES TO STUDENTS CUSTOM SUBMENU
 * A teacher can send certifications by mails to a (group of) student(s) by the
 * administration panel.
 
require plugin_dir_path( __FILE__ ) . 'includes/site_pages/badges_user_profil.php';
*/



?>