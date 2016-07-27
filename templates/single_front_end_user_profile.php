<?php
/**
 * Template Name: User Profile
 *
 * Allow users to update their profiles from Frontend.
 *
 
 */

// https://gist.github.com/chrisdigital/5525127

/* Get user info. */
global $current_user, $wp_roles;
get_currentuserinfo();

/* Load the registration file. */
require_once( ABSPATH . WPINC . '/registration.php' );
$error = array();    
/* If profile was saved, update profile. */
if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'update-user' ) {

    /* Update user password. */
    if ( !empty($_POST['pass1'] ) && !empty( $_POST['pass2'] ) ) {
        if ( $_POST['pass1'] == $_POST['pass2'] )
            wp_update_user( array( 'ID' => $current_user->ID, 'user_pass' => esc_attr( $_POST['pass1'] ) ) );
        else
            $error[] = __('The passwords you entered do not match.  Your password was not updated.', 'profile');
    }

    /* Update user information. */
    if ( !empty( $_POST['url'] ) )
       wp_update_user( array ('ID' => $current_user->ID, 'user_url' => esc_attr( $_POST['url'] )));
    if ( !empty( $_POST['email'] ) ){
        if (!is_email(esc_attr( $_POST['email'] )))
            $error[] = __('The Email you entered is not valid.  please try again.', 'profile');
        elseif(email_exists(esc_attr( $_POST['email'] )) != $current_user->id )
            $error[] = __('This email is already used by another user.  try a different one.', 'profile');
        else{
            wp_update_user( array ('ID' => $current_user->ID, 'user_email' => esc_attr( $_POST['email'] )));
        }
    }

    if ( !empty( $_POST['first-name'] ) )
        update_user_meta( $current_user->ID, 'first_name', esc_attr( $_POST['first-name'] ) );
    if ( !empty( $_POST['last-name'] ) )
        update_user_meta($current_user->ID, 'last_name', esc_attr( $_POST['last-name'] ) );
    if ( !empty( $_POST['display_name'] ) )
        wp_update_user(array('ID' => $current_user->ID, 'display_name' => esc_attr( $_POST['display_name'] )));
      update_user_meta($current_user->ID, 'display_name' , esc_attr( $_POST['display_name'] ));
    if ( !empty( $_POST['description'] ) )
        update_user_meta( $current_user->ID, 'description', esc_attr( $_POST['description'] ) );

    /* Redirect so the page will show updated info.*/
  /*I am not Author of this Code- i dont know why but it worked for me after changing below line to if ( count($error) == 0 ){ */
    if ( count($error) == 0 ) {
        //action hook for plugins and extra fields saving
        do_action('edit_user_profile_update', $current_user->ID);
        wp_redirect( get_permalink().'?updated=true' ); exit;
    }       
}


get_header(); // Loads the header.php template. ?>

	<section id="content">

		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <div id="post-<?php the_ID(); ?>">
        <div class="entry-content entry">
            <?php the_content(); ?>
            <?php if ( !is_user_logged_in() ) : ?>

                    <p class="warning">
                        <?php _e('You must be logged in to edit your profile.', 'profile'); ?>
                    </p><!-- .warning -->
            <?php else : ?>
                <link rel="stylesheet" href="<?php echo WP_PLUGIN_URL.'/badges4languages-plugin/css/single_front_end_user_profile.css'?>" type="text/css">    
               
                <div id="profil-div">
                    
                    <div id="profil-image">
                        <img id="user-avatar" src="<?php echo get_avatar_url( $current_user->ID ) ?>" />
                    </div>
                    
                    <table id="user-info">
                        <tr>
                            <th><?php _e('Name :', 'profile'); ?></th>
                            <td><?php the_author_meta( 'display_name', $current_user->ID ); echo " "; the_author_meta( 'last_name', $current_user->ID );?></td>
                        </tr>
                        <tr>
                            <th><?php _e('E-mail :', 'profile'); ?></th>
                            <td><?php the_author_meta( 'user_email', $current_user->ID ); ?></td>
                        </tr>
                        <tr>
                            <th><?php _e('Website', 'profile'); ?></th>
                            <td><?php the_author_meta( 'user_url', $current_user->ID ); ?></td>
                        </tr>
                        <tr>
                            <th><?php _e('Biographical Information', 'profile') ?></th>
                            <td><?php the_author_meta( 'description', $current_user->ID ); ?></td>
                        </tr>
                    </table>
                    
                    <h3>Your badges</h3>
                     <table id="user-info">
                        <tr>
                            <th><?php _e('Student "Self-Cerfication" Badges', 'profile') ?></th>
                            <td></td>
                        </tr>
                        <tr>
                            <th><?php _e('Student Awarded Badges', 'profile') ?></th>
                            <td></td>
                        </tr>
                        <tr>
                            <th><?php _e('Teacher "Self-Cerfication" Badges', 'profile') ?></th>
                            <td></td>
                        </tr>
                    </table>
                    
                </div>
                    
                    
               <!-- <table>
<tr class="td1" id="td1" style="">  
     <td><input type="text" name="val1" id="val1"/>VAL 1</td>
     <td><input type="text" name="val2" id="val2"/>VAL 2</td>
</tr>
  <tr class="td2" id="td2" style="">  
     <td><input type="text" name="val3" id="val3"/>VAL 3</td>
     <td><input type="text" name="val4" id="val4"/>VAL 4</td>
</tr>
</table>
                
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"> // TELECHARGER JS
                $('#td1').click(function() {
                     $('#val2').toggle('show');
            });
                </script>-->
                
            <?php endif; ?>
        </div><!-- .entry-content -->
    </div><!-- .hentry .post -->
    <?php endwhile; ?>
<?php else: ?>
    <p class="no-data">
        <?php _e('Sorry, no page matched your criteria.', 'profile'); ?>
    </p><!-- .no-data -->
<?php endif; ?>

	</section><!-- #content -->
<?php get_footer(); // Loads the footer.php template. ?>