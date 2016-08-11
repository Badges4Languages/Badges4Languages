<?php

function b4l_create_class_form(){
?>
<div id="primary">
    <div id="content" role="main">
    
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            
            <header class="entry-header" />
            <form id="post" class="post-edit front-end-form" method="post" enctype="multipart/form-data">

                <input type="hidden" name="post_id" value="<?php the_ID(); ?>" />
                <?php wp_nonce_field( 'update_post_'. get_the_ID(), 'update_post_nonce' ); ?>

                <p><label for="post_title">Title</label>
                <input type="text" id="post_title" name="post_title" value="<?php echo $post->post_title; ?>" /></p>

                <p><?php wp_editor( $post->post_content, 'postcontent' ); ?></p>

                <p><label for="post_title">Test</label>
                <?php $value = get_post_meta(get_the_ID(), 'edit_test', true); ?>
                <input type="text" id="edit_test" name="edit_test" value="<?php echo $value; ?>" /></p>

                <p><label for="post_title">Test 2</label>
                <?php $value = get_post_meta(get_the_ID(), 'edit_test2', true); ?>
                <input type="text" id="edit_test2" name="edit_test2" value="<?php echo $value; ?>" /></p>

                <input type="submit" id="submit" value="Update" />

            </form>
            
        </article>
    </div>
</div>


<?php
    if ( 'POST' == $_SERVER['REQUEST_METHOD'] && ! empty($_POST['post_id']) && ! empty($_POST['post_title']) && isset($_POST['update_post_nonce']) && isset($_POST['postcontent']) )
    {
        $post_id   = $_POST['post_id'];
        $post_type = get_post_type($post_id);
        $capability = ( 'page' == $post_type ) ? 'edit_page' : 'edit_post';
        if ( current_user_can($capability, $post_id) && wp_verify_nonce( $_POST['update_post_nonce'], 'update_post_'. $post_id ) )
        {
            $post = array(
                'ID'             => esc_sql($post_id),
                'post_content'   => esc_sql($_POST['postcontent']),
                'post_title'     => esc_sql($_POST['post_title'])
            );
            wp_update_post($post);

            if ( isset($_POST['edit_test']) ) update_post_meta($post_id, 'edit_test', esc_sql($_POST['edit_test']) );
            if ( isset($_POST['edit_test2']) ) update_post_meta($post_id, 'edit_test2', esc_sql($_POST['edit_test2']) );
        }
        else
        {
            wp_die("You can't do that");
        }
    }

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