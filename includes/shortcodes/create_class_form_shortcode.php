<?php

function b4l_create_class_form(){
?>
<div id="primary">
    <div id="content" role="main">
    
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php if(isset($post_ID)) {
                echo get_permalink($post_ID);
            }
            ?>
            
            <header class="entry-header" />
            
            <form id="post" class="post-edit front-end-form" method="post" enctype="multipart/form-data">

                <input type="hidden" name="post_id" value="<?php the_ID(); ?>" />
                <?php wp_nonce_field( 'update_post_'. get_the_ID(), 'update_post_nonce' ); ?>

                <input type="text" id="post_title" name="post_title" value="<?php echo $post->post_title; ?>" style="width:100%" placeholder="Title" /></p>

                <p><?php wp_editor( $post->post_content, 'postcontent' ); ?></p>
                
                <?php b4l_display_class_meta_box($post) ?>

                <input type="submit" id="submit" value="Submit" />

            </form>
            
        </article>
    </div>
</div>


<?php
    if ( 'POST' == $_SERVER['REQUEST_METHOD'] && ! empty($_POST['post_title']) && isset($_POST['update_post_nonce']) && isset($_POST['postcontent']) )
    {
        $post = array(
            'post_content'   => esc_sql($_POST['postcontent']),
            'post_title'     => esc_sql($_POST['post_title']),
            'post_status'    => 'publish',
            'post_type'      => 'class'
        );
        $post_ID = wp_insert_post($post);
        
    }
    var_dump(get_permalink($post_ID));
    echo "<br/>";
    var_dump(header("Location:".get_permalink($post_ID)));
    var_dump($_POST);
}




function b4l_create_class_shortcode(){
    global $current_user;
    get_currentuserinfo(); 
    
    if ( user_can( $current_user, "administrator" ) || user_can( $current_user, "b4l_badges_editor" ) || user_can( $current_user, "b4l_academy" ) || user_can( $current_user, "b4l_teacher" ) ){ 
        b4l_create_class_form();
    } else {
        echo "<p>You don't have the permissions to see the content of this page !</p>";
    }
}

/**
 * Shortcode : [create_class]. 
 * Displays the content of b4l_create_class_shortcode function.
 */
add_shortcode('create_class', 'b4l_create_class_shortcode');