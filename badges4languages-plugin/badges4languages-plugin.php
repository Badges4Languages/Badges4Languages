<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.badges4languages.org
 * @since             1.0.0
 * @package           Badges4languages_Plugin
 *
 * @wordpress-plugin
 * Plugin Name:       Badges4languages-plugin
 * Plugin URI:        http://www.badges4languages.org
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
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
 * Executes the 'b4l_create_db_table_teacherLevels' function
 * during the initialization phase.
 */
add_action('init', 'b4l_create_db_table_teacherLevels');

/**
 * Create/Update the '(prefix)teacherLevels' table
 */
function b4l_create_db_table_teacherLevels() {
    global $wpdb;
    $table_name = $wpdb->prefix . "teacherLevels"; 
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        language varchar(15) NOT NULL,
        T1 text NOT NULL,
        T2 text NOT NULL,
        T3 text NOT NULL,
        T4 text NOT NULL,
        T5 text NOT NULL,
        T6 text NOT NULL,
        UNIQUE KEY id (id)
  ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

/**
 * Executes the 'b4l_create_db_table_teacherLevels' function
 * during the initialization phase.
 */
add_action('init', 'b4l_create_db_table_studentLevels');

/**
 * Create/Update the '(prefix)teacherLevels' table
 */
function b4l_create_db_table_studentLevels() {
    global $wpdb;
    $table_name = $wpdb->prefix . "studentLevels"; 
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        language varchar(15) NOT NULL,
        A1 text NOT NULL,
        A2 text NOT NULL,
        B1 text NOT NULL,
        B2 text NOT NULL,
        C1 text NOT NULL,
        C2 text NOT NULL,
        UNIQUE KEY id (id)
  ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

/**
 * Executes the 'b4l_create_db_table_skills' function
 * during the initialization phase.
 */
add_action('init', 'b4l_create_db_table_skills');

/**
 * Create/Update the '(prefix)skills' table
 */
function b4l_create_db_table_skills() {
    global $wpdb;
    $table_name = $wpdb->prefix . "skills"; 
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        language tinytext NOT NULL,
        Listening text NOT NULL,
        Reading text NOT NULL,
        Speaking text NOT NULL,
        Writing text NOT NULL,
        UNIQUE KEY id (id)
) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

/**
 * Executes the Custom Function named b4l_create_badges_register 
 * during the initialization phase.
 */
add_action('init', 'b4l_create_badges_register');

/**
 * Creates the Custom Post
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
        
        //flush_rewrite_rules();    A EVITER !!!!!!!!!!!!
}

/**
 * Executes b4l_create_my_taxonomies during the initialization phase.
 */
add_action( 'init', 'b4l_create_my_taxonomies', 0 );

/**
 * Creates the Custom Taxonomies (categories) for the 
 * Custom Post 'badge'.
 */
function b4l_create_my_taxonomies() {
    b4l_create_TeacherLevels_taxonomies();
    b4l_create_StudentLevels_taxonomies();
    b4l_create_Skills_taxonomies();
    b4l_create_Tags_taxonomies();
}

/**
 * Creates the Custom Taxonomies 'TeacherLevels' (T1,T2,T3,etc).
 */
function b4l_create_TeacherLevels_taxonomies() {
    register_taxonomy(
        'badge_teacherlevels',
        'badge',
        array(
            'labels' => array(
                'name' => 'Teacher Levels',
                'add_new_item' => 'Add New TeacherLevel',
                'new_item_name' => "New TeacherLevel"
            ),
            'show_ui' => true,
            'show_tagcloud' => false,
            'hierarchical' => true
        )
    );
}

/**
 * Creates the Custom Taxonomies 'StudentLevels' (A1,A2,B1,etc).
 */
function b4l_create_StudentLevels_taxonomies() {
    register_taxonomy(
        'badge_studentlevels',
        'badge',
        array(
            'labels' => array(
                'name' => 'Student Levels',
                'add_new_item' => 'Add New StudentLevel',
                'new_item_name' => "New StudentLevel"
            ),
            'show_ui' => true,
            'show_tagcloud' => false,
            'hierarchical' => true
        )
    );
}

/**
 * Creates the Custom Taxonomies 'Skills' (Listening, Reading, etc).
 */
function b4l_create_Skills_taxonomies() {
    register_taxonomy(
        'badge_skills',
        'badge',
        array(
            'labels' => array(
                'name' => 'Skills',
                'add_new_item' => 'Add New Skill',
                'new_item_name' => "New Skill"
            ),
            'show_ui' => true,
            'show_tagcloud' => false,
            'hierarchical' => true
        )
    );
}

/**
 * Creates the Custom Taxonomies 'Tags'.
 */
function b4l_create_Tags_taxonomies() {
    register_taxonomy(
        'badge_tags',
        'badge',
        array(
            'labels' => array(
                'name' => 'Tags',
                'add_new_item' => 'Add New Tag',
                'new_item_name' => "New Tag"
            ),
            'show_ui' => true,
            'show_tagcloud' => false,
            'hierarchical' => true
        )
    );
}










//PEUT ETRE A DEGAGER  !!!!!!!!!!!!!!!!!!!!
/**
 * Executes b4l_my_admin when the WordPress Admin interface is visited.
 */
add_action( 'admin_init', 'b4l_my_admin' );

/**
 * Registers and associates a MetaBox with the 'badge' Custom Post
 */
function b4l_my_admin() {
    add_meta_box( 'translation_meta_box',
        'Translation to another language',
        'b4l_display_translation_meta_box',
        'badge', 'normal', 'high'
    );
}

/**
 * Displays the MetaBox on the page
 */
function b4l_display_translation_meta_box( $badge ) {
    $translation_language = esc_html( get_post_meta( $badge->ID, 'translation_language', true ) );
    $translation_text = esc_html( get_post_meta( $badge->ID, 'translation_text', true ) );
    ?>
    <table>
        <tr>
            <td style="width: 150px">Your language</td>
            <td>
                <!-- Selects the language of the translation -->
                <select style="width: 100px" name="badge_translation_language">
                    <?php 
                    $filename = WP_PLUGIN_DIR . '/badges4languages-plugin/assets/languages.csv';
                    $lines = explode(PHP_EOL, file_get_contents($filename));
                    for ( $counter = 1; $counter < count($lines) - 1; $counter++ ) { 
                        $firstLineElement = explode(",", $lines[$counter]);
                    ?>
                    <option value="<?php echo $firstLineElement[0]; ?>"> 
                        <?php echo $firstLineElement[0];?>
                    </option>
                    <?php 
                    }
                    ?>
                </select>
            </td>
        </tr>
        
        
        <tr>
            <td style="width: 150px">Your level</td>
            <td>
                <!-- Selects the language of the translation -->
                <select style="width: 100px" name="badge_translation_language">
                    <?php 
                    $filename1 = WP_PLUGIN_DIR . '/badges4languages-plugin/assets/languages.csv';
                    $lines = explode(PHP_EOL, file_get_contents($filename1));
                    $colonnes = explode(",", $lines[0]);
                    for ( $counter = 1; $counter < count($colonnes) - 1; $counter++ ) { 
                        $firstColumnElement = explode(PHP_EOL, $colonnes[$counter]);
                    ?>
                    <option value="<?php echo $firstColumnElement[0]; ?>"> 
                        <?php echo $firstColumnElement[0];?>
                    </option>
                    <?php 
                    }
                    ?>
                </select>
            </td>
        </tr>
        
        
        <tr>
            <!-- Builds the TextField of the translation -->
            <td style="width: 100%">Your text</td>
            <td><input type="text" size="80" name="badge_translation_text" placeholder="Test" value=" <?php echo $translation_text; ?>" /></td>
        </tr>
    </table>
    <?php
}

/**
add_action( 'save_post', 'b4l_add_movie_review_fields', 10, 2 );
function b4l_add_movie_review_fields( $movie_review_id, $movie_review ) {
    // Check post type for movie reviews
    if ( $movie_review->post_type == 'movie_reviews' ) {
        // Store data in post meta table if present in post data
        if ( isset( $_POST['movie_review_director_name'] ) && $_POST['movie_review_director_name'] != '' ) {
            update_post_meta( $movie_review_id, 'movie_director', $_POST['movie_review_director_name'] );
        }
        if ( isset( $_POST['movie_review_rating'] ) && $_POST['movie_review_rating'] != '' ) {
            update_post_meta( $movie_review_id, 'movie_rating', $_POST['movie_review_rating'] );
        }
    }
}

*/


//DECLARATION OF THE TEMPLATE
add_filter( 'template_include', 'b4l_include_template_function', 1 );
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






















add_action('admin-print-styles', 'bsp_admin_styles');
add_action('admin_print_scripts', 'bsp_admin_scripts');
add_action( 'wp_ajax_bsp_award_ajax', 'bsp_award_ajax_handle' );
add_action( 'wp_ajax_nopriv_bsp_award_ajax', 'bsp_award_ajax_handle' );
function bsp_issuer_api(){
	//wp_enqueue_script( 'openbadges', 'https://backpack.openbadges.org/issuer.js', array()); //for issuer API //not working if included like that, don't know why
	/*wp_enqueue_script( 'bsp-awards', plugins_url( 'js/award_badge.js', __FILE__ ), array( 'jquery' ) );*/
	wp_localize_script( 'bsp-awards', 'BSP_Awards', array(
                'ajaxurl'       => admin_url( 'admin-ajax.php' ),
            ) );
	wp_enqueue_script('custom', plugins_url( 'js/scripts.js', __FILE__ ), array( 'jquery' ));
}

//function for ajax request
function bsp_award_ajax_handle(){
	wp_die();
}

//enqueueing scripts
function bsp_admin_scripts(){
	wp_enqueue_script('media-upload'); //for wp media upload
	wp_enqueue_script('thickbox'); //for wp media upload
	wp_enqueue_script('jquery-ui-dialog');  // For admin panel popup alerts
	
	wp_register_script( 'wp_csv_to_db', plugins_url( 'js/admin_page.js', __FILE__ ), array('jquery','media-upload','thickbox') );  //including external admin_page javascript file
	wp_enqueue_script('wp_csv_to_db');
	wp_localize_script( 'wp_csv_to_db', 'bsp_pass_js_vars', array( 'ajax_image' => plugin_dir_url( __FILE__ ).'images/loading.gif', 'ajaxurl' => admin_url('admin-ajax.php') ) );
	wp_enqueue_media();
}

//function for enqueueing styles
function bsp_admin_styles(){
	wp_enqueue_style('thickbox');
}



//CODE HTML POUR L UPLOAD
add_action('admin_menu', 'b4l_csv_custom_submenu_page');

function b4l_csv_custom_submenu_page() {
    add_submenu_page(
        'edit.php?post_type=badge',
        'CSV File Upload',
        'CSV File Upload',
        'manage_options',
        'csv-custom-submenu-page',
        'b4l_csv_custom_submenu_page_callback' );
}
 
function b4l_csv_custom_submenu_page_callback() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/csv-custom-submenu-page.php'; 
}





?>