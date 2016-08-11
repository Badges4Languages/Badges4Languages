<?php

/**
 * Creates a shortcode which can be used in a post or in a page.
 * 
 * This shortcode is an exact copy of the submenu 'Send Badges To Students' in
 * the admin/teacher/academy interface. The code can be found at :
 * '/includes/submenu_pages/send_badges_students.php'
 * So if there is a modification of the send_badges_students.php file, the
 * shortcode will be automatically modificated.
 * 
 * @author Alexandre LEVACHER
 * @since 1.1.0
 * @global WordpressObject $current_user Information about the current user
*/
function b4l_send_badges_students_shortcode(){
    global $current_user;
    get_currentuserinfo(); 
    
    if ( user_can( $current_user, "administrator" ) || user_can( $current_user, "b4l_badges_editor" ) || user_can( $current_user, "b4l_academy" ) || user_can( $current_user, "b4l_teacher" ) ){ 
        require_once WP_PLUGIN_DIR . '/badges4languages-plugin/includes/submenu_pages/send_badges_students.php';
        b4l_send_badges_students_page_callback();
    } else {
        echo "<p>You don't have the permissions to see the content of this page !</p>";
    }
}

/**
 * Shortcode : [send_badges]. 
 * Displays the content of b4l_send_badges_students_shortcode function.
 */
add_shortcode('send_badges', 'b4l_send_badges_students_shortcode');