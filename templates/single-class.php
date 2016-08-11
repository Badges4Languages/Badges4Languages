<?php
 /*
  * Template Name:      Single Class
  * 
  * Description:        Template file displaying the class's information (Description, language, image, title, level)
  *                     and can be commented.
  * Version:            1.1.3
  * Author:             Alexandre LEVACHER
 */



/**
 * Register with hook 'wp_enqueue_scripts', which can be used for front end CSS and jQuery
 */
add_action( 'wp_enqueue_scripts', 'b4l_stylesheet_single_badge' );

/**
 * Enqueue plugin style-file (add the CSS file)
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
 */
function b4l_stylesheet_single_badge() {
    wp_register_style( 'prefix-style', WP_PLUGIN_URL.'/badges4languages-plugin/css/single_badge_template.css' );
    wp_enqueue_style( 'prefix-style' );
}

/*
 * Creates the page to display it. You have the badge's information, you can 
 * translate the description in another language if there is, and you can get
 * self-certificated.
 */

//Header Page
get_header(); 
global $wpdb;
global $current_user;
get_currentuserinfo();
//Contain functions to display badges/classes
require WP_PLUGIN_DIR.'/badges4languages-plugin/includes/functions_file/display_badges_and_classes_user_profile.php';
?>
<div id="primary">
    <div id="content" role="main">
    
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header" />
                
                <!-- Display featured image in right-aligned floating div -->
                <div style="float: right; margin: 10px">
                    <?php the_post_thumbnail( array( 100, 100 ) ); ?>
                </div>
 
                <!-- Display Title -->
                <h2><?php the_title(); ?></h2>
                
                <!-- Display badge description-->
                <div class="entry-content">
                    <?php echo $post->post_content; ?>
                </div> 
                <br/><hr/>
                
                <!-- CUSTOM METABOX --> 
                <div id="metabox">
                    
                    <strong>Rating: </strong> <?php echo b4l_rating_average(get_the_ID()); ?> <br/>
                    <strong>Teacher: </strong> <?php the_author_meta( 'display_name', $post->post_author ); ?> <br/>
                    
                    <?php if ( get_post_meta(get_the_ID(), 'class_language', true) ) : ?>
                        <strong>Language: </strong> <?php echo get_post_meta(get_the_ID(), 'class_language', true); ?> <br/>
                    <?php endif; ?>
                        
                    <?php if ( get_post_meta(get_the_ID(), 'class_level', true) ) : ?>
                        <strong>Level: </strong> <?php echo get_post_meta(get_the_ID(), 'class_level', true); ?> <br/>
                    <?php endif; ?>

                    <?php if ( get_post_meta(get_the_ID(), 'class_starting_date', true) ) : ?>
                        <strong>Starting Date: </strong> <?php echo get_post_meta(get_the_ID(), 'class_starting_date', true); ?> <br/>
                    <?php endif; ?>

                    <?php if ( get_post_meta(get_the_ID(), 'class_ending_date', true) ) : ?>
                        <strong>Ending Date: </strong> <?php echo get_post_meta(get_the_ID(), 'class_ending_date', true); ?> <br/>
                    <?php endif; ?>
                        
                </div>
                <br/>
                
            <!-- Display comments and the comments' form -->  
            <?php 
            
            //Check if the user follows this class (null if not)
            $follower = $wpdb->get_results($wpdb->prepare( "SELECT * FROM ".$wpdb->prefix."b4l_classes_students WHERE student_email= '".$current_user->user_email."' AND id_class = '".get_the_ID()."'", "" )); 
            //Check if the user has already posted a comment (null if not)
            $user_comment = get_comments(array('user_id' => $current_user->ID, 'post_id'=>get_the_ID())); 
            
            //If the student doesn't follow this class or has already posted a rating, he can only see the comments....
            if(!($follower) || $user_comment) {
                echo '<p>Comments:</p>';
                $comments = get_comments(array('post_id' => get_the_ID()));
                foreach ( $comments as $comment ) {
                    echo '<div><b>Title</b> : '. get_comment_meta( $comment->comment_ID, 'title', true ) .'<br/>';
                    echo '<b>Author</b> : '. $comment->comment_author .'<br/>';
                    echo '<b>Ratings</b> : ' . get_comment_meta( $comment->comment_ID, 'rating', true ) .'/5<br/>';
                    echo '<b>Message</b> : ' . $comment->comment_content .'<br/></div><br/>';
                }
            //....Else he has access to comments + comment form to give a rating
            } else {
                $withcomments = "1";
                comments_template();
                
                
            } 
            ?>
        </article>
    <?php //endwhile; ?>
    </div>
</div>

<?php wp_reset_query(); ?>

<!--Footer Page-->
<?php get_footer(); ?>
