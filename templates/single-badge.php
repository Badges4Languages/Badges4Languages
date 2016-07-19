<?php
 /*
  * Template Name:      Single Badge
  * 
  * Description:        Template file displaying the badge's information (Description, language, image, title)
  *                     with a translation field and a 'Get a certification by mail' button.
  * Version:            1.1.0
  * Author:             Alexandre LEVACHER
 */
 

/**
 * Register with hook 'wp_enqueue_scripts', which can be used for front end CSS and JavaScript
 */
add_action( 'wp_enqueue_scripts', 'prefix_add_my_stylesheet' );

/**
 * Enqueue plugin style-file (add the CSS file)
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
 */
function prefix_add_my_stylesheet() {
    wp_register_style( 'prefix-style', WP_PLUGIN_URL.'/badges4languages-plugin/css/single_badge_template.css' );
    wp_enqueue_style( 'prefix-style' );
}


/*
 * Creates the page to display it. You have the badge's information, you can 
 * translate the description in another language if there is, and you can get
 * self-certificated.
 */

//Function b4l_single_badge_translation is in WP_PLUGIN_DIR.'/badges4languages-plugin/includes/functions_file/create_json_and_send_email.php' directory.
require WP_PLUGIN_DIR.'/badges4languages-plugin/includes/functions_file/create_json_and_send_email.php';

