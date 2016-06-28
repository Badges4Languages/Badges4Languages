<?php
 /*
  * Description:        Create a json file and send a email to get a certification.
  * 
  * Version:            1.0.0
  * Author:             Alexandre Levacher
 */


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
 * Creates the 'Self Certification' Badge Assertion for the user. 
 * Contains information about the badge (BadgeClass) and the issuer of the 
 * badge (IssuerBadge).
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
 * Creates the Badge Assertion given by a teacher for the user. 
 * Contains information about the badge (BadgeClass) and the issuer of the 
 * badge (IssuerBadge).
*/ 
function b4l_create_assertion_badge_given_by_teacher_json($email_stud, $badge_image, $badge_lang, $badge_lvl, $badge_name, $badge_desc, $issuer_name, $issuer_url, $issuer_email){

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
                            'name'=>$issuer_name.' (Teacher : XXX)',
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