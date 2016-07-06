<?php
 /*
  * Description:        Create a json file and send a email to get a certification.
  * 
  * Version:            1.0.1
  * Author:             Alexandre Levacher
 */


/**
 * Writes the translation depending on the language choose by the user.
 * 
 * Foreign Key doesn't work/exist on WordPress for the database,
 * so I use a "non-official method" to find information.
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
 * @param string $translation_language Language which have a translation into the Database
 * @return string $wpdb->get_var($query2) Translated Description
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
 * Creates the 'Self Certification' Badge Assertion for the user. 
 * Contains information about the badge (BadgeClass) and the issuer of the 
 * badge (IssuerBadge).
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
 * @param string $email_stud Student's email
 * @param string $badge_image Link of the Badge's image
 * @param string $badge_lang Badge's language
 * @param string $badge_lvl Badge's level
 * @param string $badge_name Badge's name (corresponds to the page title, or the badge's level+language)
 * @param string $badge_desc Badge's description
 * @param string $issuer_name Issuer's name (name of the firm/group which gives the certification)
 * @param string $issuer_url Issuer's url
 * @param string $issuer_email Issuer's email
 * @param int|string $numberOfPeopleOrTeacherUserName Number of People having this certification (for self-certification badges) or Teacher's name (for badges awarded by a teacher)
 * @return string $file_json Json file path
*/ 
function b4l_create_certification_assertion_badge_json($email_stud, $badge_image, $badge_lang, $badge_lvl, $badge_name, $badge_desc, $issuer_name, $issuer_url, $issuer_email, $numberOfPeopleOrTeacherUserName){
    
    //adding a salt to our hashed email
    $salt=uniqid(mt_rand(), true);

    //using sha256 hash metod (open badges api defined)
    $hash='sha256$' . hash('sha256', $email_stud. $salt);

    //setting the current date
    $date=date('Y-m-d');

    //name of the json file
    $file_json=str_rot13(preg_replace("/ /", "_", $email_stud)).'_'.$badge_lvl.'_'.$badge_lang;

    //adding the folder json and encoded file name and addind the extenson of json
    $path_json= WP_PLUGIN_DIR.'/badges4languages-plugin/json/'.$file_json.'.json';
    
    //Checks if it is a number (self certification) or if a teacher gave the badge
    if(is_int($numberOfPeopleOrTeacherUserName)) {
        $uid_number = $numberOfPeopleOrTeacherUserName;
        $badge_name_info = '(Self certification)';
    } else {
        //Could cause problems in the future if we have a lot of certifications sent,
        //the random number (mt_rand() function) can be appeared a second time (probability 
        //increases if there are a lot of members/certifications sent).
        $uid_number = $hash;
        $badge_name_info = '(Teacher : '.$numberOfPeopleOrTeacherUserName.')';
    }
    //handle for opening or creating the file and writing to it (w)
    $handle=fopen($path_json, 'w') or die ('Can not open file: '.$file_json);
    if($handle){
        //Creating of the Badge Assertion (Mozilla Open Badges API)
        $data=array(
            'recipient'=> $hash,
            'salt'=>$salt,
            '@context'=>'https://w3id.org/openbadges/v1',
            'type'=>'Assertion',
            'uid'=>'b4l'.'_'.$badge_lvl.'_'.$badge_lang.'_'.$uid_number, //UID must be unique, so it's thanks to $numberOfPeople
            'badge'=>array(
                    '@context'=>'https://w3id.org/openbadges/v1',
                    'type'=>'BadgeClass',
                    'name'=>$badge_name.' - '.$badge_lang.' '.$badge_name_info,
                    'level'=>$badge_lvl, //level and language are not required, it's to have more information and to use them to stock badges on user profil
                    'language'=>$badge_lang,
                    'description'=>$badge_desc,
                    'image'=>$badge_image,
                    'criteria'=>'https://badges4languages.wordpress.com/',
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
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
 * @param string $email_stud Student's email
 * @param string $badge_name Badge's name (corresponds to the page title, or the badge's level+language)
 * @param string $badge_desc Badge's description
 * @param string $badge_image Link of the Badge's image
 * @param string $badge_lang Badge's language
 * @param string $file_json Json File, used to create the link to go to the good 'Accept Bage' page
 * @param string $issuer_logo Issuer's logo (logo of the firm/group which gives the certification)
 * @param string $issuer_email Issuer's email
 * @return string $file_json Json file path
*/
function b4l_send_badge_email($email_stud, $badge_name, $badge_desc, $badge_image, $badge_lang, $file_json, $issuer_logo, $issuer_email){
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
                        <h2>'.$badge_name.' - '.$badge_lang.'</h2>
                        <a href="'.$pagelink.'?id='.$badge_id.'&filename='.$url.'">
                            <img src="'.$badge_image.'" width="150" height="150">
                        </a>
                        </br>
                        <p>Description: '.$badge_desc.'</p>
                        <a href="'.$pagelink.'?id='.$badge_id.'&filename='.$url.'" style="background-color:#fe010d;border:1px solid #000000;border-radius:3px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:16px;line-height:44px;text-align:center;text-decoration:none;width:150px;-webkit-text-size-adjust:none;mso-hide:all;">Get the certification</a>
                        <div class="browserSupport"><b>Please use Firefox or Google Chrome to retrieve your badge.<b></div>
                    </div>
                </div>
            </body>
    </html>
    ';

    //Setting headers so it's a MIME mail and a html
    $headers = "From: Badges4languages "."<".$issuer_email.">"."\n";
    $headers .= "MIME-Version: 1.0"."\n";
    $headers .= "Content-type: text/html; charset=utf-8"."\n";
    $headers .= "Reply-To: ".$issuer_email.""."\n";

    mail($email_stud, $subject, $message, $headers); //Sending the emails
    echo 'Email Sent. If the mail is not in your mail box, verify your spams.';
    echo '<br/>';
}