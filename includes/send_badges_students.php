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
    $args = array(
    'post_type' => 'portfolio',
    'title_li'  => __( 'Portfolio', 'textdomain' )
);
wp_list_pages( $args ); 
   ?>
<form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    <h2>Choose the level</h2>
        <div>
            <input type="checkbox" name="level" value="A1">A1<br/>
            <input type="checkbox" name="level" value="A2">A2<br/>
            <input type="checkbox" name="level" value="B1">B1<br/>
            <input type="checkbox" name="level" value="B2">B2<br/>
            <input type="checkbox" name="level" value="C1">C1<br/>
            <input type="checkbox" name="level" value="C2">C2
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
    <textarea id="students_emails" name="students_emails" rows="8" cols="50"></textarea>
    <br/>
    <br/>
    
    <input name="send_emails_button" type="submit" class="button-primary" value="Send emails" />
</form>


<?php
    if($_POST["send_emails_button"]) {
        $emails = trim($_POST['students_emails']);
        $emailsArray = explode("\n", $emails);
        $emailsArray = array_filter($emailsArray, 'trim');
        print_r($emailsArray);
        foreach($emailsArray as $email){
            //Contains all the issuer information
            $queryInfo = "SELECT * FROM ".$wpdb->prefix."b4l_issuer_information ";
            $results = $wpdb->get_results($queryInfo, ARRAY_A); 
            $issuer_name = $results[0]['issuer_name'];
            $issuer_logo = $results[0]['issuer_logo'];
            $issuer_email = $results[0]['issuer_email'];
            $issuer_url = $results[0]['issuer_url'];

            $badge_name = $_POST["level"].' - '.$_POST["language_certification"];
            //
            //If the certification language has a translation, we use that one. If it hasn't, we use the default one (in English).
            for($i=0;$i<count($resultsLang);$i++) {
                if($_POST['language_certification']==$resultsLang[$i][language_name]){
                    $badge_desc = b4l_single_badge_translation($_POST['language_certification']);
                } 
            }
            if($badge_desc == null){
                $badge_desc = $post->post_content;
            }

            //Use the Wordpress featured image as badge image
            $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
            $badge_image = $image[0];
            
            //Create the JSON File and send the cerfication by email.
            $file_json = b4l_create_assertion_badge_json($email, $badge_image, $_POST["language_certification"], $_POST["level"], $badge_name, $badge_desc, $issuer_name, $issuer_url, $issuer_email); 
            b4l_send_badge_email($email, $badge_name, $badge_desc, $badge_image, $_POST["language_certification"], $_POST["level"], $file_json, $issuer_logo, $issuer_email);
            
        }
    }
}



function b4l_create_assertion_badge_json($email_stud, $badge_image, $badge_lang, $badge_lvl, $badge_name, $badge_desc, $issuer_name, $issuer_url, $issuer_email){

    //adding a salt to our hashed email
    $salt=uniqid(mt_rand(), true);

    //using sha256 hash metod (open badges api defined)
    $hash='sha256$' . hash('sha256', $email_stud. $salt);

    //setting the current date
    $date=date('Y-m-d');

    //name of the json file
    $file_json=str_rot13(preg_replace("/ /", "_", $email_stud)).'_'.$badge_lvl.'_'.$badge_lang;

    //getting the dir path of the plugin to use
    $dir_path=plugin_dir_path( __FILE__ ).'../';

    //adding the folder json and encoded file name and addind the extenson of json
    $path_json=$dir_path.'json/'.$file_json.'.json';
    
    //handle for opening or creating the file and writing to it (w)
    $handle=fopen($path_json, 'w') or die ('Can not open file: '.$file_json);
    if($handle){
        //Creating of the Badge Assertion (Mozilla Open Badges API)
        $data=array(
            'recipient'=> $hash,
            'salt'=>$salt,
            '@context'=>'https://w3id.org/openbadges/v1',
            'type'=>'Assertion',
            'uid'=>'b4l'.'_'.$badge_lvl.'_'.$badge_lang.'_'.uniqid('', true), //UID must be unique
            'badge'=>array(
                    '@context'=>'https://w3id.org/openbadges/v1',
                    'type'=>'BadgeClass',
                    'name'=>$badge_name.' - '.$badge_lang,
                    'description'=>$badge_desc,
                    'image'=>$badge_image,
                    'criteria'=>'http://about.badges4languages.org/',
                    'issuer'=>array(
                            'type'=>'Issuer',
                            'name'=>$issuer_name,
                            'origin'=>$issuer_url,
                            'email'=>$issuer_email,
                    )
            ),
            'verify'=>array(
                    'type'=>'hosted',
                    'url'=>WP_PLUGIN_URL.'/badges4languages-plugin/json/'.$file_json.'.json',
            ),
            'issued_on'=>$date
        );
    }
    fwrite($handle, json_encode($data));
    fclose($handle);
    return $file_json;
}



?>
