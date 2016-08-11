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
 * @global WordpressObject $wp_roles Wordpress roles
 */
function b4l_add_roles() {
    global $wp_roles;
    
    //We copy the editor's capabilities for the Badges Editor
    if ( ! isset( $wp_roles ) )
        $wp_roles = new WP_Roles();
    $editor = $wp_roles->get_role('editor');
    $wp_roles->add_role('b4l_badges_editor', 'B4L Editor', $editor->capabilities);
    
    add_role('b4l_academy', 'B4L Academy', array(
        'read' => true,
    ));
    
    add_role('b4l_teacher', 'B4L Teacher', array(
        'read' => true,
    ));

    add_role('b4l_student', 'B4L Student', array(
        'read' => true
    ));
}


/**
 * Creates the capabilites by default for our custom roles and admin role. 
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.1
 * @global WordpressObject $wp_roles Wordpress roles
 */
function b4l_add_caps() {
    
    global $wp_roles;
     
    /*******************************************************************
     ********************** 'BADGE' CUSTOM POST ************************
     *******************************************************************/
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
    $wp_roles->add_cap( 'administrator', 'b4l_send_badges_to_one_student' );
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
    $wp_roles->add_cap( 'b4l_badges_editor', 'b4l_send_badges_to_one_student' );
    $wp_roles->add_cap( 'b4l_badges_editor', 'b4l_send_badges_to_students' );
    $wp_roles->add_cap( 'b4l_badges_editor', 'b4l_badges_issuer_information' );
    
    $wp_roles->add_cap( 'b4l_academy', 'b4l_send_badges_to_one_student' );
    $wp_roles->add_cap( 'b4l_academy', 'b4l_send_badges_to_students' );
    $wp_roles->add_cap( 'b4l_academy', 'read_badge' );
    
    $wp_roles->add_cap( 'b4l_teacher', 'b4l_send_badges_to_one_student' );
    $wp_roles->add_cap( 'b4l_teacher', 'b4l_send_badges_to_students' );
    $wp_roles->add_cap( 'b4l_teacher', 'read_badge' );
    
    $wp_roles->add_cap( 'b4l_student', 'read_badge' );
    
    
    /*******************************************************************
     ********************** 'CLASS' CUSTOM POST ************************
     *******************************************************************/
    $wp_roles->add_cap( 'administrator', 'edit_class' );
    $wp_roles->add_cap( 'administrator', 'read_class' );
    $wp_roles->add_cap( 'administrator', 'delete_class' );
    $wp_roles->add_cap( 'administrator', 'delete_classes' );
    $wp_roles->add_cap( 'administrator', 'delete_others_classes' );
    $wp_roles->add_cap( 'administrator', 'delete_private_classes' );
    $wp_roles->add_cap( 'administrator', 'delete_published_classes' );
    $wp_roles->add_cap( 'administrator', 'edit_classes' );
    $wp_roles->add_cap( 'administrator', 'edit_others_classes' );
    $wp_roles->add_cap( 'administrator', 'edit_private_classes' );
    $wp_roles->add_cap( 'administrator', 'edit_published_classes' );
    $wp_roles->add_cap( 'administrator', 'publish_classes' );
    $wp_roles->add_cap( 'administrator', 'read_private_classes' );
    
    $wp_roles->add_cap( 'b4l_classes_editor', 'edit_class' );
    $wp_roles->add_cap( 'b4l_classes_editor', 'read_class' );
    $wp_roles->add_cap( 'b4l_classes_editor', 'delete_class' );
    $wp_roles->add_cap( 'b4l_classes_editor', 'delete_classes' );
    $wp_roles->add_cap( 'b4l_classes_editor', 'delete_others_classes' );
    $wp_roles->add_cap( 'b4l_classes_editor', 'delete_private_classes' );
    $wp_roles->add_cap( 'b4l_classes_editor', 'delete_published_classes' );
    $wp_roles->add_cap( 'b4l_classes_editor', 'edit_classes' );
    $wp_roles->add_cap( 'b4l_classes_editor', 'edit_others_classes' );
    $wp_roles->add_cap( 'b4l_classes_editor', 'edit_private_classes' );
    $wp_roles->add_cap( 'b4l_classes_editor', 'edit_published_classes' );
    $wp_roles->add_cap( 'b4l_classes_editor', 'publish_classes' );
    $wp_roles->add_cap( 'b4l_classes_editor', 'read_private_classes' );
    
    $wp_roles->add_cap( 'b4l_academy', 'edit_class' );
    $wp_roles->add_cap( 'b4l_academy', 'read_class' );
    $wp_roles->add_cap( 'b4l_academy', 'delete_class' );
    $wp_roles->add_cap( 'b4l_academy', 'delete_classes' );
    $wp_roles->add_cap( 'b4l_academy', 'delete_private_classes' );
    $wp_roles->add_cap( 'b4l_academy', 'delete_published_classes' );
    $wp_roles->add_cap( 'b4l_academy', 'edit_classes' );
    $wp_roles->add_cap( 'b4l_academy', 'edit_private_classes' );
    $wp_roles->add_cap( 'b4l_academy', 'edit_published_classes' );
    $wp_roles->add_cap( 'b4l_academy', 'publish_classes' );
    $wp_roles->add_cap( 'b4l_academy', 'read_private_classes' );
    $wp_roles->add_cap( 'b4l_academy', 'upload_files' );
    $wp_roles->add_cap( 'b4l_academy', 'read_class' );
    
    $wp_roles->add_cap( 'b4l_teacher', 'edit_class' );
    $wp_roles->add_cap( 'b4l_teacher', 'read_class' );
    $wp_roles->add_cap( 'b4l_teacher', 'delete_class' );
    $wp_roles->add_cap( 'b4l_teacher', 'delete_classes' );
    $wp_roles->add_cap( 'b4l_teacher', 'delete_private_classes' );
    $wp_roles->add_cap( 'b4l_teacher', 'delete_published_classes' );
    $wp_roles->add_cap( 'b4l_teacher', 'edit_classes' );
    $wp_roles->add_cap( 'b4l_teacher', 'edit_private_classes' );
    $wp_roles->add_cap( 'b4l_teacher', 'edit_published_classes' );
    $wp_roles->add_cap( 'b4l_teacher', 'publish_classes' );
    $wp_roles->add_cap( 'b4l_teacher', 'read_private_classes' );
    $wp_roles->add_cap( 'b4l_teacher', 'upload_files' );
    $wp_roles->add_cap( 'b4l_teacher', 'read_class' );
    
    $wp_roles->add_cap( 'b4l_student', 'read_class' );
}


