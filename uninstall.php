<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @link       http://www.badges4languages.org
 * @since      1.0.0
 * @author     Alexandre LEVACHER
 *
 * @package    Badges4languages_Plugin
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

//Delete the plugin
$option_name = 'b4l_plugin_options';
delete_option( $option_name );
// For site options in Multisite
delete_site_option( $option_name );  
 
//Drop all the 'badges4languages-plugin' Database Tables
global $wpdb;
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}b4l_languages" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}b4l_students" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}b4l_teachers" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}b4l_teacherLevels" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}b4l_studentLevels" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}b4l_skills" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}b4l_number_certifications" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}b4l_issuer_information" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}b4l_userBadgesProfil" );

//Drop all the custom roles and custom capabilities
require plugin_dir_path( __FILE__ ) . 'includes/initialisation/users_roles_and_capabilities.php';
b4l_remove_caps();