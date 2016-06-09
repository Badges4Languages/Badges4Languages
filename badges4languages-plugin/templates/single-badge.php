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
                
                
                
                
                
                
                
                
                
                <!-- AFFICHER L'IMAGE FEATURED POUR VOIR SI ON PEUT RECUPERER SON URL
<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
<div id="custom-bg" style="background-image: url('<?php echo $image[0]; ?>')">
                
                
                
                
                
                
                
                
                
                
                
                
                
                <!-- Send an email to get the certification -->      
                
                
                <form action="" method="post">
                    <input type="submit" value="Get the certification" name="button_pressed"/>
                </form>

                <?php
                /*
                if(isset($_POST['button_pressed']))
                {
                    $to      = $current_user->user_email; 
                    $subject = 'Obtains the level';
                    $message = $post->post_content;
                    $headers = 'From: mylanguageskills@hotmail.com' . "\r\n" .
                        'Reply-To: mylanguageskills@hotmail.com' . "\r\n" .
                        'X-Mailer: PHP/' . phpversion();
                    //mail($to, $subject, $message, $headers);
                    echo 'Email Sent.';
                }
                */
                ?>
                
                <?php 
                global $current_user;
                get_currentuserinfo();
                
                if(isset($_POST['button_pressed']))
                {
                $email_stud=$current_user->user_email;
                bsp_send_badge_email($email_stud, $badge_id, $badge_name, $badge_desc, $badge_image, $badge_lan, $badge_skil, $badge_lvl);
                }
                
                
                //function for sending the email and json files  
function bsp_send_badge_email($email_stud, $badge_id, $badge_name, $badge_desc, $badge_image, $badge_lan, $badge_skil, $badge_lvl){
    //adding a salt to our hashed email
    $salt=uniqid(mt_rand(), true);
    //using sha256 hash metod (open badges api defined)
    $hash='sha256$' . hash('sha256', $email_stud. $salt);
    //setting the current date
    $date=date('Y-m-d');

	//getting the settings data
	$name_issuer=get_option('bsp_issuer_name'); // A VERIFIER !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	$org_issuer=get_option('bsp_issuer_org');
	$email_issuer=get_option('bsp_issuer_email');
	$url_issuer=get_option('bsp_issuer_url');
	
        if (get_the_terms($post->ID, 'badge_studentlevels')) {
            $studentLevel = get_the_terms($post->ID, 'badge_studentlevels');
            $levelName = $studentLevel[0]->name;
        } elseif (get_the_terms($post->ID, 'badge_teacherlevels')) {
            $teacherLevel = get_the_terms($post->ID, 'badge_teacherlevels');
            $levelName = $teacherLevel[0]->name;
        }
	//string for encoding the email student and badge name (used in str_rot13)
	$str = $email_stud.$badge_name;
	//encoding the json files
	//$file_json=str_rot13($badge_id . '-' . preg_replace("/ /", "_", $email_stud)); //POURQUOI ENCODER ???
        $file_json=$email_stud.$badge_name;
        
	//getting the dir path of the plugin to use
	$dir_path=plugin_dir_path( __FILE__ ).'../';
	//adding the folder json and encoded file name and addind the extenson of json
	$path_json=$dir_path.'json/'.$file_json.'.json';
	
	//handle for opening or creating the file and writing to it (w)
	$handle=fopen($path_json, 'w') or die ('Can not open file: '.$file_json);
	if($handle){
		//data for issuing the badge (mozilla open badges api specified)
		$data=array(
			'recipient'=> $hash,
			'salt'=>$salt,
			'badge'=>array(
				'name'=>$badge_name,
				'description'=>$post->post_content,
				'image'=>$badge_image,
				'criteria'=>'http://about.badges4languages.org/',
				'issuer'=>array(
					'name'=>$name_issuer,
					'origin'=>$url_issuer,
					'email'=>$email_issuer,
				)
			),
			'verify'=>array(
				'type'=>'hosted',
				'url'=>plugins_url( 'json/', __FILE__ ).$file_json.'.json',
			),
			'issued_on'=>$date
			);
		//encoding the data into json format	
		if(fwrite($handle, json_encode($data))){
			fclose($handle);
			//getting the url of the page by title (our custom created page)
			$pagelink=esc_url( get_permalink( get_page_by_title( 'Accept Badge' ) ) );
			
				//form for sending an email in html 
				$mail = $email_stud; //setting the to who this email is send
				$mailFrom = $email_issuer; //setting the from who this email is
				$subject = "You have just earned a badge"; //entering a subject for email
				//encoding the url
				$url = str_rot13(base64_encode(plugins_url('json/', __FILE__).$file_json.'.json'));

				//the actual message, which is displayed in an email
				$message= ' 
				<html>
					<head>
						<meta http-equiv="Content-Type" content="text/html"; charset="utf-8" />
					</head>
					<body>
					<div id="bsp-award-actions-wrap">
					<img src="' . plugins_url( 'images/OpenBadges.png', __FILE__ ) . '" align="right">
					<div align="center">
					<img src="' . plugins_url( 'images/logo_b.png', __FILE__ ) . '" > 
						<h1>Congratulations you have just earned a badge!</h1>
							<h2>'.$badge_name.' '.$badge_lan.' '.$badge_lvl.' '.$badge_skil.'</h2>
							
							<a href="'.$pagelink.'?id='.$badge_id.'&filename='.$url.'">
							<img src="'.$badge_image.'"></a></br>
							<p>Description: '.$badge_desc.'</p>
						<h2 class="acceptclick">Click on the badge to add it to your Mozilla Backpack!</h2>
						<div class="browserSupport"><b>Please use Firefox or Google Chrome to retrieve your badge.<b></div>
						</div>
					</body>
				</html>
				';
				$json_hosted_file=plugins_url('json/', __FILE__ ).$file_json.'.json';
				
				//setting headers so it's a MIME mail and a html
				// Always set content-type when sending HTML email
				$headers = "From: Badges4languages "."<".$mailFrom. ">"."\n";
				$headers .= "MIME-Version: 1.0"."\n";
				$headers .= "Content-type: text/html; charset=ISO-8859-1"."\n";
				$headers .= "Reply-To: info@badges4languages.org"."\n";

				mail($mail, $subject, $message, $headers); //the call of the mail function with parameters
                                echo 'Email Sent.';
                                echo '<br>';
                                echo 'json file name :'.$file_json.'.json';
                
		}//end of if fwrite
	}//end of if handle	
}//end of function
                
                
                
                
                
                
                
                
                
                
                
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
    echo $wpdb->get_var($query2);
}


?>

