<?php

function b4l_add_roles() {
    
    add_role('b4l_university', __( 'University', 'badge' ), array(
        'read' => true, 
        'edit_posts' => true,
        'delete_posts' => true,
        'edit_published_posts' => true,
        'delete_published_posts' => true,
        'publish_posts' => true,
        'upload_files' => true
    ));
    
    add_role('b4l_teacher', __( 'Teacher', 'badge' ), array(
        'read' => true, 
        'edit_posts' => true,
        'delete_posts' => true,
        'edit_published_posts' => true,
        'delete_published_posts' => true,
        'publish_posts' => true,
        'upload_files' => true
    ));

    add_role('b4l_student', __( 'Student', 'badge' ), array(
        'read' => true
    ));
}

function b4l_add_caps() {
    
    global $wp_roles;
    
    $wp_roles->add_cap( 'administrator', 'b4l_import_csv_to_db' );
    $wp_roles->add_cap( 'administrator', 'send_badges_to_students' );
    $wp_roles->add_cap( 'administrator', 'edit_badge' );
    $wp_roles->add_cap( 'administrator', 'delete_badge' );
    
    $wp_roles->add_cap( 'b4l_university', 'send_badges_to_students' );
    $wp_roles->add_cap( 'b4l_university', 'edit_badge' );
    $wp_roles->add_cap( 'b4l_university', 'delete_badge' );
    
    $wp_roles->add_cap( 'b4l_teacher', 'send_badges_to_students' );
    $wp_roles->add_cap( 'b4l_teacher', 'edit_badge' );
    $wp_roles->add_cap( 'b4l_teacher', 'delete_badge' );
}

function b4l_remove_caps() {
    global $wp_roles;
    $wp_roles->remove_cap( 'administrator', 'b4l_import_csv_to_db' );
}

//FAIRE UN MAP
//
//https://wordpress.org/support/topic/plugin-members-custom-post-type

//https://codex.wordpress.org/Function_Reference/register_post_type