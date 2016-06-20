<?php
 /*
  * Template Name: Single Badge
  * 
  * Description:       Template file displaying the badge's information (English description, image, title)
  *                    with a translation field.
  * Version:           1.1.0
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
                                    $results = $wpdb->get_results($queryLevelTeachersOrStudents, ARRAY_A);
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
                            <div class="entry-content"><?php echo b4l_single_badge_translation($_GET['description_translation']); ?></div>
                        </td>
                    </tr>
                </table>
                
                <!-- TAXOMONIE/CATEGORIE --> 
                <?php
                if(get_the_term_list( $post->ID, 'badge_studentlevels')){
                    echo '<strong>Student level: </strong>';
                    the_terms( $post->ID, 'badge_studentlevels');
                    $studentLevel = get_the_terms($post->ID, 'badge_studentlevels');
                    $levelName = $studentLevel[0]->name;
                } elseif(get_the_term_list( $post->ID, 'badge_teacherlevels')){
                    echo '<strong>Teacher level: </strong>';
                    the_terms( $post->ID, 'badge_teacherlevels');
                    $teacherLevel = get_the_terms($post->ID, 'badge_teacherlevels');
                    $levelName = $teacherLevel[0]->name;
                }
                ?>
                <br/>
                <strong>Skill(s): </strong>
                <?php the_terms( $post->ID, 'badge_skills' ,  ' ' ); ?>
                <br />
               
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
                            $results = $wpdb->get_results($query, ARRAY_A);
                            foreach($results as $result) {
                        ?>
                            <option value="<?php echo $result[language_name]; ?>">
                                <?php echo $result[language_name]; } ?>
                            <option value="<?php echo $result[language_name]; ?>">
                    </select>
                    <input type="submit" value="Get the certification" name="get_certification"/>
                    <?php 
                        //Displays number of certifications delivers
                        $queryNbPeople = "SELECT ".$levelName." FROM ".$wpdb->prefix."b4l_number_certifications WHERE id=1";
                        $numberOfPeople = $wpdb->get_var($queryNbPeople); 
                        if($numberOfPeople == null) {
                            $numberOfPeople = 0;
                        } 
                    ?>
                    <p><?php echo $numberOfPeople ?> persons have the certification <?php echo the_terms( $post->ID, 'badge_studentlevels') ?></p>
                </form>
                
                

            <!-- Send an email to get the certification -->
            <?php 
            global $current_user;
            get_currentuserinfo();

            if(isset($_POST['get_certification']) && ($_POST['language_certification'] != ""))
            {
                /**
                * Recovers all the useful information for the JSON creation and sending an email
                */
                $numberOfPeople = $numberOfPeople+1;
                if($wpdb->get_row($wpdb->prepare( "SELECT * FROM ".$wpdb->prefix."b4l_number_certifications WHERE ID = 1", "" ))) {
                    $wpdb->update( $wpdb->prefix . 'b4l_number_certifications',array($levelName => $numberOfPeople),array('id' => '1'));
                } else {
                    $wpdb->insert($wpdb->prefix . 'b4l_number_certifications',array($levelName => $numberOfPeople));
                }
                $email_stud=$current_user->user_email;
                $badge_name = get_the_title();
                //If the certification language has a translation, we use that one. If it hasn't, we use the default one (in English).
                for($i=0;$i<count($results);$i++) {
                    if($_POST['language_certification']==$results[$i][language_name]){
                        $badge_desc = b4l_single_badge_translation($_POST['language_certification']);
                    } 
                }
                if($badge_desc == null){
                    $badge_desc = $post->post_content;
                }
                $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
                $badge_image = $image[0];
                $badge_pagelink = esc_url(get_permalink(get_page_by_title($badge_name)));
                $badge_lang = $_POST['language_certification'];
                if (get_the_terms($post->ID, 'badge_studentlevels')) {
                    $studentLevel = get_the_terms($post->ID, 'badge_studentlevels');
                    $badge_lvl = $studentLevel[0]->name;
                } elseif (get_the_terms($post->ID, 'badge_teacherlevels')) {
                    $teacherLevel = get_the_terms($post->ID, 'badge_teacherlevels');
                    $badge_lvl = $teacherLevel[0]->name;
                }

                b4l_create_badge_class_json($badge_name, $badge_desc, $badge_image, $badge_pagelink, $badge_lang, $badge_lvl);
                b4l_create_assertion_badge_json($email_stud, $badge_image, $badge_lang, $badge_lvl, $numberOfPeople);
                b4l_send_badge_email($email_stud, $badge_name, $badge_desc, $badge_image, $badge_pagelink, $badge_lang, $badge_lvl);
            }
            

      
                
       
                
                
                
                
                
                
              
