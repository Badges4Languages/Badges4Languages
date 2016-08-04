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
		'supports' => array('title','editor','thumbnail','comments', 'comments', 'author', 'page-attributes'),
		'has_archive'=>true
	  ); 
	
	//Registering the custom post type
	register_post_type( 'class' , $args );
        
        //Automatic flushing of the WordPress rewrite rules
        flush_rewrite_rules();
}




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