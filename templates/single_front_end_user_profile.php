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
    //wp_register_script('my-jquery-script', WP_PLUGIN_URL.'/badges4languages-plugin/js/display_badges.js', array('jquery')); //Recherche de notre fichier jQuery
    //wp_enqueue_script('my-jquery-script'); //Utilisation de notre fichier jQuery pour cette page
    wp_register_style('my-css', WP_PLUGIN_URL.'/badges4languages-plugin/css/single_front_end_user_profile.css'); //Recherche de notre fichier CSS
    wp_enqueue_style('my-css'); //Utilisation de notre fichier CSS pour cette page
}



/* Get user info. */
global $wp_roles;

//By default if the user writes the URL without parameter, he goes to his own profile page.
if(!(isset( $_GET['user']))){
    global $current_user;
    get_currentuserinfo();
    $user_roles = $current_user->roles;
    $user_role = array_shift($user_roles);
} else {
    global $wpdb;
    $users = $wpdb->get_results("SELECT * FROM $wpdb->users WHERE display_name = '".$_GET['user']."'");
    $current_user = $users[0];
    $user_info = get_userdata($current_user->ID);
    $user_roles = $user_info->roles;
    $user_role = array_shift($user_roles);
}

//Contain functions to display badges/classes
require WP_PLUGIN_DIR.'/badges4languages-plugin/includes/functions_file/display_badges_and_classes_user_profile.php';

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
                    
                    <ul class="tab">
                        <li><a href="#" class="tablinks" onclick="openCategory(event, 'badge-student-self-certification')">Student "Self-Cerfication" Badges</a></li>
                        <li><a href="#" class="tablinks" onclick="openCategory(event, 'badge-student-awarded-by-teacher')">Student Awarded Badges</a></li>
                        <?php
                        //Display the section if the user can have (access to) Teacher Badges
                        if ($user_role == 'administrator' || $user_role == 'b4l_academy' || $user_role == 'b4l_teacher' || $user_role == 'b4l_badges_editor') {
                        ?>
                            <li><a href="#" class="tablinks" onclick="openCategory(event, 'badge-teacher-self-certification')">Teacher "Self-Cerfication" Badges'</a></li>
                            <li><a href="#" class="tablinks" onclick="openCategory(event, 'teacher-classes')">My classes</a></li>
                        <?php }?>
                    </ul>
                    
                    <div id="badge-student-self-certification" class="tabcontent">
                        <?php b4l_search_badges_by_category('b4l_userStudentBadgesProfil', true, $current_user, 'FrontEnd'); ?>
                    </div>
                    
                    <div id="badge-student-awarded-by-teacher" class="tabcontent">
                        <?php b4l_search_badges_by_category('b4l_userStudentBadgesProfil', false, $current_user, 'FrontEnd'); ?>
                    </div>
                    
                    <div id="badge-teacher-self-certification" class="tabcontent">
                        <?php b4l_search_badges_by_category('b4l_userTeacherBadgesProfil', true, $current_user, 'FrontEnd'); ?>
                    </div>
                    
                    <div id="teacher-classes" class="tabcontent">
                        <?php b4l_search_and_display_classes($current_user->display_name); ?>
                    </div>
                   
                </div>
                
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


<!-- Source : http://www.w3schools.com/howto/howto_js_tabs.asp -->
<!-- FOR THE MOMENT THE USER PROFILE FRONT END PAGE IS NOT OFFICIAL, IT CAN CHANGE.
BEFORE AN OTHER JAVASCRIPT CODE WAS USED (cf beginning of this file, '/badges4languages-plugin/js/display_badges.js')
SO WHEN THE FINAL VERSION WILL BE ELECTED, THIS CODE BELOW WILL BE REMOVED : IF THIS CODE IS ELECTED
IT WILL BE INTO '/badges4languages-plugin/js/display_badges.js', ELSE IT WILL BE DELETED.

THIS VERSION (WITH THIS CODE BELOW) CREATES TABS TO DISPLAY BADGES AND CLASSES. (1 TAB FOR 1 TYPE OF BADGE)
IF YOU WANT THE FORMER VERSION (ALL IN ONE PAGE, YOU HAVE TO CLICK ON THE TYPE OF BADGE'S TITLE TO SHOW/HIDE
THE BADGES), PLEASE CHECK THE CODE BEFORE VERSION 1.1.3 08/08/2016 ON GITHUB.

DON'T FORGET TO MAKE THIS CHANGE FOR MORE CLARITIES AND RESPECTING WORDPRESS PLUGIN STRUCTURE ! -->
<script>
    function openCategory(evt, htmlDivID) {
        // Declare all variables
        var i, tabcontent, tablinks;

        // Get all elements with class="tabcontent" and hide them
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }

        // Get all elements with class="tablinks" and remove the class "active"
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }

        // Show the current tab, and add an "active" class to the link that opened the tab
        document.getElementById(htmlDivID).style.display = "block";
        evt.currentTarget.className += " active";
    }
</script>