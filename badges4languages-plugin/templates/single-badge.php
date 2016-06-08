<?php
 /*
  * Template Name: Single Badge
  * 
  * Description:       Template file displaying the badge's information (English description, image, title)
  *                    with a translation field.
  * Version:           1.0.1
  * Author:            Alexandre Levacher
 */
 
get_header(); ?>
<div id="primary">
    <div id="content" role="main">
    <?php
    $mypost = array( 'post_type' => 'badge', );
    $loop = new WP_Query( $mypost );
    ?>
        
    <!--Decomment this line and 'endwhile' at the end of this code 
    if you want to display all the Custom Post on the same page -->
    <?php //while ( $loop->have_posts() ) : $loop->the_post();?>
    
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                
                <!-- Display featured image in right-aligned floating div -->
                <div style="float: right; margin: 10px">
                    <?php the_post_thumbnail( array( 100, 100 ) ); ?>
                </div>
 
                <!-- Display Title -->
                <h2><?php the_title(); ?></h2>
 
                <!-- Display badge review contents -->
                <div class="entry-content">
                    <?php echo $post->post_content; //Can use 'the_content()' if the 'while($loop....) is activated ?>
                </div> 
                
                <!-- Display a translation -->
                <h3>Choose a translation</h3>
                 <table>
                    <tr>
                        <td width="30%">
                            <form action="">
                                <!-- Select the language translation into a menu -->
                                <select style="width: 100px" id="description_translation" name="description_translation">
                                <?php
                                    /*
                                     *Display all the languages which have a translation description.
                                     */
                                    global $wpdb;
                                    //Select all the languages of the DB Table '$wpdb->prefix.b4l_languages'
                                    //if it is a student level/badge.
                                    if(get_the_terms( $post->ID, 'badge_studentlevels' )){
                                        $query = "SELECT l.language_name "
                                            . "FROM ".$wpdb->prefix."b4l_languages l, ".$wpdb->prefix."b4l_studentLevels sl "
                                            . "WHERE l.language_id=sl.language";
                                    } 
                                    //The same with a teacher level/badge.
                                    elseif(get_the_terms( $post->ID, 'badge_teacherlevels' )){
                                        $query = "SELECT l.language_name "
                                            . "FROM ".$wpdb->prefix."b4l_languages l, ".$wpdb->prefix."b4l_teacherLevels sl "
                                            . "WHERE l.language_id=sl.language";
                                    }
                                    $results = $wpdb->get_results($query, ARRAY_A);
                                    foreach($results as $result) {
                                ?>
                                    <option value="<?php echo $result[language_name]; ?>">
                                        <?php echo $result[language_name]; } ?>
                                    </option>
                                </select>
                                <!-- Send your translation request -->
                                <input type="submit" value="Translate" />
                            </form>
                        </td>
                        <td>
                            <!-- Place where the translation will be displayed -->
                            <div class="entry-content"><?php b4l_single_badge_translation(); ?></div>
                        </td>
                    </tr>
                </table>
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
                
                <?php 
                /**
                * Displays previous and next Custom Post Link at the end of the article.
                * http://bryantwebdesign.com/code/previous-next-navigation-for-custom-post-types/
                */
                if( is_singular('badge') ) { ?>
                    <div class="post-nav">
                    <div class="alignleft prev-next-post-nav"><?php previous_post_link( '&laquo; %link' ) ?></div>
                    <div class="alignright prev-next-post-nav"><?php next_post_link( '%link &raquo;' ) ?></div>
                    </div>
                <?php } ?>

        </article>
    <?php //endwhile; ?>
    </div>
</div>
<?php wp_reset_query(); ?>
<?php get_footer(); ?>


<!-- To keep the selected language in the translation menu after displaying the translation -->
<script type="text/javascript">
    document.getElementById('description_translation').value = "<?php echo $_GET['description_translation'];?>";
</script>


<?php
/**
 * Writes the translation depending on the language choose by the user.
 * 
 * Foreign Key doesn't work/exist on WordPress for the database,
 * so I use a "non-official method" to find information.
 */
function b4l_single_badge_translation(){
    global $wpdb;

    //Gives the language_id depending on the language in the menu chosen by the user
    $query1 = "SELECT language_id FROM ".$wpdb->prefix."b4l_languages WHERE language_name='".$_GET['description_translation']."'";
    $value = $wpdb->get_var($query1);
    
    //Checks if it is a Student or a Teacher Level
    //Then Obtains the name (string) of the Custom Taxonomy 'Student/Teacher Level'
    //Finally makes the query with the argument $value and $levelName.
    if ($studentLevel = get_the_terms($post->ID, 'badge_studentlevels')) {
        $levelName = $studentLevel[0]->name;
        $query2 = "SELECT ".$levelName." FROM ".$wpdb->prefix."b4l_studentlevels WHERE language='".$value."'";
    } elseif ($teacherLevel = get_the_terms($post->ID, 'badge_teacherlevels')) {
        $levelName = $teacherLevel[0]->name;
        $query2 = "SELECT ".$levelName." FROM ".$wpdb->prefix."b4l_teacherLevels WHERE language='".$value."'";
    }
    
    //Displays the translated description
    echo $wpdb->get_var($query2);
}


?>
