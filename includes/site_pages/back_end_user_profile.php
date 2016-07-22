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
                    b4l_display_one_badge($badgeInfo['badge_image'], $badgeInfo['badge_level'], $badgeInfo['badge_language'], $badgeInfo['badge_date'] , $badgeInfo['badge_teacher']);
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
                   echo '<div class="badge-div">'.$badgeWithTeacherInfo['badge_level']." - ".$badgeWithTeacherInfo['badge_language']."</div>";
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
                    echo '<div class="badge-div">'.$badgeTeacherInfo['badge_level']." - ".$badgeTeacherInfo['badge_language']."</div>";
                } 
            ?>
        </td>
    </tr>
    
    
  </table>
<?php
}



function b4l_display_one_badge($badge_image, $badge_level, $badge_language, $badge_date , $badge_teacher) {
    ?>
    <div class="badge-div">
        <img class="badge-img" src=<?php echo '"'.$badge_image.'"' ?> />
        <div class="badge-text">
            <p class="badge-name">
                <?php echo $badge_level." - ".$badge_language; ?>
            </p>
            <p>
                <b>Date :</b> <?php echo $badge_date; ?>
                <br/>
                <b>Teacher :</b> <?php echo $badge_teacher; ?>
            </p>
        </div>
    </div>
    <?php
}






add_action( 'personal_options_update', 'b4l_save_badges_profile_fields' );
add_action( 'edit_user_profile_update', 'b4l_save_badges_profile_fields' );

/**
 * Saves a custom field for badges into user's profile.
 */
function b4l_save_badges_profile_fields( $user_id ) {
  $saved = false;
  if ( current_user_can( 'edit_user', $user_id ) ) {
    update_user_meta( $user_id, 'badge', $_POST['badge'] );
    $saved = true;
  }
  return true;
}