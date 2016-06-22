<?php

// A MODIFIER
/**
 * Executes b4l_create_accept_badge_page during the initialization phase.
 */
add_action( 'admin_init', 'b4l_create_accept_badge_page' );

/**
 * Creates the 'Accept Badge' Page
 */
function b4l_create_accept_badge_page(){

	//Adds a new page if it doesn't exist.
	if (get_page_by_title('Accept badge') == NULL) {
		//creating post object
		$bsp_award_page=array(
		'post_name'=>'changes-saved',
		'post_title'=>'Changes saved',
		'post_content'=>'Your changes have been saved!',
		'post_excerpt'=>'badges',
		'post_status'=>'publish',
		'post_type'=>'page',
		'page_template'=>'badges-changes-template.php',
		'comment_status'=>'closed'
		);
	}
	//Inserts the page
	$post_id=wp_insert_post($bsp_award_page);
	
	//adding the post meta so we can easily find it and delete it (or do other things)
	//add_post_meta($post_id,'bsp_delete_page','delete page', true);
}


/**
 * Executes b4l_accept_badge_page_content during the page creation (content).
 */
add_filter('the_content','b4l_changes_content');

/**
 * This function adds content to 'Accept Badge' page.
 */
function b4l_changes_content($content){
    echo 'Your changes have been saved';
}
?>