//Header Page
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
            <header class="entry-header" />
                
                <!-- Display featured image in right-aligned floating div -->
                <div style="float: right; margin: 10px">
                    <?php the_post_thumbnail( array( 100, 100 ) ); ?>
                </div>
 
                <!-- Display Title -->
                <h2><?php the_title(); ?></h2>
                
                <!-- Display badge description-->
                <div class="entry-content">
                    <?php echo $post->post_content; //Can use 'the_content()' if the 'while($loop....) is activated ?>
                </div> 
                <br/><hr/>
                
                <!-- Display a translation -->
                <?php
                /*
                * Get all the translated description from the DB tables.
                */
                global $wpdb;
                //Select all the languages of the DB Table '$wpdb->prefix.b4l_languages' if it is a student level/badge.
                if(get_the_terms( $post->ID, 'badge_studentlevels' )){
                    $queryLevelTeachersOrStudents = "SELECT l.language_name "
                        . "FROM ".$wpdb->prefix."b4l_languages l, ".$wpdb->prefix."b4l_studentLevels sl "
                        . "WHERE l.language_id=sl.language";
                } 
                //The same with a teacher level/badge.
                elseif(get_the_terms( $post->ID, 'badge_teacherlevels' )){
                    $queryLevelTeachersOrStudents = "SELECT l.language_name "
                        . "FROM ".$wpdb->prefix."b4l_languages l, ".$wpdb->prefix."b4l_teacherLevels sl "
                        . "WHERE l.language_id=sl.language";
                }
                $results1 = $wpdb->get_results($queryLevelTeachersOrStudents, ARRAY_A);
                
                //Checks there is at least one translation or not. If there is not, the scrollbar menu is not displayed.
                if($results1[0]){
                ?>
                    <h4>Choose a translation</h4>
                     <table>
                        <tr>
                            <td width="30%">
                                <form action="">
                                    <!-- Select the language translation into a menu -->
                                    <select style="width: 100px" id="description_translation" name="description_translation">
                                    <?php
                                        foreach($results1 as $result) {
                                    ?>
                                        <option value="<?php echo $result[language_name]; ?>">
                                            <?php echo $result[language_name]; } ?>
                                        </option>
                                    </select>
                                    <br/>
                                    <!-- Send your translation request -->
                                    <input type="submit" value="Translate" class="button-small" />
                                </form>
                            </td>
                            <td>
                                <!-- Place where the translation will be displayed -->
                                <div class="entry-content"><?php echo b4l_single_badge_translation($_GET['description_translation']); ?></div>
                            </td>
                        </tr>
                    </table>
                <?php
                }
                ?>
                
                <!-- TAXOMONIES/CATEGORIES --> 
                <div id="taxonomies">
                    <?php
                        //Get the StudentLevel and the Skills (if exist)
                        if(get_the_term_list( $post->ID, 'badge_studentlevels')){
                            echo '<strong>Student level: </strong>';
                            the_terms( $post->ID, 'badge_studentlevels');
                            $studentLevel = get_the_terms($post->ID, 'badge_studentlevels');
                            $levelName = $studentLevel[0]->name;
                            echo '<br/>';
                            echo '<strong>Skill(s): </strong>';
                            the_terms( $post->ID, 'badge_skills' ,  ' ' );
                        //Get the TeacherLevel (if exists)
                        } elseif(get_the_term_list( $post->ID, 'badge_teacherlevels')){
                            echo '<strong>Teacher level: </strong>';
                            the_terms( $post->ID, 'badge_teacherlevels');
                            $teacherLevel = get_the_terms($post->ID, 'badge_teacherlevels');
                            $levelName = $teacherLevel[0]->name;
                        }
                    ?>
                </div>
                
                <!-- CUSTOM METABOX --> 
                <div id="metabox">
                    <?php
                        $custom_metabox_links = get_post_meta(get_the_ID(), 'badge_links', true);
                        if($custom_metabox_links){
                            echo '<strong>More information: </strong>';
                            foreach($custom_metabox_links as $link) {
                                echo '<a href="'.$link[url].'">'.$link[select].'</a> ';
                            }
                        }
                    ?>
                </div>
                <br/><hr/>
               
                <!-- SEE THE SELF CERTIFICATION FORM AND SEND CERTIFICATION --> 
                <?php
                global $current_user;
                $user_roles = $current_user->roles;
                $user_role = array_shift($user_roles);
                //If it is a student badge, everybody can see it
                if ( get_the_terms($post->ID, 'badge_studentlevels') ) {
                    b4l_see_and_send_self_certification();
                } else {
                    //If it is a teacher badge, only admin, teacher, academy et badges editor (custom roles of the plugin) can see the form
                    if ( $user_role == 'administrator' || $user_role == 'b4l_academy' || $user_role == 'b4l_teacher' || user_role == 'b4l_badges_editor') {
                        b4l_see_and_send_self_certification();
                    }
                }
                ?>
                
                <?php 
                //Gets all the user's information.
                global $current_user;
                get_currentuserinfo();

                /**
                 * Recovers all the useful information for the JSON creation and sending an email
                */
                if(isset($_POST['get_certification']) && ($_POST['language_certification'] != "") && ($_POST['language_certification'] != "------------"))
                {
                    //Contains all the issuer information
                    $queryInfo = "SELECT * FROM ".$wpdb->prefix."b4l_issuer_information ";
                    $results = $wpdb->get_results($queryInfo, ARRAY_A); 
                    $issuer_name = $results[0]['issuer_name'];
                    $issuer_logo = $results[0]['issuer_logo'];
                    $issuer_email = $results[0]['issuer_email'];
                    $issuer_url = $results[0]['issuer_url'];
                    
                    //Checks if issuer information are set and valid. If not, pop-up which alerts the user that is not the case.
                    if(array_filter($results[0]) == false || strpos($issuer_logo, 'Invalid') !== false || strpos($issuer_email, 'Invalid') !== false || strpos($issuer_url, 'Invalid') !== false) {
                        ?>
                        <script>
                            alert("Can't send the certification : Badges Issuer Information not complete/valid !")
                        </script>
                        <?php
                    } else {
                        $numberOfPeople = $numberOfPeople+1; //Increments the number of people having the badge.
                        
                        //By default the table is empty : it checks if the table is empty to insert a line or it is not empty to update a line for the numberOfPeople
                        if($wpdb->get_row($wpdb->prepare( "SELECT * FROM ".$wpdb->prefix."b4l_number_certifications WHERE ID = 1", "" ))) {
                            $wpdb->update( $wpdb->prefix . 'b4l_number_certifications',array($levelName => $numberOfPeople),array('id' => '1'));
                        } else {
                            $wpdb->insert($wpdb->prefix . 'b4l_number_certifications',array($levelName => $numberOfPeople));
                        }

                        $email_stud=$current_user->user_email; //Email student is user's email.
                        $badge_name = get_the_title(); //Page title must be the name you want to give to the badge.

                        //If the certification language has a translation, we use that one. If it hasn't, we use the default one (in English).
                        //Function b4l_single_badge_translation is in WP_PLUGIN_DIR.'/badges4languages-plugin/includes/functions_file/create_json_and_send_email.php' directory.
                        for($i=0;$i<count($results1);$i++) {
                            if($_POST['language_certification']==$results1[$i][language_name]){
                                $badge_desc = b4l_single_badge_translation($_POST['language_certification']);
                            } 
                        }
                        if($badge_desc == null){
                            $badge_desc = $post->post_content;
                        }

                        //Use the Wordpress featured image as badge image
                        $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
                        $badge_image = $image[0];
                        $badge_lang = $_POST['language_certification'];

                        //Check if it is a Student badge or a Teacher badge and recuperate the value.
                        if (get_the_terms($post->ID, 'badge_studentlevels')) {
                            $studentLevel = get_the_terms($post->ID, 'badge_studentlevels');
                            $badge_lvl = $studentLevel[0]->name;
                        } elseif (get_the_terms($post->ID, 'badge_teacherlevels')) {
                            $teacherLevel = get_the_terms($post->ID, 'badge_teacherlevels');
                            $badge_lvl = $teacherLevel[0]->name;
                        }

                        //Create the JSON File and send the cerfication by email.
                        //Function b4l_create_certification_assertion_badge_json is in WP_PLUGIN_DIR.'/badges4languages-plugin/includes/functions_file/create_json_and_send_email.php' directory.
                        $file_json = b4l_create_certification_assertion_badge_json($email_stud, $badge_image, $badge_lang, $badge_lvl, $badge_name, $badge_desc, $issuer_name, $issuer_url, $issuer_email, $numberOfPeople);
                        b4l_send_badge_email($email_stud, $badge_name, $badge_desc, $badge_image, $badge_lang, $file_json, $issuer_logo, $issuer_email);
                        ?>
                        <script>
                            alert("Email Sent. If the mail is not in your mail box, verify your spams.");
                        </script>
                        <?php
                    }
                }
                
                /**
                * Displays previous and next Custom Post Link at the end of the article.
                * http://bryantwebdesign.com/code/previous-next-navigation-for-custom-post-types/
                * Possible that it is not working on every themes.
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

<!--Footer Page-->
<?php get_footer(); ?>


<!-- To keep the selected language in the translation and certification -->
<!-- menu after displaying the translation -->
<script type="text/javascript">
    document.getElementById('description_translation').value = "<?php echo $_GET['description_translation'];?>";
    document.getElementById('language_certification').value = "<?php echo $_GET['language_certification'];?>";
</script>




<?php
/**
 * Displays the section to get a certification.
 * 
 * @author Alexandre LEVACHER
 * @since 1.1.0
*/
function b4l_see_and_send_self_certification(){
    ?>
    <!-- Choose the language certification -->
    <div id="send_certification_form">
        <form action="" method="post">
            <h3>Choose the language that you want a certification</h3>
            <p>You can write the name into the scrollbar menu or look for it</p>
            <select style="width: 100px" id="language_certification" name="language_certification">
                <?php
                    //Display all the languages possible stored in the ($wpdb->prefix)b4l_languages table. 
                    global $wpdb;
                    if(get_the_terms( $post->ID, 'badge_studentlevels' ) || get_the_terms( $post->ID, 'badge_teacherlevels' )){
                        $query = "SELECT language_name FROM ".$wpdb->prefix."b4l_languages ORDER BY 
                                    (CASE 
                                        WHEN language_id = 'arb' THEN 1
                                        WHEN language_id = 'cmn' THEN 1
                                        WHEN language_id = 'deu' THEN 1
                                        WHEN language_id = 'eng' THEN 1
                                        WHEN language_id = 'fra' THEN 1 
                                        WHEN language_id = 'ita' THEN 1
                                        WHEN language_id = 'jpn' THEN 1
                                        WHEN language_id = 'por' THEN 1
                                        WHEN language_id = 'rus' THEN 1
                                        WHEN language_id = 'spa' THEN 1
                                        WHEN language_id = '---' THEN 2
                                        ELSE language_name 
                                    END)";
                    } 
                    $results2 = $wpdb->get_results($query, ARRAY_A);
                    foreach($results2 as $result) {
                ?>
                    <option value="<?php echo $result[language_name]; ?>">
                        <?php echo $result[language_name]; } ?>
                    <option value="<?php echo $result[language_name]; ?>">
            </select>
            <br/>

            <!-- Send an email to get the certification -->

            <input type="submit" value="Get the certification" name="get_certification" class="button button-primary"/>
            <?php 
                //Displays number of certifications delivers
                //This number is used to create unique ID for badges !!!
                $queryNbPeople = "SELECT ".$levelName." FROM ".$wpdb->prefix."b4l_number_certifications WHERE id=1";
                $numberOfPeople = $wpdb->get_var($queryNbPeople); 

                //Table empty at the initialization of the plugin, so if it's null we remplace by 0
                if($numberOfPeople == null) {
                    $numberOfPeople = 0;
                } 
            ?>
            <p><?php echo $numberOfPeople ?> persons have the certification <?php echo the_terms( $post->ID, 'badge_studentlevels') ?></p>
        </form>
    </div>
    <?php
}