<?php

/**
 * ----- badges4languages-plugin -----
 *
 * This plugin allows to a user to get a student or teacher certification by
 * himself or to receive it thanks to someone who has given him.
 *
 * @link              http://www.badges4languages.com
 * @since             1.0.0
 * @package           Badges4languages_Plugin
 *
 * @wordpress-plugin
 * Plugin Name:       Badges4languages-plugin
 * Plugin URI:        http://www.badges4languages.com
 * Description:       Gives a student or a teacher certification to someone.
 * Version:           1.1.2
 * Author:            Alexandre LEVACHER
 * Author URI:        http://www.badges4languages.com
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

// Create Database Tables for the Custom Post 'badge'.
require plugin_dir_path( __FILE__ ) . 'includes/initialisation/db_tables.php';

// Create the custom post 'badge'.
require plugin_dir_path( __FILE__ ) . 'includes/initialisation/custom_post.php';

// Create custom taxonomies (categories) 'TeacherLevels', 'StudentLevels', 'Skills' and 'BadgesCategories'.
require plugin_dir_path( __FILE__ ) . 'includes/initialisation/custom_taxonomies.php';

// Create custom metabox for the custom post 'badge' which displays a link associated to a language.
require plugin_dir_path( __FILE__ ) . 'includes/initialisation/custom_metabox.php';

// Create custom roles and capabilities 'Teacher', 'Academy', 'Student' and 'Badge Editor'.
require plugin_dir_path( __FILE__ ) . 'includes/initialisation/users_roles_and_capabilities.php';

// Create a shortcode to display the 'Send a badge to student' form for a teacher in a page/post.
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
 * This plugin is used to create the submenu 'CSV File Upload' for the Custom Post 'badge'.
 */
require plugin_dir_path( __FILE__ ) . 'included_plugins/wp_csv_to_db/wp_csv_to_db.php';

/**
 * SEND BADGES TO STUDENTS CUSTOM SUBMENU
 * A teacher can send certifications by mails to a (group of) student(s) by the administration panel.
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
 * Execute b4l_include_template_function for initializing the Custom Post Template.
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
 ****************** ADD BADGES FIELD INTO USER PROFILE ********************
 *************************************************************************/

/**
 * NOT AVAILABLE FOR THE MOMENT
 * 
 * SEND BADGES TO STUDENTS CUSTOM SUBMENU
 * A teacher can send certifications by mails to a (group of) student(s) by the
 * administration panel.
*/
require plugin_dir_path( __FILE__ ) . 'includes/site_pages/back_end_user_profile.php';

require plugin_dir_path( __FILE__ ) . 'includes/site_pages/front_end_user_profile.php';


?>
