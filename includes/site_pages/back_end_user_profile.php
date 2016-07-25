<?php
 /*
  * Display the badges earned by the user on his profil.
  * The badges are separated in 2 categories : self-certification badges and badges given by a teacher.
  * 
  * @author Alexandre Levacher
  * @package badges4languages-plugin
  * @subpackage includes/site_pages
  * @since 1.0.0
 */




/**
 * Executes b4l_badges_profile_fields while user's profile is visualised/edited.
*/
add_action( 'show_user_profile', 'b4l_badges_profile_fields' );
add_action( 'edit_user_profile', 'b4l_badges_profile_fields' );

/**
 * Creates a custom field for badges into user's profile.
 * 
*/
function b4l_badges_profile_fields( $user ) {
    global $current_user;
    get_currentuserinfo();
    $user_roles = $current_user->roles;
    $user_role = array_shift($user_roles);
?>
<link rel="stylesheet" href="<?php echo WP_PLUGIN_URL.'/badges4languages-plugin/css/single_back_end_user_profile.css'?>" type="text/css">
  <h3>Your Student badges</h3>
  <table class="form-table">
      
    <tr>
        <th><label for="badge">Self-certification badges</label></th>
        <td>
            <!-- BADGE -->
            <?php
                global $wpdb;
                $query = "SELECT * FROM ".$wpdb->prefix."b4l_userStudentBadgesProfil
                        WHERE user_id = ".$current_user->ID." AND badge_teacher = 'Self certification'";
                $badgesSelfCertifcationsInfo = $wpdb->get_results($query, ARRAY_A);
                foreach($badgesSelfCertifcationsInfo as $badgeInfo) {
                    b4l_display_one_badge($badgeInfo);
                } 
            ?>
        </td>
    </tr>
    
    <tr>
        <?php
        if ($user_role == 'administrator' || $user_role == 'b4l_academy' || $user_role == 'b4l_teacher' || user_role == 'b4l_badges_editor') {
        ?>
            <th><label for="badge">Given by teacher</label></th>
            <td>
            <?php
                global $wpdb;
                $query = "SELECT * FROM ".$wpdb->prefix."b4l_userStudentBadgesProfil
                        WHERE user_id = ".$current_user->ID." AND badge_teacher <> 'Self certification'";
                $badgesWithTeacherInfo = $wpdb->get_results($query, ARRAY_A);
                foreach($badgesWithTeacherInfo as $badgeWithTeacherInfo) {
                    b4l_display_one_badge($badgeWithTeacherInfo);  
                }
            ?>
            </td>
        <?php } ?>
    </tr>
    
  </table>
  
  <h3>Your Teacher badges</h3>
  <table class="form-table">
      
    <tr>
        <th><label for="badge">Self-certification badges</label></th>
        <td>
            <!-- BADGE -->
            <?php
                global $wpdb;
                $query = "SELECT * FROM ".$wpdb->prefix."b4l_userTeacherBadgesProfil
                        WHERE user_id = ".$current_user->ID."";
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



function b4l_display_one_badge($badgeInfo) {
    ?>
    <div class="badge-div" >
        <img class="badge-img" src=<?php echo '"'.$badgeInfo['badge_image'].'"' ?> />
        <div class="badge-comment">
                <b>Comment :</b> <br/>
                <textarea class="badge-comments" rows="2" cols="8"><?php echo $badgeInfo['badge_comment']; ?></textarea>
                <input type="text" name="badge_comment[]" value="<?php echo $badgeInfo['badge_comment']; ?>" class="regular-text"> <br/>
                <input type="hidden" name="user_badge_id[]" value="<?php echo $badgeInfo['user_badge_id']; ?>" class="regular-text"> <br/>
                
        </div>
        <div class="badge-text">
            <div class="badge-name">
                <?php echo $badgeInfo['badge_level']." - ".$badgeInfo['badge_language']; ?>
            </div>
            <p>
                <b>Date :</b> <?php echo $badgeInfo['badge_date']; ?> <br/>
                <b>Teacher :</b> <?php echo $badgeInfo['badge_teacher']; ?>
            </p>
        </div>
        <div class="clear"></div> <!--Usefull for the CSS-->
    </div>
    <?php
}


    

add_action( 'personal_options_update', 'b4l_save_badges_profile_fields' );
add_action( 'edit_user_profile_update', 'b4l_save_badges_profile_fields' );

/**
 * Saves a custom field for badges into user's profile.
 */
function b4l_save_badges_profile_fields( $user_id ) {
    global $wpdb;
    
    if ( !current_user_can( 'edit_user', $user_id ) )
        return false;
    
    foreach ($_POST['badge_comment'] as $k=>$val) {if($val != null) {
            $wpdb->update( $wpdb->prefix . 'b4l_userStudentBadgesProfil',array('badge_comment' => $val),array('user_badge_id' => $_POST['user_badge_id'][$k]));
        }
    }
}