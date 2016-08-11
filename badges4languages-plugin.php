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
 * Version:           1.1.3
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

// Create Database Tables for the plugin.
require plugin_dir_path( __FILE__ ) . 'includes/initialisation/db_tables.php';

// Create the custom post 'badge' and 'class'.
require plugin_dir_path( __FILE__ ) . 'includes/initialisation/custom_post_badge.php';
require plugin_dir_path( __FILE__ ) . 'includes/initialisation/custom_post_class.php';

// Create custom taxonomies (categories) 'TeacherLevels', 'StudentLevels', 'Skills' and 'BadgesCategories' for 'badge'.
require plugin_dir_path( __FILE__ ) . 'includes/initialisation/custom_taxonomies.php';

// Create custom metaboxes for the custom posts 'badge' and 'class'.
require plugin_dir_path( __FILE__ ) . 'includes/initialisation/custom_metabox_badge.php';
require plugin_dir_path( __FILE__ ) . 'includes/initialisation/custom_metabox_class.php';

// Create custom roles and capabilities 'Teacher', 'Academy', 'Student' and 'Badge Editor'.
require plugin_dir_path( __FILE__ ) . 'includes/initialisation/users_roles_and_capabilities.php';

// Create a shortcode to display the 'Send badges to 1 student' form for a teacher in a page/post.
require plugin_dir_path( __FILE__ ) . 'includes/shortcodes/send_badges_one_student_shortcode.php';

// Create a shortcode to display the 'Send badges to students' form for a teacher in a page/post.
require plugin_dir_path( __FILE__ ) . 'includes/shortcodes/send_badges_students_shortcode.php';

// Create a shortcode to display the 'class' custom post form to allow the creation in frontend.
require plugin_dir_path( __FILE__ ) . 'includes/shortcodes/class_form_shortcode.php';

// Create 2 custom fields for the comments: title and ratings.
require plugin_dir_path( __FILE__ ) . 'included_plugins/extendcomment/extendcomment.php';





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
 * SEND BADGES TO 1 STUDENT CUSTOM SUBMENU
 * A teacher can send certifications by mails to 1 student by the administration panel.
 */
require plugin_dir_path( __FILE__ ) . 'includes/submenu_pages/send_badges_one_student.php';

/**
 * SEND BADGES TO STUDENTS CUSTOM SUBMENU
 * A teacher can send certifications by mails to a group of students by the administration panel.
 */
require plugin_dir_path( __FILE__ ) . 'includes/submenu_pages/send_badges_students.php';

/**
 * BADGES SETTINGS
 * Plugin settings. The user can choose if he wants to erase or not the database when he
 * deletes the plugin.
 */
require plugin_dir_path( __FILE__ ) . 'includes/submenu_pages/options.php';





/**************************************************************************
 ************************* FRONT END PAGES ********************************
 *************************************************************************/

/**
 * ACCEPT BADGE
 * Create a page after receiving a certification by mail. This page is generic,
 * that is to say it will be always loaded, only the certification information
 * will change.
 */
require plugin_dir_path( __FILE__ ) . 'includes/site_pages/accept_badge.php';

/**
 * FRONT END USER PROFILE
 * Front end user profile with field for the badges
*/
require plugin_dir_path( __FILE__ ) . 'includes/site_pages/front_end_user_profile.php';





/**************************************************************************
 ****************************** TEMPLATES *********************************
 *************************************************************************/

/**
 * Execute b4l_include_template_function for initializing the Custom Post Template.
 */
add_filter( 'template_include', 'b4l_include_badge_template', 1 );

/**
 * Adding the template for 'Single Badge' page to the list of templates.
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
 * @param File $template_path Template path
 * @return File $template_path Template path
 */
function b4l_include_badge_template( $template_path ) {
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
 * BACK END USER PROFILE
 * Back end user profile with field for the badges
*/
require plugin_dir_path( __FILE__ ) . 'includes/site_pages/back_end_user_profile.php';

?>