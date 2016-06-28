<?php
 /*
  * Description:        Create a submenu padge in the administration menu to 
  *                     allow a teacher to send badges to students.
  * Version:            1.0.0
  * Author:             Alexandre Levacher
 */


/**
 * Adds b4l_send_badges_students_submenu_page to the admin menu.
 */
add_action('admin_menu', 'b4l_send_badges_students_submenu_page');
 
/**
 * Creates the submenu page.
 */
function b4l_send_badges_students_submenu_page() {
    add_submenu_page(
        'edit.php?post_type=badge',
        'Send Badges To Students',
        'Send Badges To Students',
        'manage_options',
        'send-badges-students-submenu-page',
        'b4l_send_badges_students_page_callback' 
    );
}
 

/**
 * Displays the content of the submenu page
 */
function b4l_send_badges_students_page_callback() {
   ?>
<div>
    <form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <h2>Choose the level</h2>
        <div>
        <?php 
            $taxonomy = 'badge_studentlevels';
            $taxonomyTerms = get_terms( array( 
                                    'taxonomy' => $taxonomy,
                                    'parent'   => 0
                                ) );
            for($i=0;$i<count($taxonomyTerms);$i++) {
                $taxonomyNameTerms[$i] = $taxonomyTerms[$i]->name;
            }
            $mypost = array( 'post_type' => 'badge', 
                            'tax_query' => array(
                                                array(
                                                        'taxonomy' => $taxonomy,
                                                        'field'    => 'name',
                                                        'terms'    => $taxonomyNameTerms,
                                                ),
                                            ), 
                        );
            $loop = new WP_Query( $mypost );
            while ( $loop->have_posts() ) : $loop->the_post();
        ?>
                <input type="checkbox" name="level" value="<?php echo the_ID(); ?>"><?php echo the_title(); ?><br/>
            <?php endwhile; ?>
        </div>
    <br/>

        <h2>Choose the language</h2>
        <select style="width: 100px" id="language_certification" name="language_certification">
            <?php
                //Display all the languages possible stored in the ($wpdb->prefix)b4l_languages table. 
                global $wpdb;
                $query = "SELECT language_name FROM ".$wpdb->prefix."b4l_languages";
                $resultsLang = $wpdb->get_results($query, ARRAY_A);
                foreach($resultsLang as $result) {
            ?>
                <option value="<?php echo $result[language_name]; ?>">
                    <?php echo $result[language_name]; } ?>
                <option value="<?php echo $result[language_name]; ?>">
        </select>
        <br/>

        <h2>Write the students' emails</h2>
        <p>Write an email an each line. Don't use dot or something else at the end of the line.</p>
        <textarea id="students_emails" name="students_emails" rows="8" cols="50"></textarea>
        <br/>
        <br/>

        <input name="send_emails_button" type="submit" class="button-primary" value="Send emails" />
    </form>


<?php

    if($_POST["send_emails_button"]) {

        $post = get_post($_POST["level"]);
        
        //Contains all the issuer information
        $queryInfo = "SELECT * FROM ".$wpdb->prefix."b4l_issuer_information ";
        $results = $wpdb->get_results($queryInfo, ARRAY_A); 
        
        $issuer_name = $results[0]['issuer_name'];
        $issuer_logo = $results[0]['issuer_logo'];
        $issuer_email = $results[0]['issuer_email'];
        $issuer_url = $results[0]['issuer_url'];

        $badge_name = $post->post_title;
        
        //If the certification language has a translation, we use that one. If it hasn't, we use the default one (in English).
        //Function b4l_single_badge_translation is in WP_PLUGIN_DIR.'/badges4languages-plugin/includes/create_json_and_send_email.php' directory.
        require WP_PLUGIN_DIR.'/badges4languages-plugin/includes/create_json_and_send_email.php';
        for($i=0;$i<count($resultsLang);$i++) {
            if($_POST['language_certification']==$resultsLang[$i][language_name]){
                $badge_desc = b4l_single_badge_translation($_POST['language_certification']);
            } 
        }
        if($badge_desc == null){
            $badge_desc = $post->post_content;
        }
        
        //Check if it is a Student badge or a Teacher badge and recuperate the value.
        $studentLevel = get_the_terms($post->ID, 'badge_studentlevels');
        $badge_lvl = $studentLevel[0]->name;
        
        //Use the Wordpress featured image as badge image
        $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
        $badge_image = $image[0];
        
        //Get the emails from the textarea which are separated by a line break (\n)
        $emails = trim($_POST['students_emails']);
        $emailsArray = explode("\n", $emails);
        $emailsArray = array_filter($emailsArray, 'trim');

        foreach($emailsArray as $email){
            //Function b4l_single_badge_translation is in WP_PLUGIN_DIR.'/badges4languages-plugin/includes/create_json_and_send_email.php' directory.
            $file_json = b4l_create_assertion_badge_given_by_teacher_json($email, $badge_image, $_POST["language_certification"], $badge_lvl, $badge_name, $badge_desc, $issuer_name, $issuer_url, $issuer_email, $numberOfPeople);
            b4l_send_badge_email($email, $badge_name, $badge_desc, $badge_image, $_POST["language_certification"], $file_json, $issuer_logo, $issuer_email); 
        }
    }?>
    </div>
<?php
}