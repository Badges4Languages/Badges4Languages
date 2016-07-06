<?php
 /*
  * Description:        Display the badges earned by the user on his profil.
  *                     The badges are separated in 2 categories : self-certification
  *                     badges and badges given by a teacher.
  * 
  * Version:            1.0.0
  * Author:             Alexandre Levacher
 */


/**
 * Executes b4l_badges_profile_fields while user's profile is visualised/edited.
*/
add_action( 'show_user_profile', 'b4l_badges_profile_fields' );
add_action( 'edit_user_profile', 'b4l_badges_profile_fields' );

/**
 * Creates a custom field for badges into user's profile.
 * 
 * 
*/
function b4l_badges_profile_fields( $user ) {
?>
  <h3>Your badges</h3>
  <table class="form-table">
      
    <tr>
      <th><label for="badge">Self-certification badges</label></th>
      <td>
        <!-- BADGE -->
        <input type="text" name="badge" id="phone" class="regular-text" 
            value="<?php echo esc_attr( get_the_author_meta( 'badge', $user->ID ) ); ?>" /><br />
        <span class="description"><?php _e("Please enter ........."); ?></span>
    </td>
    </tr>
    
    <tr>
      <th><label for="badge">Given by teacher</label></th>
      <td>
        <!-- BADGE -->
        <input type="text" name="badge" id="phone" class="regular-text" 
            value="<?php echo esc_attr( get_the_author_meta( 'badge', $user->ID ) ); ?>" /><br />
        <span class="description"><?php _e("Please enter ........."); ?></span>
    </td>
    </tr>
    
  </table>
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