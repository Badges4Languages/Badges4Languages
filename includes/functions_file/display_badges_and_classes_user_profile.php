<?php
 /*
  * Create and display badges or classes on user profile (front end and back end).
  * 
  * @author Alexandre LEVACHER
  * @package badges4languages-plugin
  * @subpackage includes/functions_file
  * @since 1.1.3
 */


/**
 * Get all the information about a certification in a specified database
 * 
 * @author Alexandre LEVACHER
 * @since 1.1.2
 * @param String $table Name of the table into the database.
 * @param Boolean $selfCertificationBoolean True if the badge is a 'Self-certification' badge,
 * @param Object $current_user Current user
 * @param String $frontOrBackEnd If it is BackEnd, a textarea is displayed for the comments
 */
function b4l_search_badges_by_category($table, $selfCertificationBoolean, $current_user, $frontOrBackEnd) {
    global $wpdb;
    
    //For the SQL query following
    if($selfCertificationBoolean == true) {
        $sign = "=";
    } else {
        $sign = "<>";
    }
    
    $query = "SELECT * FROM ".$wpdb->prefix.$table." WHERE user_id = ".$current_user->ID." AND badge_teacher ".$sign." 'Self-certification'";
    $badgesWithTeacherInfo = $wpdb->get_results($query, ARRAY_A);
    
    //Get all the badges and display them one by one
    foreach($badgesWithTeacherInfo as $badgeWithTeacherInfo) {
        b4l_display_one_badge($badgeWithTeacherInfo, $frontOrBackEnd);  
    }
}


/**
 * Display a badge : name, image, awarded date, teacher, and a comment field.
 * The user can't modify these information except the comment field.
 * 
 * @author Alexandre LEVACHER
 * @since 1.1.2
 * @param Array $badgeInfo Array which contains all the badge information
 * @param String $frontOrBackEnd If it is BackEnd, a textarea is displayed for the comments
 */
function b4l_display_one_badge($badgeInfo, $frontOrBackEnd) {
    //Adding a parameter in the URL of the page to GET after the teacher user by is user name
    $pagelink = esc_url( add_query_arg( 'user', $badgeInfo['badge_teacher'], get_permalink( get_page_by_title( 'User Profile' ) ) ) );
    
    //Check if it is a Student/Teacher Self-certification Badge or not
    if($badgeInfo['badge_teacher'] == 'Self-certification') {
        $teacher = $badgeInfo['badge_teacher']; //Teacher name : 'Self-certification'
        if ($frontOrBackEnd == 'BackEnd') {
            $comment = '<textarea name="badge_comment[]" rows="2" >'.$badgeInfo['badge_comment'].'</textarea>'; //You can modify the comment, for example to tell where you earned it. So it is a text area field.
        } else {
            $comment = $badgeInfo['badge_comment']; //You can modify the comment, for example to tell where you earned it. So it is a text area field.            
        }
    } else {
        $teacher = '<a href="'.$pagelink.'">'.$badgeInfo['badge_teacher'].'</a>'; //Teacher name : user member with a link to his profile
        if ($frontOrBackEnd == 'BackEnd') {
            $comment = '<textarea name="badge_comment[]" readonly="readonly" rows="2" >'.$badgeInfo['badge_comment'].'</textarea>'; //You can't modify the comment of a badge given by a teacher. The comment is written by the teacher. So we only display it.
        } else {
            $comment = $badgeInfo['badge_comment']; //You can modify the comment, for example to tell where you earned it. So it is a text area field.            
        }
    }
    ?>
    <div class="badge-div">
        <img class="badge-img" src=<?php echo '"'.$badgeInfo['badge_image'].'"' ?> />
        <div class="badge-text">
            <div class="badge-name">
                <?php echo $badgeInfo['badge_level']." - ".$badgeInfo['badge_language']; ?>
            </div>
            <p>
                <b>Date :</b> <?php echo $badgeInfo['badge_date']; ?> <br/>
                <b>Teacher :</b> <?php echo $teacher;?>
            </p>
        </div>
        <div class="badge-comment">
            <b>Comment :</b> <br/> <?php echo $comment ?>
            <input type="hidden" name="user_badge_id[]" value="<?php echo $badgeInfo['user_badge_id']; ?>" class="regular-text"> <br/> <!--Keep the badge ID-->
        </div>
        <div class="clear"></div> <!--Useful for the CSS-->
    </div>
    <?php
}

  
/**
 * Display classes's teacher.
 * 
 * @author Alexandre LEVACHER
 * @since 1.1.3
 * @param String $teacher_name Teacher display name in Wordpress
 * @global WordpressObject $wpdb Wordpress Database
 */
