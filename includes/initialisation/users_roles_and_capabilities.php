<?php
 /*
  * This file contains all the custom users' roles and custom user's capabilities for the 'badge' custom post.
  * 
  * @author Alexandre Levacher
  * @package badges4Languages-plugin
  * @subpackage includes/initialisation
  * @since 1.0.1
 */


/**
 * Creates the roles 'Teacher', 'Academy' and 'Students' and give them some capabilities
 * like submenu visible or not, etc.
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.1
 */
function b4l_add_roles() {
    
    add_role('b4l_badges_editor', __( 'Badges Editor', 'badge' ), array(
        'delete_others_pages' => true, 
        'delete_others_posts' => true, 
        'delete_pages' => true, 
        'delete_posts' => true, 
        'delete_private_pages' => true, 
        'delete_private_posts' => true, 
        'delete_published_pages' => true, 
        'delete_published_posts' => true, 
        'edit_others_pages' => true, 
        'edit_others_posts' => true,
        'edit_pages' => true, 
        'edit_posts' => true, 
        'create_posts' => true, 
        'manage_categories' => true,
        'manage_links' => true,
        'publish_posts' => true, 
        'read' => true,
        'read_private_pages' => true,
        'read_private_posts ' => true,
        'upload_files ' => true,
    ));
    
    add_role('b4l_academy', __( 'Academy', 'badge' ), array(
        'read' => true,
    ));
    
    add_role('b4l_teacher', __( 'Teacher', 'badge' ), array(
        'read' => true,
    ));

    add_role('b4l_student', __( 'Student', 'badge' ), array(
        'read' => true
    ));
}


/**
 * Creates the capabilites by default for our custom roles and admin role. 
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.1
 */
function b4l_add_caps() {
    
    global $wp_roles;
    
    $wp_roles->add_cap( 'administrator', 'b4l_import_csv_to_db' );
    $wp_roles->add_cap( 'administrator', 'b4l_send_badges_to_students' );
    $wp_roles->add_cap( 'administrator', 'b4l_badges_issuer_information' );
    
    $wp_roles->add_cap( 'b4l_badges_editor', 'b4l_import_csv_to_db' );
    $wp_roles->add_cap( 'b4l_badges_editor', 'b4l_send_badges_to_students' );
    $wp_roles->add_cap( 'b4l_badges_editor', 'b4l_badges_issuer_information' );
    
    $wp_roles->add_cap( 'b4l_academy', 'b4l_send_badges_to_students' );
    
    $wp_roles->add_cap( 'b4l_teacher', 'b4l_send_badges_to_students' );
    
}


/**
 * Removes all the capabilities given by the plugin when it is uninstalled. 
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.1
 */
function b4l_remove_caps() {
    
    global $wp_roles;
    
    $wp_roles->remove_cap( 'administrator', 'b4l_import_csv_to_db' );
    $wp_roles->remove_cap( 'administrator', 'b4l_send_badges_to_students' );
    $wp_roles->remove_cap( 'administrator', 'b4l_badges_issuer_information' );
    
    $wp_roles->remove_cap( 'b4l_badges_editor', 'b4l_import_csv_to_db' );
    $wp_roles->remove_cap( 'b4l_badges_editor', 'b4l_send_badges_to_students' );
    $wp_roles->remove_cap( 'b4l_badges_editor', 'b4l_badges_issuer_information' );
   
    $wp_roles->remove_cap( 'b4l_academy', 'b4l_send_badges_to_students' );
    
    $wp_roles->remove_cap( 'b4l_teacher', 'b4l_send_badges_to_students' );
    
    //A SUPPRIMER
    $wp_roles->remove_cap( 'b4l_academy', 'b4l_badges_issuer_information' );
    $wp_roles->remove_cap( 'b4l_university', 'b4l_badges_issuer_information' );
    $wp_roles->remove_cap( 'b4l_university', 'b4l_send_badges_to_students' );
}
