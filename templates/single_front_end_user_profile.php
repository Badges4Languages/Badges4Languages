<?php
/**
 * Template Name: User Profile
 *
 * Allow users to update their profiles from Frontend.
 *
 */


/**
 * Register with hook 'b4l_jQuery_front_end_user_profile', which can be used 
 * for front end CSS and jQuery actions.
 */
add_action( 'wp_enqueue_scripts', 'b4l_jQuery_front_end_user_profile' );

/**
 * Call and use jQuery and CSS files.
 * 
 * @author Alexandre LEVACHER
 * @since 1.1.2
 */
function b4l_jQuery_front_end_user_profile() {
    wp_register_script('my-jquery-script', WP_PLUGIN_URL.'/badges4languages-plugin/js/display_badges.js', array('jquery')); //Recherche de notre fichier jQuery
    wp_enqueue_script('my-jquery-script'); //Utilisation de notre fichier jQuery pour cette page
    wp_register_style('my-css', WP_PLUGIN_URL.'/badges4languages-plugin/css/single_front_end_user_profile.css'); //Recherche de notre fichier CSS
    wp_enqueue_style('my-css'); //Utilisation de notre fichier CSS pour cette page
}



/* Get user info. */
global $wp_roles;

//By default if the user writes the URL without parameter, he goes to his own profile page.
if(!(isset( $_GET['user']))){
    global $current_user;
    get_currentuserinfo();
} else {
    global $wpdb;
    $users = $wpdb->get_results("SELECT * FROM $wpdb->users WHERE display_name = '".$_GET['user']."'");
    $current_user = $users[0];
}

get_header(); ?>

<section id="content">

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <div id="post-<?php the_ID(); ?>">
        <div class="entry-content entry">
            <?php the_content(); ?>
            
                <div id="profil-div">
                    
                    <div id="profil-image">
                        <center><img id="user-avatar" src="<?php echo get_avatar_url( $current_user->ID );?>" /></center>
                    </div>
                    
                    <table id="user-info">
                        <tr>
                            <th><?php _e('Name :', 'profile'); ?></th>
                            <td><?php the_author_meta( 'display_name', $current_user->ID ); ?></td>
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
                    
                    
                    
                    <h2>Your badges</h2>
                    
                    <h3 class="badge-category" id="titre-badge-student-self-certification">
                        <?php _e('Student "Self-Cerfication" Badges', 'profile');?>
                        <img id="img-arrow" src="<?php echo WP_PLUGIN_URL . '/badges4languages-plugin/images/icon-arrow-down.png' ?>" alt="img_display_badges" />
                    </h3>
                    <div id="badge-student-self-certification">
                        <?php b4l_search_badges_by_category('b4l_userStudentBadgesProfil', true, $current_user, 'FrontEnd'); ?>
                    </div>
                    
                    
                    <h3 class="badge-category" id="titre-badge-student-awarded-by-teacher">
                        <?php _e('Student Awarded Badges', 'profile');?>
                        <img id="img-arrow" src="<?php echo WP_PLUGIN_URL . '/badges4languages-plugin/images/icon-arrow-down.png' ?>" alt="img_display_badges" />
                    </h3>
                    <div id="badge-student-awarded-by-teacher">
                        <?php b4l_search_badges_by_category('b4l_userStudentBadgesProfil', false, $current_user, 'FrontEnd'); ?>
                    </div>
                    
                    
                    <h3 class="badge-category" id="titre-badge-teacher-self-certification">
                        <?php _e('Teacher "Self-Cerfication" Badges', 'profile');?>
                        <img id="img-arrow" src="<?php echo WP_PLUGIN_URL . '/badges4languages-plugin/images/icon-arrow-down.png' ?>" alt="img_display_badges" />
                    </h3>
                    <div id="badge-teacher-self-certification">
                        <?php b4l_search_badges_by_category('b4l_userTeacherBadgesProfil', true, $current_user, 'FrontEnd'); ?>
                    </div>
                    
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
                
                
        </div><!-- .entry-content -->
    </div><!-- .hentry .post -->
    <?php endwhile; ?>
<?php else: ?>
    <p class="no-data">
        <?php _e('Sorry, no page matched your criteria.', 'profile'); ?>
    </p><!-- .no-data -->
<?php endif; ?>
</section><!-- #content -->

<?php get_footer();?>
