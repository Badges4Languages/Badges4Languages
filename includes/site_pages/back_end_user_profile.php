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
    if(!(isset( $_GET['user_id']))){
        global $current_user;
        get_currentuserinfo();
        $user_roles = $current_user->roles;
        $user_role = array_shift($user_roles);
    } else {
        //If there is a parameter, it checks the name in the dabase and displays the info
        global $wpdb;
        $users = $wpdb->get_results("SELECT * FROM $wpdb->users WHERE display_name = '".$_GET['user']."'");
        $current_user = $users[0];
        $user_info = get_userdata($current_user->ID);
        $user_roles = $user_info->roles;
        $user_role = array_shift($user_roles);
    }
    
    //Contain functions to display badges/classes
    require WP_PLUGIN_DIR.'/badges4languages-plugin/includes/functions_file/display_badges_and_classes_user_profile.php';
?>
<link rel="stylesheet" type="text/css" href="<?php echo WP_PLUGIN_URL.'/badges4languages-plugin/css/single_back_end_user_profile.css'; ?>">
<h3>Your Student badges</h3>
  <table class="form-table">
    <tr>
        <th><label for="badge">Self-certification badges</label></th> <!--STUDENT SELF CERTIFICATION BADGES -->
        <td><?php b4l_search_badges_by_category('b4l_userStudentBadgesProfil', true, $current_user, 'BackEnd'); ?></td>
    </tr>
    <tr>
        <th><label for="badge">Given by teacher</label></th> <!--STUDENT AWARDED BADGES -->
        <td><?php b4l_search_badges_by_category('b4l_userStudentBadgesProfil', false, $current_user, 'BackEnd'); ?></td>
    </tr>
  </table>
  
<?php
    //Display the section if the user can have (access to) Teacher Badges
    if ($user_role == 'administrator' || $user_role == 'b4l_academy' || $user_role == 'b4l_teacher' || $user_role == 'b4l_badges_editor') {
?>
        <h3>Your Teacher badges</h3>
        <table class="form-table">
          <tr>
              <th><label for="badge">Self-certification badges</label></th> <!--TEACHER AWARDED BADGES -->
              <td> <?php b4l_search_badges_by_category('b4l_userTeacherBadgesProfil', true, $current_user, 'BackEnd'); ?></td>
          </tr>
        </table>
        
        <h3>Your classes</h3>
        <table class="form-table">
          <tr>
              <th><label for="badge">Classes</label></th> <!--TEACHER AWARDED BADGES -->
              <td> <?php b4l_search_and_display_classes($current_user->display_name); ?></td>
          </tr>
        </table>
    <?php 
    }
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