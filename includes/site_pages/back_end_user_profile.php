<?php
 /*
  * Display the badges earned by the user on his profil.
  * The badges are separated in 2 main categories : badges for student and badge for teacher.
  * 
  * @author Alexandre Levacher
  * @package badges4languages-plugin
  * @subpackage includes/site_pages
  * @since 1.1.2
 */


/**
 * Executes b4l_badges_profile_fields while user's profile is visualised/edited.
*/
add_action( 'show_user_profile', 'b4l_badges_profile_fields' );
add_action( 'edit_user_profile', 'b4l_badges_profile_fields' );

/**
 * Creates custom fields for badges into user's profile.
 * 
 * Into the Student category, 2 subcategories : self-certification and given by a teacher.
 * For the moment there is only 1 category for the teacher (self-certification).
 * 
 * @author Alexandre LEVACHER
 * @since 1.1.2
 * 
*/
function b4l_badges_profile_fields( ) {
    global $current_user;
    get_currentuserinfo();
    $user_roles = $current_user->roles;
    $user_role = array_shift($user_roles);
?>
<link rel="stylesheet" href="<?php echo WP_PLUGIN_URL.'/badges4languages-plugin/css/single_back_end_user_profile.css'?>" type="text/css">
  <h3>Your Student badges</h3>
  <table class="form-table">
    <tr>
        <th><label for="badge">Self-certification badges</label></th> <!--STUDENT SELF CERTIFICATION BADGES -->
        <td>
        <?php
        //There is no verification of the user role because all the users are at least 'Student'
            global $wpdb;
            $query = "SELECT * FROM ".$wpdb->prefix."b4l_userStudentBadgesProfil WHERE user_id = ".$current_user->ID." AND badge_teacher = 'Self-certification'";
            $badgesSelfCertifcationsInfo = $wpdb->get_results($query, ARRAY_A);
            foreach($badgesSelfCertifcationsInfo as $badgeInfo) {
                b4l_display_one_badge($badgeInfo);
            } 
        ?>
        </td>
    </tr>
    <tr>
        <th><label for="badge">Given by teacher</label></th> <!--STUDENT AWARDED BADGES -->
        <td>
        <?php
            global $wpdb;
            $query = "SELECT * FROM ".$wpdb->prefix."b4l_userStudentBadgesProfil WHERE user_id = ".$current_user->ID." AND badge_teacher <> 'Self-certification'";
            $badgesWithTeacherInfo = $wpdb->get_results($query, ARRAY_A);
            foreach($badgesWithTeacherInfo as $badgeWithTeacherInfo) {
                b4l_display_one_badge($badgeWithTeacherInfo);  
            }
        ?>
        </td>
        
    </tr>
  </table>
  
<?php
    //Display the section if the user can have (access to) Teacher Badges
    if ($user_role == 'administrator' || $user_role == 'b4l_academy' || $user_role == 'b4l_teacher' || user_role == 'b4l_badges_editor') {
?>
        <h3>Your Teacher badges</h3>
        <table class="form-table">
          <tr>
              <th><label for="badge">Self-certification badges</label></th> <!--TEACHER AWARDED BADGES -->
              <td>
                <?php
                    global $wpdb;
                    $query = "SELECT * FROM ".$wpdb->prefix."b4l_userTeacherBadgesProfil WHERE user_id = ".$current_user->ID." AND badge_teacher = 'Self-certification'";
                    $badgesTeacherInfo = $wpdb->get_results($query, ARRAY_A);
                    foreach($badgesTeacherInfo as $badgeTeacherInfo) {
                        b4l_display_one_badge($badgeTeacherInfo); 
                    } 
                ?>
              </td>
          </tr>
        </table>
    <?php 
    }
}


/**
 * Display a badge : name, image, awarded date, teacher, and a comment field.
 * The user can't modify these information except the comment field.
 * 
 * @author Alexandre LEVACHER
 * @since 1.1.2
 * @param Array $badgeInfo Array which contains all the badge information
 */
function b4l_display_one_badge($badgeInfo) {
    ?>
    <div class="badge-div" >
        <img class="badge-img" src=<?php echo '"'.$badgeInfo['badge_image'].'"' ?> />
        <div class="badge-text">
            <div class="badge-name">
                <?php echo $badgeInfo['badge_level']." - ".$badgeInfo['badge_language']; ?>
            </div>
            <p>
                <b>Date :</b> <?php echo $badgeInfo['badge_date']; ?> <br/>
                <b>Teacher :</b> <?php echo $badgeInfo['badge_teacher']; ?>
            </p>
        </div>
        
        <div class="badge-comment">
            <b>Comment :</b> <br/>
            <?php 
            //You can modify the comment of a 'self-certification' badge, for example to tell where you earned it. So it is a text area field.-->
            if ($badgeInfo['badge_teacher'] == 'Self-certification') {
                echo '<textarea name="badge_comment[]" rows="2" >'.$badgeInfo['badge_comment'].'</textarea>';
            } else { //You can't modify the comment of a badge given by a teacher. The comment is written by the teacher. So we only display it.-->
                echo '<textarea name="badge_comment[]" readonly="readonly" >'.$badgeInfo['badge_comment'].'</textarea>';
            } ?>
            <input type="hidden" name="user_badge_id[]" value="<?php echo $badgeInfo['user_badge_id']; ?>" class="regular-text"> <br/> <!--Keep the badge ID-->
        </div>
        <div class="clear"></div> <!--Useful for the CSS-->
    </div>
    <?php
}

    
/**
 * Save and update the user profile's information
 */
add_action( 'personal_options_update', 'b4l_save_badges_profile_fields' );
add_action( 'edit_user_profile_update', 'b4l_save_badges_profile_fields' );

/**
 * Saves a custom field for badges into user's profile.
 * 
 * @author Alexandre LEVACHER
 * @since 1.1.2
 * @param String $user_id User ID
 */
function b4l_save_badges_profile_fields( $user_id ) {
    global $wpdb;
    
    //Check if user can edit his profile
    if ( !current_user_can( 'edit_user', $user_id ) )
        return false;
    
    //Foreach badge on the user profile....
    foreach ($_POST['badge_comment'] as $k=>$val) {
        //... if the comment has a value (text or "" but not null)....
        if($val != null) {
            //... update into the database tables.
            $wpdb->update( $wpdb->prefix . 'b4l_userStudentBadgesProfil',array('badge_comment' => $val),array('user_badge_id' => $_POST['user_badge_id'][$k]));
            $wpdb->update( $wpdb->prefix . 'b4l_userTeacherBadgesProfil',array('badge_comment' => $val),array('user_badge_id' => $_POST['user_badge_id'][$k]));
        }
    }
}