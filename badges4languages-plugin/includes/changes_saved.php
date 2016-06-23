<?php
 /*
  * Description:        Create a hidden page to display 'Your changes have been saved !'
  * Version:            1.0.0
  * Author:             Alexandre Levacher
 */

/**
 * Adds b4l_badges_issuer_information_submenu_page during to the admin menu.
 */
add_action('admin_menu', 'b4l_badges_changes_saved_submenu_page');

/**
 * Adds 'Your changes have been saved!' page.
 */
function b4l_badges_changes_saved_submenu_page() {
    add_submenu_page(
        null,
        'Your changes have been saved!',
        'Your changes have been saved!',
        'manage_options',
        'badges-changes-saved-submenu-page',
        'b4l_badges_changes_saved_page_callback' 
    );
}
 
/**
 * Creates the page content.
 */
function b4l_badges_changes_saved_page_callback() {
    echo '<h3>Your changes have been saved !</h3>';
}

