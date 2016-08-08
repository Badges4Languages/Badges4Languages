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
get_header(); ?>
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
                    <strong>Teacher: </strong> <?php the_author_meta( 'display_name', $post->post_author ); ?> <br/>
                    <strong>Language: </strong> <?php echo get_post_meta(get_the_ID(), 'class_language', true); ?> <br/>
                    <strong>Level: </strong> <?php echo get_post_meta(get_the_ID(), 'class_level', true); ?> <br/>
                    <strong>Starting Date: </strong> <?php echo get_post_meta(get_the_ID(), 'class_starting_date', true); ?> <br/>
                    <strong>Ending Date: </strong> <?php echo get_post_meta(get_the_ID(), 'class_ending_date', true); ?> <br/>
                </div>
                <br/>
            <!-- Display comments and the comments' form -->    
            <?php $withcomments = "1"; comments_template(); ?>
                
        </article>
    <?php //endwhile; ?>
    </div>
</div>

<?php wp_reset_query(); ?>

<!--Footer Page-->
<?php get_footer(); ?>

