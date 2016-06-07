<?php
 /*
  * Template Name: Single Badge
 */
 
get_header(); ?>
<div id="primary">
    <div id="content" role="main">
    <?php
    $mypost = array( 'post_type' => 'badge', );
    $loop = new WP_Query( $mypost );
    ?>
    <?php while ( $loop->have_posts() ) : $loop->the_post();?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
 
                <!-- Display featured image in right-aligned floating div -->
                <div style="float: right; margin: 10px">
                    <?php the_post_thumbnail( array( 100, 100 ) ); ?>
                </div>
 
                <!-- Display Title -->
                <h2><?php the_title(); ?></h2>
 
                <!-- Display badge review contents -->
                <div class="entry-content"><?php the_content(); ?></div>
                
                <!-- Display a translation -->
                <div class="entry-content"><?php b4l_single_badge_translation(); ?></div>
            
                <!-- TAXOMONIE/CATEGORIE -->                   
                <strong>Student level: </strong>
                <?php the_terms( $post->ID, 'badge_studentlevels' ,  ' ' ); ?>
                <br/>
                <strong>Teacher level: </strong>
                <?php the_terms( $post->ID, 'badge_teacherlevels' ,  ' ' ); ?>
                <br/>
                <strong>Skill(s): </strong>
                <?php the_terms( $post->ID, 'badge_skills' ,  ' ' ); ?>
                <br />
                
        </article>
 
    <?php endwhile; ?>
    </div>
</div>
<?php wp_reset_query(); ?>
<?php get_footer(); ?>


<?php
function b4l_single_badge_translation(){
    global $wpdb;
    echo $wpdb->get_results( 'SELECT A1 FROM $wpdb->prefix'.b4l_studentLevels.' WHERE language="French"', output_type );
}

$sql = "SELECT * FROM wp_reminders WHERE reminder LIKE '$today'";
$results = $wpdb->get_results($sql) or die(mysql_error());

    foreach( $results as $result ) {

        echo $result->name;

    }