function b4l_search_and_display_classes($teacher_name) {
    global $wpdb;
    $mypost = array( 'post_type' => 'class' );
    $loop = new WP_Query( $mypost );
    while ( $loop->have_posts() ) : $loop->the_post();
        if(get_the_author_meta( 'display_name' ) == $teacher_name) {
        ?>
            <div class="badge-div">
                <div style="float: right; margin: 10px">
                    <?php the_post_thumbnail( array( 100, 100 ) ); ?>
                </div>
                <div class="badge-text">
                    <div class="badge-name">
                        <a href="<?php echo get_post_permalink(get_the_ID()); ?>" style="color: #f78181;"><?php the_title(); ?></a>
                    </div>
                    <p>
                        
                        <strong>Rating: </strong> <?php echo b4l_rating_average(get_the_ID()); ?> <br/>
                        
                        <?php if ( get_post_meta(get_the_ID(), 'class_language', true) ) : ?>
                            <strong>Language: </strong> <?php echo get_post_meta(get_the_ID(), 'class_language', true); ?> <br/>
                        <?php endif; ?>
                            
                        <!-- Get the link of the custom post 'badge' in function of the level -->
                        <?php if ( get_post_meta(get_the_ID(), 'class_level', true) ) : 
                            $idBadge = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM ".$wpdb->prefix."posts WHERE post_title = '".get_post_meta(get_the_ID(), 'class_level', true)."' AND post_type= 'badge'"));
                        ?>
                            <strong>Level: </strong>
                            <a href="<?php echo get_post_permalink($idBadge); ?>" style="color: #f78181;">
                                <?php echo get_post_meta(get_the_ID(), 'class_level', true); ?>
                            </a> <br/>
                        <?php endif; ?>
                            
                        <?php if ( get_post_meta(get_the_ID(), 'class_starting_date', true) ) : ?>
                            <strong>Starting Date: </strong> <?php echo get_post_meta(get_the_ID(), 'class_starting_date', true); ?> <br/>
                        <?php endif; ?>
                            
                        <?php if ( get_post_meta(get_the_ID(), 'class_ending_date', true) ) : ?>
                            <strong>Ending Date: </strong> <?php echo get_post_meta(get_the_ID(), 'class_ending_date', true); ?> <br/>
                        <?php endif; ?>
                            
                    </p>
                </div>
                <div class="clear"></div> <!--Useful for the CSS-->
            </div>
        <?php }
    endwhile;
    wp_reset_postdata(); //Restore the $post variable to the current post (before the loop, post_type was 'class', now it is 'post').
}


/**
 * Calculate the average of a class with the ratings comment.
 * 
 * @author Alexandre LEVACHER
 * @since 1.1.3
 * @param String $id ID of the class
 * @return Real $sum_values/$number_values Average of the rating class
 */
function b4l_rating_average($id) {
        $comments = get_comments(array('post_id' => $id));
        foreach ( $comments as $comment ) {
            $sum_values = $sum_values + get_comment_meta( $comment->comment_ID, 'rating', true);
            (int)$number_values = $number_values + 1;
        }
        return ($sum_values/$number_values);
}