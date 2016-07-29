<?php
 /*
  * Generic page to inform the user that he has just earned a badge. Contains a Mozilla OpenBadges link.
  * 
  * @author Alexandre Levacher
  * @package badges4languages-plugin
  * @subpackage includes/site_pages
  * @since 1.0.0
 */

/**
 * Executes b4l_create_accept_badge_page during the initialization phase.
 */
add_action( 'admin_init', 'b4l_create_accept_badge_page' );

/**
 * Creates the 'Accept Badge' Page
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
 */
function b4l_create_accept_badge_page(){

	//Adds a new page if it doesn't exist.
	if (get_page_by_title('Accept badge') == NULL) {
		//creating post object
		$b4l_award_page=array(
		'post_name'=>'accept-badge',
		'post_title'=>'Accept badge',
		'post_excerpt'=>'badges',
		'post_status'=>'publish',
		'post_type'=>'page',
		'page_template'=>'single_accept_badge.php',
		'comment_status'=>'closed'
		);
	}
	//Inserts the page
	$post_id=wp_insert_post($b4l_award_page);
}




/**
 * Include a custom template.
 */
add_filter( 'template_include', 'b4l_accept_badge_template');

/**
 * Adding the template for 'Accept Badge' page to the list of templates.
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
 * @param File $template Template path
 * @return File $template Template path
 */
function b4l_accept_badge_template( $template ) {
    //checking if the page has the slug of accept-badge
    if ( is_page( 'accept-badge' )  ) {
        $file = WP_PLUGIN_DIR . '/badges4languages-plugin/templates/single_accept_badge.php';
        if (file_exists($file)) {
            $template = $file;
        }
    }
    return $template;
}
