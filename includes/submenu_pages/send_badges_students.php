<?php
 /*
  * Create a submenu padge in the administration menu to allow a teacher to send badges to students.
  * 
  * @author Alexandre Levacher
  * @package badges4languages-plugin
  * @subpackage includes/submenu_pages
  * @since 1.0.0
 */


/**
 * Adds b4l_send_badges_students_submenu_page to the admin menu.
 */
add_action('admin_menu', 'b4l_send_badges_students_submenu_page');
 
/**
 * Creates the submenu page.
 * 
 * The capability allows superadmin, admin, editor and author to see this submenu.
 * If you change to change the permissions, use manage_options as capability (for
 * superadmin and admin).
 */
function b4l_send_badges_students_submenu_page() {
        
    add_submenu_page(
        'edit.php?post_type=badge',
        'Send Badges To Students',
        'Send Badges To Students',
        'b4l_send_badges_to_students', //capability: 'edit_posts' to give automatically the access to author/editor/admin
        'send-badges-students-submenu-page',
        'b4l_send_badges_students_page_callback' 
    );
}
 
if ( function_exists( 'members_plugin_init' ) ) {
	add_filter( 'plugin_name_capability', 'plugin_name_unique_capability' );
}

function plugin_name_unique_capability( $cap ) {
	return 'b4l_send_badges_to_students';
}

if ( function_exists( 'members_get_capabilities' ) ) {
	add_filter( 'members_get_capabilities', 'plugin_name_extra_caps' );
}

function plugin_name_extra_caps( $caps ) {
	$caps[] = 'b4l_send_badges_to_students';
	return $caps;
}

/**
 * Displays the content of the submenu page
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
 * @global WordpressObject $wpdb Wordpress Database
 * @global WordpressObject $current_user Information about the current user
 */
function b4l_send_badges_students_page_callback() {
    global $current_user;
    get_currentuserinfo();
?>
    <div>
        <form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
            <!-- Display all the levels -->
            <h2>Choose the level</h2>
            <div>
            <?php 
                $mypost = b4l_send_badges_students_get_posts();
                $loop = new WP_Query( $mypost );
                while ( $loop->have_posts() ) : $loop->the_post();
            ?>
                <input type="radio" name="level" value="<?php echo the_ID(); ?>"><?php echo the_title(); ?><br/>
                <?php endwhile; ?>
            </div>
            <br/>

            <!-- Display all the languages into the database -->
            <h2>Choose the language</h2>
            <div>
                <select style="width: 200px" id="language_certification" name="language_certification">
                    <?php
                        //Display all the languages possible stored in the ($wpdb->prefix)b4l_languages table. 
                        global $wpdb;
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
                                        WHEN language_id = 'vlc' THEN 1
                                        WHEN language_id = '---' THEN 2
                                        ELSE language_name 
                                    END)";
                        $resultsLang = $wpdb->get_results($query, ARRAY_A);
                        foreach($resultsLang as $result) {
                    ?>
                        <option value="<?php echo $result[language_name]; ?>">
                            <?php echo $result[language_name]; } ?>
                        <option value="<?php echo $result[language_name]; ?>">
                </select>
                <br/>
            </div>
            <br/>

            <!-- Send emails to students -->
            <h2>Write the student's email</h2>
            <div>
                <!--<input type="text" name="students_emails"><br>
                <!-- NOT FUNCTIONNABLE : send badge to more than one student-->
                <p>Write an email an each line. Don't use dot or something else at the end of the line.</p>
                <textarea id="students_emails" name="students_emails" rows="8" cols="50"></textarea>
                
            </div>
            <br/>
            
            <h2>Write a comment about the certification</h2>
            <div>
                <input type="text" name="student_comment"><br>
            </div>
            <br/>
            
            <h2>Select your class associated to this certification</h2>
            <div>
               <select style="width: 200px" id="teacher_class" name="teacher_class">
                    <?php
                        $mypost = array( 'post_type' => 'class' );
                        $loop = new WP_Query( $mypost );
                        while ( $loop->have_posts() ) : $loop->the_post();
                            if(get_the_author_meta( 'display_name' ) == $current_user->display_name) {
                                echo '<option value="'.get_the_ID().'">'.get_the_title().' ('.get_post_meta( get_the_ID(), 'class_language', true ).' - '.get_post_meta( get_the_ID(), 'class_level', true ).')</option>';
                            }
                        endwhile;
                    ?>
                </select>
            </div>
            <br/>
            
            <input name="send_emails_button" type="submit" class="button-primary" value="Send emails" />
         </form>