/**
 * Removes all the capabilities given by the plugin when it is uninstalled. 
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.1
 * @global WordpressObject $wp_roles Wordpress roles
 */
function b4l_remove_caps() {
    
    global $wp_roles;
    
    /*******************************************************************
     ********************** 'BADGE' CUSTOM POST ************************
     *******************************************************************/
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
    $wp_roles->remove_cap( 'administrator', 'b4l_send_badges_to_one_student' );
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
    $wp_roles->remove_cap( 'b4l_badges_editor', 'b4l_send_badges_to_one_student' );
    $wp_roles->remove_cap( 'b4l_badges_editor', 'b4l_send_badges_to_students' );
    $wp_roles->remove_cap( 'b4l_badges_editor', 'b4l_badges_issuer_information' );
   
    $wp_roles->remove_cap( 'b4l_academy', 'b4l_send_badges_to_one_student' );
    $wp_roles->remove_cap( 'b4l_academy', 'b4l_send_badges_to_students' );
    $wp_roles->remove_cap( 'b4l_academy', 'read_badge' );
    
    $wp_roles->remove_cap( 'b4l_teacher', 'b4l_send_badges_to_one_student' );
    $wp_roles->remove_cap( 'b4l_teacher', 'b4l_send_badges_to_students' );
    $wp_roles->remove_cap( 'b4l_teacher', 'read_badge' );
    
    $wp_roles->remove_cap( 'b4l_student', 'read_badge' );
    
    
    /*******************************************************************
     ********************** 'CLASS' CUSTOM POST ************************
     *******************************************************************/
    $wp_roles->remove_cap( 'administrator', 'edit_class' );
    $wp_roles->remove_cap( 'administrator', 'read_class' );
    $wp_roles->remove_cap( 'administrator', 'delete_class' );
    $wp_roles->remove_cap( 'administrator', 'delete_classes' );
    $wp_roles->remove_cap( 'administrator', 'delete_others_classes' );
    $wp_roles->remove_cap( 'administrator', 'delete_private_classes' );
    $wp_roles->remove_cap( 'administrator', 'delete_published_classes' );
    $wp_roles->remove_cap( 'administrator', 'edit_classes' );
    $wp_roles->remove_cap( 'administrator', 'edit_others_classes' );
    $wp_roles->remove_cap( 'administrator', 'edit_private_classes' );
    $wp_roles->remove_cap( 'administrator', 'edit_published_classes' );
    $wp_roles->remove_cap( 'administrator', 'publish_classes' );
    $wp_roles->remove_cap( 'administrator', 'read_private_classes' );
    
    $wp_roles->remove_cap( 'b4l_classes_editor', 'edit_class' );
    $wp_roles->remove_cap( 'b4l_classes_editor', 'read_class' );
    $wp_roles->remove_cap( 'b4l_classes_editor', 'delete_class' );
    $wp_roles->remove_cap( 'b4l_classes_editor', 'delete_classes' );
    $wp_roles->remove_cap( 'b4l_classes_editor', 'delete_others_classes' );
    $wp_roles->remove_cap( 'b4l_classes_editor', 'delete_private_classes' );
    $wp_roles->remove_cap( 'b4l_classes_editor', 'delete_published_classes' );
    $wp_roles->remove_cap( 'b4l_classes_editor', 'edit_classes' );
    $wp_roles->remove_cap( 'b4l_classes_editor', 'edit_others_classes' );
    $wp_roles->remove_cap( 'b4l_classes_editor', 'edit_private_classes' );
    $wp_roles->remove_cap( 'b4l_classes_editor', 'edit_published_classes' );
    $wp_roles->remove_cap( 'b4l_classes_editor', 'publish_classes' );
    $wp_roles->remove_cap( 'b4l_classes_editor', 'read_private_classes' );
    
    $wp_roles->remove_cap( 'b4l_academy', 'edit_class' );
    $wp_roles->remove_cap( 'b4l_academy', 'read_class' );
    $wp_roles->remove_cap( 'b4l_academy', 'delete_class' );
    $wp_roles->remove_cap( 'b4l_academy', 'delete_classes' );
    $wp_roles->remove_cap( 'b4l_academy', 'delete_private_classes' );
    $wp_roles->remove_cap( 'b4l_academy', 'delete_published_classes' );
    $wp_roles->remove_cap( 'b4l_academy', 'edit_classes' );
    $wp_roles->remove_cap( 'b4l_academy', 'edit_private_classes' );
    $wp_roles->remove_cap( 'b4l_academy', 'edit_published_classes' );
    $wp_roles->remove_cap( 'b4l_academy', 'publish_classes' );
    $wp_roles->remove_cap( 'b4l_academy', 'read_private_classes' );
    $wp_roles->remove_cap( 'b4l_academy', 'upload_files' );
    $wp_roles->remove_cap( 'b4l_academy', 'read_class' );
    
    $wp_roles->remove_cap( 'b4l_teacher', 'edit_class' );
    $wp_roles->remove_cap( 'b4l_teacher', 'read_class' );
    $wp_roles->remove_cap( 'b4l_teacher', 'delete_class' );
    $wp_roles->remove_cap( 'b4l_teacher', 'delete_classes' );
    $wp_roles->remove_cap( 'b4l_teacher', 'delete_private_classes' );
    $wp_roles->remove_cap( 'b4l_teacher', 'delete_published_classes' );
    $wp_roles->remove_cap( 'b4l_teacher', 'edit_classes' );
    $wp_roles->remove_cap( 'b4l_teacher', 'edit_private_classes' );
    $wp_roles->remove_cap( 'b4l_teacher', 'edit_published_classes' );
    $wp_roles->remove_cap( 'b4l_teacher', 'publish_classes' );
    $wp_roles->remove_cap( 'b4l_teacher', 'read_private_classes' );
    $wp_roles->remove_cap( 'b4l_teacher', 'upload_files' );
    $wp_roles->remove_cap( 'b4l_teacher', 'read_class' );
    
    $wp_roles->remove_cap( 'b4l_student', 'read_class' );
}