add_action('admin_action_bsp_award_badges', 'bsp_save_awarded_badges'); // A CHANGER !!!!!!!!
function b4l_save_awarded_badges(){
	//getting the wp database
	global $wpdb;
	$tablename=$wpdb->prefix."users"; //geting the user table name with prefix
		
	//getting the meta data for current user
	global $current_user;
	get_currentuserinfo();
        
	//our checked items are "saved" in the $_POST array
	$badges=$_POST['bsp_selected'];
	$students=$_POST['student'];
	
	//getting the data from our custom table and checking if the id in the table matches the id of selected student	
	$data=$wpdb->get_results("SELECT students_name, students_lastname, students_email FROM $tablename WHERE students_id in (".implode(', ', $students).")");
				
	//if the button is clicked	
	if(isset($_POST['bsp_award_submit'])){
		//we need to check the array of selected badges to get them
		foreach($badges as $badge){
			//and get the title
			$badge_name=get_the_title($badge);
			//the id
			$badge_id=$badge;
			//the image src url
			$badge_image=wp_get_attachment_image_src(get_post_thumbnail_id($badge_id));
				$badge_image=$badge_image[0];
			//the description
			$desc=get_post($badge_id);
			$badge_desc=$desc->post_content;
			
		
			
			//get the levels
			$termslvl=wp_get_post_terms($badge, array('levels'));
			$badge_lvl='';
			if(!is_wp_error($termslvl)){
				$termslvl_all=array();
				foreach($termslvl as $termlvl){
					$termslvl_all[]= $termlvl->name;
				}
				$badge_lvl=implode($termslvl_all, ', ');
			}
			
			//getting the skill
			$termsskil=wp_get_post_terms($badge, array('skills'));
			$badge_skil='';
			if(!is_wp_error($termsskil)){
				$termsskil_all=array();
				foreach($termsskil as $termskil){
					$termsskil_all[]=$termskil->name;
				}
				$badge_skil=implode($termsskil_all, ', ');
			}
				
			//sent the email for each selected student
			foreach($data as $da){
				$email_stud=$da->students_email;
				bsp_send_badge_email($email_stud, $badge_id, $badge_name, $badge_desc, $badge_image, $badge_lan, $badge_skil, $badge_lvl);	
			}
			
		}//end of foreach badge
		
	//displaying the success message when student is added
		?>
			<div class="wrap"><!-- wp class for wraping the text-->
				<div class="updated"><p>Awards sent!</p></div><!--wp class updated for success notices -->
			</div>
		<?php
		
	}//end of isset
		  
	//need to use wp_redirect so that we stay on the same page
	wp_redirect($_SERVER['HTTP_REFERER'] );
	
    exit();
}
         
                
            
                
                
                


















                
                
                
                
                
                
                
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
        $query2 = "SELECT ".$levelName." FROM ".$wpdb->prefix."b4l_studentlevels WHERE language='".$value."'";
    } elseif (get_the_terms($post->ID, 'badge_teacherlevels')) {
        $teacherLevel = get_the_terms($post->ID, 'badge_teacherlevels');
        $levelName = $teacherLevel[0]->name;
        $query2 = "SELECT ".$levelName." FROM ".$wpdb->prefix."b4l_teacherLevels WHERE language='".$value."'";
    }
    
    //Displays the translated description
    return $wpdb->get_var($query2);
}


/**
* Creates the BadgeClass. Could be activate before (without the activation of the button get_certification).
* But activate this function at this moment can actualize the BadgeClass each time someone ask the certification.
*/            
function b4l_create_badge_class_json($badge_name, $badge_desc, $badge_image, $badge_pagelink, $badge_lang, $badge_lvl){

    //name of the json file
    $file_json=$badge_lvl.'_'.$badge_lang;

    //getting the dir path of the plugin to use
    $dir_path=plugin_dir_path( __FILE__ ).'../';

    //adding the folder json and encoded file name and addind the extenson of json
    $path_json=$dir_path.'json/badgesclass/'.$file_json.'.json';

    //recuperate the tags's name
    $tags = get_the_terms($post->ID, 'badge_tags');
    $tagsNumber = count($tags);
    $tagsName = '[';
    for($i=0; $i<$tagsNumber; $i++) {
    $tagsName = $tagsName.'"'.$tags[$i]->name.'", ';
    }
    $tagsName = substr($tagsName, 0, -2); //Delete ", " at the end of the string
    $tagsName = $tagsName.']';

    //handle for opening or creating the file and writing to it (w)
    $handle=fopen($path_json, 'w') or die ('Can not open file: '.$file_json);
    if($handle){
        //Creating of the Badge Class (Mozilla Open Badges API)
        $data=array(
            '@context'=>'https://w3id.org/openbadges/v1',
            'type'=>'BadgeClass',
            'id'=>plugins_url( 'json/badgesclass/', __FILE__ ).$file_json.'.json',
            'name'=>$badge_name.' - '.$badge_lang,
            'description'=>$badge_desc,
            'image'=>$badge_image,
            'criteria'=>$badge_pagelink,
            'tags'=> $tagsName,
            'issuer'=>'http://badges4languages.com',
            'alignment'=>array(
                array(
                    'name'=>'Common European Framework of Reference for Languages: Learning, Teaching, Assessment (CEFR)',
                    'url'=>'http://www.coe.int/t/dg4/linguistic/cadre1_en.asp',
                    'description'=>'Common European Framework of Reference for Languages: Learning, Teaching, Assessment (CEFR) web site'
                ),
                array(
                    'name'=>'Common European Framework of Reference for Languages: Learning, Teaching, Assessment',
                    'url'=>'http://www.coe.int/t/dg4/linguistic/source/framework_en.pdf',
                    'description'=>'Common European Framework of Reference for Languages: Learning, Teaching, Assessment (CEFR) PDF version'
                )
            )
        );
    }
    fwrite($handle, json_encode($data));
    fclose($handle);
}

