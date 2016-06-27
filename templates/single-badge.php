<?php
 /*
  * Template Name:      Single Badge
  * 
  * Description:        Template file displaying the badge's information (Description, language, image, title)
  *                     with a translation field and a 'Get a certification by mail' button.
  * Version:            1.1.3
  * Author:             Alexandre Levacher
 */
 

/**
 * Register with hook 'wp_enqueue_scripts', which can be used for front end CSS and JavaScript
 */
add_action( 'wp_enqueue_scripts', 'prefix_add_my_stylesheet' );

/**
 * Enqueue plugin style-file
 */
function prefix_add_my_stylesheet() {
    wp_register_style( 'prefix-style', WP_PLUGIN_URL.'/badges4languages-plugin/css/single_badge_template_style.css' );
    wp_enqueue_style( 'prefix-style' );
}


/*
 * Creates the page to display the page and to be certificated.
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
                
                <!-- Display badge description-->
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
                                    foreach($results1 as $result) {
                                ?>
                                    <option value="<?php echo $result[language_name]; ?>">
                                        <?php echo $result[language_name]; } ?>
                                    </option>
                                </select>
                                <br/>
                                <!-- Send your translation request -->
                                <input type="submit" value="Translate" />
                            </form>
                        </td>
                        <td>
                            <!-- Place where the translation will be displayed -->
                            <div class="entry-content"><?php echo b4l_single_badge_translation($_GET['description_translation']); ?></div>
                        </td>
                    </tr>
                </table>
                
                <!-- TAXOMONIES/CATEGORIES --> 
                <div id="taxonomies">
                    <?php
                        if(get_the_term_list( $post->ID, 'badge_studentlevels')){
                            echo '<strong>Student level: </strong>';
                            the_terms( $post->ID, 'badge_studentlevels');
                            $studentLevel = get_the_terms($post->ID, 'badge_studentlevels');
                            $levelName = $studentLevel[0]->name;
                            echo '<br/>';
                            echo '<strong>Skill(s): </strong>';
                            the_terms( $post->ID, 'badge_skills' ,  ' ' );
                        } elseif(get_the_term_list( $post->ID, 'badge_teacherlevels')){
                            echo '<strong>Teacher level: </strong>';
                            the_terms( $post->ID, 'badge_teacherlevels');
                            $teacherLevel = get_the_terms($post->ID, 'badge_teacherlevels');
                            $levelName = $teacherLevel[0]->name;
                        }
                    ?>
                </div>
                <br/>
                <br/>
                <hr/>
               
                <!-- Choose the language certification -->
                <form action="" method="post">
                    <h3>Choose the language that you want a certification :</h3>
                    <select style="width: 100px" id="language_certification" name="language_certification">
                        <?php
                            //Display all the languages possible stored in the ($wpdb->prefix)b4l_languages table. 
                            global $wpdb;
                            if(get_the_terms( $post->ID, 'badge_studentlevels' )){
                                $query = "SELECT language_name FROM ".$wpdb->prefix."b4l_languages";
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
                    <input type="submit" value="Get the certification" name="get_certification"/>
                    <?php 
                        //Displays number of certifications delivers
                        //This number is used to create unique ID for badges !!!
                        $queryNbPeople = "SELECT ".$levelName." FROM ".$wpdb->prefix."b4l_number_certifications WHERE id=1";
                        $numberOfPeople = $wpdb->get_var($queryNbPeople); 
                        if($numberOfPeople == null) {
                            $numberOfPeople = 0;
                        } 
                    ?>
                    <p><?php echo $numberOfPeople ?> persons have the certification <?php echo the_terms( $post->ID, 'badge_studentlevels') ?></p>
                </form>
                
            <?php 
                //Gets all the user's information.
                global $current_user;
                get_currentuserinfo();

                /**
                 * Recovers all the useful information for the JSON creation and sending an email
                */
                if(isset($_POST['get_certification']) && ($_POST['language_certification'] != ""))
                {
                    $numberOfPeople = $numberOfPeople+1; //Increments the number of people having the badge.
                    //
                    //Contains all the issuer information
                    $queryInfo = "SELECT * FROM ".$wpdb->prefix."b4l_issuer_information ";
                    $results = $wpdb->get_results($queryInfo, ARRAY_A); 
                    $issuer_name = $results[0]['issuer_name'];
                    $issuer_logo = $results[0]['issuer_logo'];
                    $issuer_email = $results[0]['issuer_email'];
                    $issuer_url = $results[0]['issuer_url'];
                    
                    //By default the table is empty : it checks if the table is empty to insert a line
                    //or it is not empty to update a line for the numberOfPeople
                    if($wpdb->get_row($wpdb->prepare( "SELECT * FROM ".$wpdb->prefix."b4l_number_certifications WHERE ID = 1", "" ))) {
                        $wpdb->update( $wpdb->prefix . 'b4l_number_certifications',array($levelName => $numberOfPeople),array('id' => '1'));
                    } else {
                        $wpdb->insert($wpdb->prefix . 'b4l_number_certifications',array($levelName => $numberOfPeople));
                    }
                    
                    $email_stud=$current_user->user_email;
                    $badge_name = get_the_title(); //Page title must be the name you want to give to the badge.
                    //
                    //If the certification language has a translation, we use that one. If it hasn't, we use the default one (in English).
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
                    $file_json = b4l_create_self_certification_assertion_badge_json($email_stud, $badge_image, $badge_lang, $badge_lvl, $badge_name, $badge_desc, $issuer_name, $issuer_url, $issuer_email, $numberOfPeople);
                    b4l_send_badge_email($email_stud, $badge_name, $badge_desc, $badge_image, $badge_lang, $badge_lvl, $file_json, $issuer_logo, $issuer_email);
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
<?php get_footer(); ?>


<!-- To keep the selected language in the translation and certification -->
<!-- menu after displaying the translation -->
<script type="text/javascript">
    document.getElementById('description_translation').value = "<?php echo $_GET['description_translation'];?>";
    document.getElementById('language_certification').value = "<?php echo $_GET['language_certification'];?>";
</script>


<?php
/**
 * Writes the translation depending on the language choose by the user.
 * 
 * Foreign Key doesn't work/exist on WordPress for the database,
 * so I use a "non-official method" to find information.
 */
function b4l_single_badge_translation($translation_language){
    global $wpdb;
    
    //Gives the language_id depending on the language in the menu chosen by the user
    $query1 = "SELECT language_id FROM ".$wpdb->prefix."b4l_languages WHERE language_name='".$translation_language."'";
    $value = $wpdb->get_var($query1);
    
    //Checks if it is a Student or a Teacher Level
    //Then Obtains the name (string) of the Custom Taxonomy 'Student/Teacher Level'
    //Finally makes the query with the argument $value and $levelName.
    if (get_the_terms($post->ID, 'badge_studentlevels')) {
        $studentLevel = get_the_terms($post->ID, 'badge_studentlevels');
        $levelName = $studentLevel[0]->name;
        $query2 = "SELECT ".$levelName." FROM ".$wpdb->prefix."b4l_studentLevels WHERE language='".$value."'";
    } elseif (get_the_terms($post->ID, 'badge_teacherlevels')) {
        $teacherLevel = get_the_terms($post->ID, 'badge_teacherlevels');
        $levelName = $teacherLevel[0]->name;
        $query2 = "SELECT ".$levelName." FROM ".$wpdb->prefix."b4l_teacherLevels WHERE language='".$value."'";
    }
    
    //Displays the translated description
    return $wpdb->get_var($query2);
}


/**
 * Creates the Badge Assertion for the user. Contains information about the badge (BadgeClass)
 * and the issuer of the badge (IssuerBadge).
*/ 
function b4l_create_self_certification_assertion_badge_json($email_stud, $badge_image, $badge_lang, $badge_lvl, $badge_name, $badge_desc, $issuer_name, $issuer_url, $issuer_email, $numberOfPeople){

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
            'uid'=>'b4l'.'_'.$badge_lvl.'_'.$badge_lang.'_'.$numberOfPeople, //UID must be unique, so it's thanks to $numberOfPeople
            'badge'=>array(
                    '@context'=>'https://w3id.org/openbadges/v1',
                    'type'=>'BadgeClass',
                    'name'=>$badge_name.' - '.$badge_lang.' (Self certification)',
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


/**
* Sends an email to the user to get the certification.
*/ 
function b4l_send_badge_email($email_stud, $badge_name, $badge_desc, $badge_image, $badge_lang, $badge_lvl, $file_json, $issuer_logo, $issuer_email){
    $subject = "You have just earned a badge"; //entering a subject for email
    //encoding the url
    $url = str_rot13(base64_encode(WP_PLUGIN_URL.'/badges4languages-plugin/json/'.$file_json.'.json'));
    $pagelink=esc_url( get_permalink( get_page_by_title( 'Accept Badge' ) ) );
    $badge_id = $badge_name.'-'.$badge_lang; //unique ID for the badge
            
    //Message displayed in the email
    $message= ' 
    <html>
            <head>
                    <meta http-equiv="Content-Type" content="text/html"; charset="utf-8" />
            </head>
            <body>
            <div id="b4l-award-actions-wrap">
            <div align="center">
            <img src="' . $issuer_logo . '" width="180" alt="Company Logo"> 
                    <h1>Congratulations you have just earned a badge!</h1>
                            <h2>'.$badge_name.' - '.$badge_lang.' (Self certification)</h2>
                            <a href="'.$pagelink.'?id='.$badge_id.'&filename='.$url.'">
                                <img src="'.$badge_image.'" width="150" height="150">
                            </a>
                            </br>
                            <p>Description: '.$badge_desc.'</p>
                    <h2 class="acceptclick">Click on the badge to add it to your Mozilla Backpack!</h2>
                    <div class="browserSupport"><b>Please use Firefox or Google Chrome to retrieve your badge.<b></div>
                    </div>
            </body>
    </html>
    ';

    //setting headers so it's a MIME mail and a html
    // Always set content-type when sending HTML email
    $headers = "From: Badges4languages "."<".$issuer_email.">"."\n";
    $headers .= "MIME-Version: 1.0"."\n";
    $headers .= "Content-type: text/html; charset=ISO-8859-1"."\n";
    $headers .= "Reply-To: ".$issuer_email.""."\n";

    mail($email_stud, $subject, $message, $headers); //the call of the mail function with parameters
    echo 'Email Sent. If the mail is not in your mail box, verify your spams.';
}