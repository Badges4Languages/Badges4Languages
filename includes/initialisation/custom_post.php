<?php
/**
 * Executes the Custom Function named b4l_create_badges_register 
 * during the initialization phase.
 */
add_action('init', 'b4l_create_badges_register', 0);

/**
 * Creates the Custom Post 'badge'
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
 */
function b4l_create_badges_register(){
 
        //Declaration of the labels
	$labels = array(
		'name' =>_x('Badge School', 'post type general name'),
		'singular_name' =>_x('Badge', 'post type singular name'),
		'add_new' =>_x('Add New', 'badge item'),
		'add_new_item' =>__('Add New Badge'),
		'edit_item' =>__('Edit Badge'),
		'new_item' =>__('New Badge'),
		'view_item' =>__('View Badge'),
		'search_items' =>__('Search Badge'),
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
		'capability_type' => 'post',
		//Capabilities just for admin (only admin can see the custom post)
		'capabilities'=>array(
			'edit_post'=>'update_core',
			'read_post'=>'update_core',
			'delete_post'=>'update_core',
			'edit_posts'=>'b4l_edit_badges',
			'edit_others_posts'=>'b4l_edit_others_badges',
			'publish_posts'=>'b4l_publish_badges',
			'read_private_posts'=>'b4l_read_private_badges',
                        'b4l_send_badges_to_students'=>'b4l_send_badges_to_students', //Displays 'send_badges_to_students' page for users who have this capability
                        'b4l_import_csv_to_db'=>'b4l_import_csv_to_db', //Displays 'import_csv_to_db' page for users who have this capability
                        'b4l_badges_issuer_information'=>'b4l_badges_issuer_information' //Displays 'badges_issuer_information' page for users who have this capability
		),
		
		'hierarchical' => false,
		'menu_position' => 15,
		'supports' => array('title','editor','thumbnail','page-attributes'),
		'has_archive'=>true
	  ); 
	
	//Registering the custom post type
	register_post_type( 'badge' , $args );
        
        //Automatic flushing of the WordPress rewrite rules
        flush_rewrite_rules();
}