<?php
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
		$b4l_award_page=array(
		'post_name'=>'accept-badge',
		'post_title'=>'Accept badge',
		'post_content'=>'You got a badge!',
		'post_excerpt'=>'badges',
		'post_status'=>'publish',
		'post_type'=>'page',
		'page_template'=>'badges-accept-template.php',
		'comment_status'=>'closed'
		);
	}
	//Inserts the page
	$post_id=wp_insert_post($b4l_award_page);
	
	//adding the post meta so we can easily find it and delete it (or do other things)
	//add_post_meta($post_id,'bsp_delete_page','delete page', true);
}


/**
 * Executes b4l_accept_badge_page_content during the page creation (content).
 */
add_filter('the_content','b4l_accept_badge_page_content');

/**
 * This function adds content to 'Accept Badge' page.
 */
function b4l_accept_badge_page_content($content){
    //Verification of the existence of the page
    if ( is_page( 'accept-badge' )){

        //Getting the filename and the id from the url
        if(isset( $_GET['filename']) && ($_GET['id']) ){
            $path_json = $_GET['filename']; //Gets the encoded Json Path
            $path_json = base64_decode(str_rot13($path_json)); //Decodes the path
            $badge_name=$_GET['id'];
        }
        ?>
        <!-- Issuer API script (OpenBadges backpack) -->
        <script src="https://backpack.openbadges.org/issuer.js"></script>
        <script type="text/javascript">
            //Function for issuing the badge
            jQuery(document).ready(function($) {
                $('.js-required').hide();
                //Checks the navigator because the Issuer API isn't supported on MSIE Bbrowsers
                if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)){  
                    $('.acceptclick').hide();
                    $('.browserSupport').show(); //Writes an error message
                }else{
                    $('.browserSupport').hide();
                }
                $('#badge-error').hide();
                $('.acceptclick').click(function() {		
                    var assertionUrl = '<?php echo $path_json; ?>';
                    OpenBadges.issue([''+assertionUrl+''], function(errors, successes) {
                        if (errors.length > 0 ) {
                            $('#badge-error').show(); //Displays an error message
                            $.ajax({
                                url: '<?php get_post_type_archive_link( 'badge' ); ?>',
                                type: 'POST',
                                data: { 
                                    action:'award_action'
                                }
                            });
                        }

                        if (successes.length > 0) {
                            $('.acceptclick').hide();
                            $('#badge-error').hide();
                            $.ajax({
                                url: '<?php get_post_type_archive_link( 'badge' ); ?>',
                                type: 'POST',
                                data: { 
                                    action:'award_action'
                                }
                            });
                        }	
                    });    
                });
            });
       </script>
   
        <?php
        //Content of 'Accept Badge' page on the template page
        $content = <<<EOHTML
            <div id="bsp-award-actions-wrap">
                <div id="badgeSuccess">
                    <p>Congratulations! The "{$badge_name}" badge has been awarded to you.</p>
                    <p class="acceptclick">Please <a href='#' class='acceptclick'>accept</a> the award.</p>
                </div>
            </div>
            <div class="browserSupport">
                <p>Microsoft Internet Explorer is not supported at this time. Please use Firefox or Chrome to retrieve your award.</p>
            </div>
            <div id="badge-error">
                <p>An error occured while adding this badge to your backpack.</p>
            </div>
            </div>
            {$content}
EOHTML;
    }//end of is_page('accept-badge')   
    return $content;
}


/**
 * Include a custom template.
 */
add_filter( 'template_include', 'b4l_accept_badge_template');

/**
 * Adding the template for 'Accept Badge' page to the list of templates.
 */
function b4l_accept_badge_template( $template ) {
    //checking if the page has the slug of accept-badge
    if ( is_page( 'accept-badge' )  ) {
            //creating new template
            $new_template = locate_template( array( 'badges-accept-template.php' ) );
            //if the new tempalte is not empty then use the template
            if ( '' != $new_template ) {
                    return $new_template ;
            }
    }
    return $template;
}
