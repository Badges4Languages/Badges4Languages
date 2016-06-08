<?php
/**
 * Creates the Custom Taxonomies 'TeacherLevels' (T1,T2,T3,etc).
 */
function b4l_create_TeacherLevels_taxonomies() {
    register_taxonomy(
        'badge_teacherlevels',
        'badge',
        array(
            'labels' => array(
                'name' => 'Teacher Levels',
                'add_new_item' => 'Add New TeacherLevel',
                'new_item_name' => "New TeacherLevel"
            ),
            'show_ui' => true,
            'show_tagcloud' => false,
            'hierarchical' => true
        )
    );
}

/**
 * Creates the Custom Taxonomies 'StudentLevels' (A1,A2,B1,etc).
 */
function b4l_create_StudentLevels_taxonomies() {
    register_taxonomy(
        'badge_studentlevels',
        'badge',
        array(
            'labels' => array(
                'name' => 'Student Levels',
                'add_new_item' => 'Add New StudentLevel',
                'new_item_name' => "New StudentLevel"
            ),
            'show_ui' => true,
            'show_tagcloud' => false,
            'hierarchical' => true
        )
    );
}

/**
 * Creates the Custom Taxonomies 'Skills' (Listening, Reading, etc).
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
 * Creates the Custom Taxonomies 'Tags'.
 */
function b4l_create_Tags_taxonomies() {
    register_taxonomy(
        'badge_tags',
        'badge',
        array(
            'labels' => array(
                'name' => 'Tags',
                'add_new_item' => 'Add New Tag',
                'new_item_name' => "New Tag"
            ),
            'show_ui' => true,
            'show_tagcloud' => false,
            'hierarchical' => true
        )
    );
}


// More information about the custom post and taxomonies here :
// http://code.tutsplus.com/tutorials/a-guide-to-wordpress-custom-post-types-creation-display-and-meta-boxes--wp-27645
// http://code.tutsplus.com/tutorials/a-guide-to-wordpress-custom-post-types-taxonomies-admin-columns-filters-and-archives--wp-27898