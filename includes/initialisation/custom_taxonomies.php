<?php
 /*
  * This file contains all the custom taxonomies of the custom post 'badge'.
  * 
  * @author Alexandre Levacher
  * @package badges4Languages-plugin
  * @subpackage includes/initialisation
  * @since 1.0.0
 */


/**
 * Execute b4l_create_my_taxonomies during the initialization phase.
 */
add_action( 'init', 'b4l_create_my_taxonomies', 0 );

/**
 * Create the Custom Taxonomies (categories) for the Custom Post 'badge'.
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
 */
function b4l_create_my_taxonomies() {
    b4l_create_TeacherLevels_taxonomies();
    b4l_create_StudentLevels_taxonomies();
    b4l_create_Skills_taxonomies();
    b4l_create_Badges_Categories_taxonomies();
}


/**
 * Creates the Custom Taxonomies 'TeacherLevels' (T1,T2,T3,etc).
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
 */
function b4l_create_TeacherLevels_taxonomies() {
    register_taxonomy(
        'badges_teachers_levels',
        'badge',
        array(
            'labels' => array(
                'name' => 'Teacher Levels',
                'add_new_item' => 'Add New Teacher Level',
                'new_item_name' => "New Teacher Level"
            ),
            'show_ui' => true,
            'show_tagcloud' => false,
            'hierarchical' => true
        )
    );
}

/**
 * Creates the Custom Taxonomies 'StudentLevels' (A1,A2,B1,etc).
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
 */
function b4l_create_StudentLevels_taxonomies() {
    register_taxonomy(
        'badges_students_levels',
        'badge',
        array(
            'labels' => array(
                'name' => 'Student Levels',
                'add_new_item' => 'Add New Student Level',
                'new_item_name' => "New Student Level"
            ),
            'show_ui' => true,
            'show_tagcloud' => false,
            'hierarchical' => true
        )
    );
}

/**
 * Creates the Custom Taxonomies 'Skills' (Listening, Reading, etc).
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
 */
function b4l_create_Skills_taxonomies() {
    register_taxonomy(
        'badges_skills',
        'badge',
        array(
            'labels' => array(
                'name' => 'Skills',
                'add_new_item' => 'Add New Skill',
                'new_item_name' => "New Skill"
            ),
            'show_ui' => true,
            'show_tagcloud' => false,
            'hierarchical' => true
        )
    );
}

/**
 * Creates the Custom Taxonomies 'BadgesCategories'.
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
 */
function b4l_create_Badges_Categories_taxonomies() {
    register_taxonomy(
        'badges_categories',
        'badge',
        array(
            'labels' => array(
                'name' => 'Badges Categories',
                'add_new_item' => 'Add New Badges Category',
                'new_item_name' => "New Badges Category"
            ),
            'show_ui' => true,
            'show_tagcloud' => false,
            'hierarchical' => true
        )
    );
}