<?php
    //Level, email, and language have to be set to send a certification
    if($_POST["send_emails_button"] && isset($_POST["level"]) && !empty($_POST["language_certification"]) && ($_POST["language_certification"] != "------------") && !empty($_POST["students_emails"]) && isset($_POST["teacher_class"])) {
        
        //Contains all the issuer information
        $queryInfo = "SELECT * FROM ".$wpdb->prefix."b4l_issuer_information ";
        $issuerInformation = $wpdb->get_results($queryInfo, ARRAY_A); 
        
        //Issuer (firm, company, etc.) information
        $issuer_name = $issuerInformation[0]['issuer_name'];
        $issuer_logo = $issuerInformation[0]['issuer_logo'];
        $issuer_email = $issuerInformation[0]['issuer_email'];
        $issuer_url = $issuerInformation[0]['issuer_url'];
        
        //Checks if issuer information are set and valid. If not, pop-up which alerts the user that is not the case.
        if(array_filter($issuerInformation[0]) == false || strpos($issuer_logo, 'Invalid') !== false || strpos($issuer_email, 'Invalid') !== false || strpos($issuer_url, 'Invalid') !== false) {
            ?>
            <script>
                alert("Can't send the certification : Badges Issuer Information not complete/valid !")
            </script>
            <?php
        } else {
            //'Badge' class
            require WP_PLUGIN_DIR.'/badges4languages-plugin/includes/classes/badge.php';

            //Current user name, that is to say name of the teacher
            $teacher_user_name = $current_user->user_login;

            //Get all the post's info into an array $post
            $post = get_post($_POST["level"]);
            $title = $post->post_title;

            //If the certification language has a translation, we use that one. If it hasn't, we use the default one (in English).
            //Function b4l_single_badge_translation is in WP_PLUGIN_DIR.'/badges4languages-plugin/includes/functions_file/create_json_and_send_email.php' directory.
            require WP_PLUGIN_DIR.'/badges4languages-plugin/includes/functions_file/create_json_and_send_email.php';
            for($i=0;$i<count($resultsLang);$i++) {
                if($_POST['language_certification']==$resultsLang[$i][language_name]){
                    $badge_desc = b4l_single_badge_translation($_POST['language_certification']);
                } 
            }
            if($badge_desc == null){
                $badge_desc = $post->post_content;
            }

            //Check if it is a Student badge or a Teacher badge and recuperate the value.
            $studentLevel = get_the_terms($post->ID, 'badges_students_levels');
            $badge_lvl = $studentLevel[0]->name;

            //Use the Wordpress featured image as badge image
            $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );

            //Get the comment from the InputField  and check his value
            if($_POST['user_comment'] != null){
                $badge_comment = $_POST['student_comment'];
            } else {
                $badge_comment = "";
            }
            
            //Use the skills of the badge as tags
            $skills = get_the_terms( $_POST["level"], 'badges_skills'); //Get all the skills (Wordpress taxonomy)
            $skillsList= array();
            foreach($skills as $skill) {
                array_push($skillsList, $badge_tags.$skill->name); //Put all the 'skills' name (String) into an array
            }
            
            //Get URL of the class. Will be used on "Accept-badge" page, to give a link to student to rate the class.
            $class_link=esc_url( get_permalink( $_POST["teacher_class"] ) );
            
            //Creation of a new 'Badge' object to store all the information
            $badge = new Badge($title, $badge_desc, $image[0], $_POST['language_certification'], $badge_lvl, 'Student', $badge_comment, $skillsList, get_permalink($_POST["level"], $class_link));

            //Get the emails from the textarea which are separated by a line break (\n)
            $emails = trim($_POST['students_emails']);
            $emailsArray = explode("\n", $emails);
            $emailsArray = array_filter($emailsArray, 'trim');

            foreach($emailsArray as $email){
                $nombreEmails = $nombreEmails + 1;
                //Function b4l_single_badge_translation is in WP_PLUGIN_DIR.'/badges4languages-plugin/includes/functions_file/create_json_and_send_email.php' directory.
                $file_json = b4l_create_certification_assertion_badge_json($email, $badge, $issuerInformation[0], $teacher_user_name);
                b4l_send_badge_email($email, $badge, $file_json, $issuerInformation[0]); 
                
                //Associate the student (with his email) to a teacher's class. Thanks to this association
                //the student could give a rating to this class.
                if(!($wpdb->get_row($wpdb->prepare( "SELECT * FROM ".$wpdb->prefix."b4l_classes_students WHERE student_email= ".$email." AND id_class = ".$_POST["teacher_class"]."", "" )))) {
                    $lastid = $wpdb->get_var("SELECT id FROM ".$wpdb->prefix."b4l_classes_students ORDER BY ID DESC LIMIT 0 , 1" );
                    $wpdb->insert(
                                $wpdb->prefix . 'b4l_classes_students',
                                array(
                                    'id' => $lastid + 1,
                                    'id_class' => $_POST["teacher_class"],
                                    'student_email' => $email
                                )
                            );
                }
            }
            echo 'Number of emails : '.$nombreEmails;
        }
    }?>
    </div>
<?php
}


/**
 * Get all the custom posts 'badge' which belongs to the custom taxonomy (category)
 * 'badge_studentlevels', that is to say posts into category 'A1', 'A2',...., 'C2'.
 * 
 * @return post Custom Post which has the taxonomy 'Student Level'
 */
function b4l_send_badges_students_get_posts() {
    
    //Define the taxonomy used
    $taxonomy = 'badges_students_levels';
    
    //Get all the taxonomy terms of $taxonomy ('A1', 'A2',... 'C2')
    $taxonomyTerms = get_terms( array( 
                            'taxonomy' => $taxonomy,
                            'parent'   => 0
                        ) );
    for($i=0;$i<count($taxonomyTerms);$i++) {
        $taxonomyNameTerms[$i] = $taxonomyTerms[$i]->name;
    }
    
    //Get all the custom posts 'badge' with category 'A1', 'A2,.... 'C2'
    $mypost = array( 'post_type' => 'badge', 
                    'tax_query' => array(
                                        array(
                                                'taxonomy' => $taxonomy,
                                                'field'    => 'name',
                                                'terms'    => $taxonomyNameTerms,
                                        ),
                                    ), 
                );
    return $mypost;
}