/**
* Creates the Badge Assertion for the user
*/ 
function b4l_create_assertion_badge_json($email_stud, $badge_image, $badge_lang, $badge_lvl, $numberOfPeople){

    //adding a salt to our hashed email
    $salt=uniqid(mt_rand(), true);

    //using sha256 hash metod (open badges api defined)
    $hash='sha256$' . hash('sha256', $email_stud. $salt);

    //setting the current date
    $date=date('Y-m-d');

    //name of the json file
    $file_json=$email_stud.'_'.$badge_lvl.'_'.$badge_lang;

    //getting the dir path of the plugin to use
    $dir_path=plugin_dir_path( __FILE__ ).'../';

    //adding the folder json and encoded file name and addind the extenson of json
    $path_json=$dir_path.'json/badgesassertion/'.$file_json.'.json';

    //handle for opening or creating the file and writing to it (w)
    $handle=fopen($path_json, 'w') or die ('Can not open file: '.$file_json);
    if($handle){
        //Creating of the Badge Assertion (Mozilla Open Badges API)
        $data=array(
            '@context'=>'https://w3id.org/openbadges/v1',
            'type'=>'Assertion',
            'id'=>plugins_url( 'json/badgesassertion/', __FILE__ ).$file_json.'.json',
            'uid'=>'b4l'.'_'.$badge_lvl.'_'.$badge_lang.'_'.$numberOfPeople, //UID must be unique, so it's thanks to $numberOfPeople
            'recipient'=>array(
              'type'=>'email',
              'hashed'=>true,
              'salt'=>$salt,
              'identity'=>$hash
            ),
            'image'=>$badge_image,
            'evidence'=>'EXERCICES PAGE',
            'issued_on'=>$date,
            'badge'=>plugins_url( 'json/badgesclass/', __FILE__ ).$badge_lvl.'_'.$badge_lang.'.json',
            'verify'=>array(
                'type'=>'hosted',
                'url'=>plugins_url( 'json/badgesassertion/', __FILE__ ).$file_json.'.json'
            )
        );
    }
    fwrite($handle, json_encode($data));
    fclose($handle);
}

/**
* Sends an email to get the certification.
*/ 
function b4l_send_badge_email($email_stud, $badge_name, $badge_desc, $badge_image, $badge_pagelink, $badge_lang, $badge_lvl){

    $mailFrom = $email_issuer; //setting the from who this email is
    $subject = "You have just earned a badge"; //entering a subject for email
    //encoding the url
    $url = str_rot13(base64_encode(plugins_url('json/badgesassertion/', __FILE__).$file_json.'.json'));

    //the actual message, which is displayed in an email
    $message= ' 
    <html>
            <head>
                    <meta http-equiv="Content-Type" content="text/html"; charset="utf-8" />
            </head>
            <body>
            <div id="b4l-award-actions-wrap">
            <div align="center">
            <img src="' . plugins_url( '../images/OpenBadges.png', __FILE__ ) . '" width="180" > 
                    <h1>Congratulations you have just earned a badge!</h1>
                            <h2>'.$badge_name.' - '.$badge_lang.'</h2>
                            <a href="'.$badge_pagelink.'?filename='.$url.'">
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
    $headers = "From: Badges4languages "."<".$email_stud. ">"."\n";
    $headers .= "MIME-Version: 1.0"."\n";
    $headers .= "Content-type: text/html; charset=ISO-8859-1"."\n";
    $headers .= "Reply-To: info@badges4languages.org"."\n";

    mail('adresse.spam@live.fr', $subject, $message, $headers); //the call of the mail function with parameters
    echo 'Email Sent.';
}
?>

