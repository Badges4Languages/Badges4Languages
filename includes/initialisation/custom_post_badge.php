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
                'map_meta_cap' => true,
		'capability_type' => array('badge', 'badges'),
		'capabilities'=>array(
			'edit_post'=>'edit_badge',
			'read_post'=>'read_badge',
			'delete_post'=>'delete_badge',
                        'delete_posts'=>'delete_badges',
                        'delete_others_posts'=>'delete_others_badges',
                        'delete_private_posts'=>'delete_private_badges',
                        'delete_published_posts'=>'delete_published_badges',
			'edit_posts'=>'edit_badges',
			'edit_others_posts'=>'edit_others_badges',
                        'edit_private_posts'=>'edit_private_badges',
                        'edit_published_posts'=>'edit_published_badges',
			'publish_posts'=>'publish_badges',
			'read_private_posts'=>'read_private_badges',
                        'b4l_send_badges_to_students'=>'b4l_send_badges_to_students', //Displays 'send_badges_to_students' page for users who have this capability
                        'b4l_import_csv_to_db'=>'b4l_import_csv_to_db', //Displays 'import_csv_to_db' page for users who have this capability
                        'b4l_badges_issuer_information'=>'b4l_badges_issuer_information' //Displays 'badges_issuer_information' page for users who have this capability
                    ),
		
		'hierarchical' => false,
		'menu_position' => 21,
		'supports' => array('title','editor','thumbnail','page-attributes'),
		'has_archive'=>true
	  ); 
	
	//Registering the custom post type
	register_post_type( 'badge' , $args );
        
        //Automatic flushing of the WordPress rewrite rules
        flush_rewrite_rules();
}