<?php
 /*
  * Description:        This file contains all the custom taxonomies of the
  *                     custom post 'badge'.
  * Version:            1.0.0
  * Author:             Alexandre Levacher
 */

/**
 * Creates the Custom Taxonomies 'TeacherLevels' (T1,T2,T3,etc).
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
 */
function b4l_create_TeacherLevels_taxonomies() {
    register_taxonomy(
        'badge_teacherlevels',
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
        'badge_studentlevels',
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
        'badge_skills',
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
 * Creates the Custom Taxonomies 'Badges Categories'.
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
 */
function b4l_create_Badges_Categories_taxonomies() {
    register_taxonomy(
        'badge_badges_categories',
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