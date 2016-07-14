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
 * Execute b4l_create_roles_and_capabilities during the initialization phase.
 */
add_action( 'init', 'b4l_create_roles_and_capabilities', 0 );

/**
 * Create the roles 'Teacher', 'Academy' and 'Students' and give them some 
 * capabilities like submenu visible or not, etc.
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.1
 */
function b4l_create_roles_and_capabilities() {
    b4l_add_roles();
    b4l_add_caps();
}


/**
 * Creates the roles 'Teacher', 'Academy' and 'Students' and give them some capabilities
 * like submenu visible or not, etc.
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.1
 */
function b4l_add_roles() {
    global $wp_roles;
    
    //We copy the editor's capabilities for the Badges Editor
    if ( ! isset( $wp_roles ) )
        $wp_roles = new WP_Roles();
    $editor = $wp_roles->get_role('editor');
    $wp_roles->add_role('b4l_badges_editor', __( 'Badges Editor', 'badge' ), $editor->capabilities);
    
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
            
    $wp_roles->add_cap( 'administrator', 'edit_badge' );
    $wp_roles->add_cap( 'administrator', 'read_badge' );
    $wp_roles->add_cap( 'administrator', 'delete_badge' );
    $wp_roles->add_cap( 'administrator', 'delete_badges' );
    $wp_roles->add_cap( 'administrator', 'delete_others_badges' );
    $wp_roles->add_cap( 'administrator', 'delete_private_badges' );
    $wp_roles->add_cap( 'administrator', 'delete_published_badges' );
    $wp_roles->add_cap( 'administrator', 'edit_badges' );
    $wp_roles->add_cap( 'administrator', 'edit_others_badges' );
    $wp_roles->add_cap( 'administrator', 'edit_private_badges' );
    $wp_roles->add_cap( 'administrator', 'edit_published_badges' );
    $wp_roles->add_cap( 'administrator', 'publish_badges' );
    $wp_roles->add_cap( 'administrator', 'read_private_badges' );
    $wp_roles->add_cap( 'administrator', 'b4l_import_csv_to_db' );
    $wp_roles->add_cap( 'administrator', 'b4l_send_badges_to_students' );
    $wp_roles->add_cap( 'administrator', 'b4l_badges_issuer_information' );
    
    $wp_roles->add_cap( 'b4l_badges_editor', 'edit_badge' );
    $wp_roles->add_cap( 'b4l_badges_editor', 'read_badge' );
    $wp_roles->add_cap( 'b4l_badges_editor', 'delete_badge' );
    $wp_roles->add_cap( 'b4l_badges_editor', 'delete_badges' );
    $wp_roles->add_cap( 'b4l_badges_editor', 'delete_others_badges' );
    $wp_roles->add_cap( 'b4l_badges_editor', 'delete_private_badges' );
    $wp_roles->add_cap( 'b4l_badges_editor', 'delete_published_badges' );
    $wp_roles->add_cap( 'b4l_badges_editor', 'edit_badges' );
    $wp_roles->add_cap( 'b4l_badges_editor', 'edit_others_badges' );
    $wp_roles->add_cap( 'b4l_badges_editor', 'edit_private_badges' );
    $wp_roles->add_cap( 'b4l_badges_editor', 'edit_published_badges' );
    $wp_roles->add_cap( 'b4l_badges_editor', 'publish_badges' );
    $wp_roles->add_cap( 'b4l_badges_editor', 'read_private_badges' );
    $wp_roles->add_cap( 'b4l_badges_editor', 'b4l_import_csv_to_db' );
    $wp_roles->add_cap( 'b4l_badges_editor', 'b4l_send_badges_to_students' );
    $wp_roles->add_cap( 'b4l_badges_editor', 'b4l_badges_issuer_information' );
    
    $wp_roles->add_cap( 'b4l_academy', 'b4l_send_badges_to_students' );
    $wp_roles->add_cap( 'b4l_academy', 'read_badge' );
    
    $wp_roles->add_cap( 'b4l_teacher', 'b4l_send_badges_to_students' );
    $wp_roles->add_cap( 'b4l_teacher', 'read_badge' );
    
    $wp_roles->add_cap( 'b4l_student', 'read_badge' );
}


/**
 * Removes all the capabilities given by the plugin when it is uninstalled. 
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.1
 */
function b4l_remove_caps() {
    
    global $wp_roles;
    
    $wp_roles->remove_cap( 'administrator', 'edit_badge' );
    $wp_roles->remove_cap( 'administrator', 'read_badge' );
    $wp_roles->remove_cap( 'administrator', 'delete_badge' );
    $wp_roles->remove_cap( 'administrator', 'delete_badges' );
    $wp_roles->remove_cap( 'administrator', 'delete_others_badges' );
    $wp_roles->remove_cap( 'administrator', 'delete_private_badges' );
    $wp_roles->remove_cap( 'administrator', 'delete_published_badges' );
    $wp_roles->remove_cap( 'administrator', 'edit_badges' );
    $wp_roles->remove_cap( 'administrator', 'edit_others_badges' );
    $wp_roles->remove_cap( 'administrator', 'edit_private_badges' );
    $wp_roles->remove_cap( 'administrator', 'edit_published_badges' );
    $wp_roles->remove_cap( 'administrator', 'publish_badges' );
    $wp_roles->remove_cap( 'administrator', 'read_private_badges' );
    $wp_roles->remove_cap( 'administrator', 'b4l_import_csv_to_db' );
    $wp_roles->remove_cap( 'administrator', 'b4l_send_badges_to_students' );
    $wp_roles->remove_cap( 'administrator', 'b4l_badges_issuer_information' );
    
    $wp_roles->remove_cap( 'b4l_badges_editor', 'edit_badge' );
    $wp_roles->remove_cap( 'b4l_badges_editor', 'read_badge' );
    $wp_roles->remove_cap( 'b4l_badges_editor', 'delete_badge' );
    $wp_roles->remove_cap( 'b4l_badges_editor', 'delete_badges' );
    $wp_roles->remove_cap( 'b4l_badges_editor', 'delete_others_badges' );
    $wp_roles->remove_cap( 'b4l_badges_editor', 'delete_private_badges' );
    $wp_roles->remove_cap( 'b4l_badges_editor', 'delete_published_badges' );
    $wp_roles->remove_cap( 'b4l_badges_editor', 'edit_badges' );
    $wp_roles->remove_cap( 'b4l_badges_editor', 'edit_others_badges' );
    $wp_roles->remove_cap( 'b4l_badges_editor', 'edit_private_badges' );
    $wp_roles->remove_cap( 'b4l_badges_editor', 'edit_published_badges' );
    $wp_roles->remove_cap( 'b4l_badges_editor', 'publish_badges' );
    $wp_roles->remove_cap( 'b4l_badges_editor', 'read_private_badges' );
    $wp_roles->remove_cap( 'b4l_badges_editor', 'b4l_import_csv_to_db' );
    $wp_roles->remove_cap( 'b4l_badges_editor', 'b4l_send_badges_to_students' );
    $wp_roles->remove_cap( 'b4l_badges_editor', 'b4l_badges_issuer_information' );
   
    $wp_roles->remove_cap( 'b4l_academy', 'b4l_send_badges_to_students' );
    $wp_roles->remove_cap( 'b4l_academy', 'read_badge' );
    
    $wp_roles->remove_cap( 'b4l_teacher', 'b4l_send_badges_to_students' );
    $wp_roles->remove_cap( 'b4l_teacher', 'read_badge' );
    
    $wp_roles->remove_cap( 'b4l_student', 'read_badge' );
}
