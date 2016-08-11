<?php
/**
 * Executes the Custom Function named b4l_create_classes_register 
 * during the initialization phase.
 */
add_action('init', 'b4l_create_classes_register', 0);

/**
 * Creates the Custom Post 'class'
 * 
 * @author Alexandre LEVACHER
 * @since 1.1.3
 */
function b4l_create_classes_register(){
 
        //Declaration of the labels
	$labels = array(
		'name' =>_x('Class School', 'post type general name'),
		'singular_name' =>_x('Class', 'post type singular name'),
		'add_new' =>_x('Add New', 'class item'),
		'add_new_item' =>__('Add New Class'),
		'edit_item' =>__('Edit Class'),
		'new_item' =>__('New Class'),
		'view_item' =>__('View Class'),
		'search_items' =>__('Search Class'),
		'not_found' =>__('Nothing found'),
		'not_found_in_trash' =>__('Nothing found in Trash'),
		'parent_item_colon' => ''
	);
 
        //Declaration of the arguments
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
                'show_in_nav_menus' => true,
		'query_var' => true,
		'menu_icon' => 'dashicons-welcome-learn-more',
		'rewrite' => true,
                'map_meta_cap' => true,
		'capability_type' => array('class', 'classes'),
		'capabilities'=>array(
			'edit_post'=>'edit_class',
			'read_post'=>'read_class',
			'delete_post'=>'delete_class',
                        'delete_posts'=>'delete_classes',
                        'delete_others_posts'=>'delete_others_classes',
                        'delete_private_posts'=>'delete_private_classes',
                        'delete_published_posts'=>'delete_published_classes',
			'edit_posts'=>'edit_classes',
			'edit_others_posts'=>'edit_others_classes',
                        'edit_private_posts'=>'edit_private_classes',
                        'edit_published_posts'=>'edit_published_classes',
			'publish_posts'=>'publish_classes',
			'read_private_posts'=>'read_private_classes',
                        'b4l_send_classes_to_students'=>'b4l_send_classes_to_students', //Displays 'send_classes_to_students' page for users who have this capability
                        'b4l_import_csv_to_db'=>'b4l_import_csv_to_db', //Displays 'import_csv_to_db' page for users who have this capability
                        'b4l_classes_issuer_information'=>'b4l_classes_issuer_information' //Displays 'classes_issuer_information' page for users who have this capability
                    ),
		
		'hierarchical' => false,
		'menu_position' => 22,
		'supports' => array('title','editor','thumbnail','comments', 'author', 'page-attributes'),
		'has_archive'=>true
	  ); 
	
	//Registering the custom post type
	register_post_type( 'class' , $args );
        
        //Automatic flushing of the WordPress rewrite rules
        flush_rewrite_rules();
}



/**
 * Include the template
 */
add_filter( 'template_include', 'b4l_include_class_template', 1 );

/**
 * Adding the template for 'Single Badge' page to the list of templates.
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
 * @param File $template_path Template path
 * @return File $template_path Template path
 */
function b4l_include_class_template( $template_path ) {
    if ( get_post_type() == 'class' ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'single-class.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = WP_PLUGIN_DIR . '/badges4languages-plugin/templates/single-class.php';
            }
        }
    }
    return $template_path;
}



/**
 * Execute b4l_general_class_for_teacher during the user registration phase.
 */
add_action('user_register','b4l_general_class_for_teacher');

/**
 * Create a new custom post 'class' when a user register.
 * 
 * This class is the 'default class' for a user who will be teacher. If he wants
 * to send a certification to (a) student(s), he is not obliged to create a class
 * to associate the badge to a course: he can select this 'general class' in which
 * he can add all the certification. Therefore, the students will give a rating in
 * this class.
 * 
 * This method is functionnable but it is not the best way to do it. We make it
 * because of a lack of time and for simplicity. However every user will have a
 * 'class' (every user registered after the activation of the plugin), even the
 * user who will never be teacher. Therefore there is an important loss of memory.
 * And an user registered before the activation of the plugin will not have a
 * 'default class'.
 * 
 * In the future, it will be better to change this function to be more efficient.
 * 
 * @author Alexandre LEVACHER
 * @since 1.1.3
 * @param Bigint $user_id User ID
 */
function b4l_general_class_for_teacher($user_id){
        $user_info = get_userdata($user_id);
        $general_class = array(
            'post_title' => $user_info->display_name."'s general class",
            'post_type' => 'class',
            'post_content' => 'General class of '.$user_info->display_name,
            'post_status' => 'publish',
            'post_author' => $user_id,
            'post_date' => date("Y-m-d H:i:s"),
            'post_date_gmt' => gmdate("Y-m-d H:i:s"),
            'comment_status' => 'open',
            'ping_status' => 'closed',
            'post_name' => 'user'.$user_id.'GeneralClass',
        );

        // Insert the post into the database
        wp_insert_post( $general_class );
}