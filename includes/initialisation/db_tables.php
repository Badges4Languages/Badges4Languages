<?php
 /*
  * This file contains all the database tables.
  * 
  * @author Alexandre Levacher
  * @package badges4Languages-plugin
  * @subpackage includes/initialisation
  * @since 1.0.0
 */


/**
 * Execute the 'b4l_create_db_tables' function
 * during the initialization phase.
 */
add_action('init', 'b4l_create_db_tables', 0);

/**
 * Create the Database Tables for the Custom Post 'badge'.
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
 */
function b4l_create_db_tables() {
    b4l_create_db_table_b4l_languages();
    b4l_create_db_table_b4l_students();
    b4l_create_db_table_b4l_teachers();
    b4l_create_db_table_b4l_teacherLevels();
    b4l_create_db_table_b4l_studentLevels();
    b4l_create_db_table_b4l_skills();
    b4l_create_db_table_b4l_number_certifications();
    b4l_create_db_table_b4l_issuer_information();
    b4l_create_db_table_b4l_userStudentBadgesProfil();
    b4l_create_db_table_b4l_userTeacherBadgesProfil();
}


/**
 * Create/Update the '(prefix)b4l_languages' table
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
 */
function b4l_create_db_table_b4l_languages() {
    global $wpdb;
    $table_name = $wpdb->prefix . "b4l_languages"; 
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        language_id varchar(3) NOT NULL,
        country_id varchar(2) NOT NULL,
        language_name varchar(70) NOT NULL,
        PRIMARY KEY  (language_id),
        KEY (language_name)
) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

/**
 * Create/Update the '(prefix)b4l_students' table
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
 */
function b4l_create_db_table_b4l_students() {
    global $wpdb;
    $table_name = $wpdb->prefix . "b4l_students"; 
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        students_id bigint(20) NOT NULL AUTO_INCREMENT,
        students_name varchar(70) NOT NULL,
        students_lastname varchar(70) NOT NULL,
        students_email varchar(70) NOT NULL,
        A1 text,
        A2 text,
        B1 text,
        B2 text,
        C1 text,
        C2 text,
        UNIQUE KEY students_id (students_id)
) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

/**
 * Create/Update the '(prefix)b4l_students' table
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
 */
function b4l_create_db_table_b4l_teachers() {
    global $wpdb;
    $table_name = $wpdb->prefix . "b4l_teachers"; 
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        teachers_id bigint(20) NOT NULL AUTO_INCREMENT,
        teachers_name varchar(70) NOT NULL,
        teachers_lastname varchar(70) NOT NULL,
        teachers_email varchar(70) NOT NULL,
        T1 text,
        T2 text,
        T3 text,
        T4 text,
        T5 text,
        T6 text,
        UNIQUE KEY teachers_id (teachers_id)
) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

/**
 * Create/Update the '(prefix)b4l_teacherLevels' table
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
 */
function b4l_create_db_table_b4l_teacherLevels() {
    global $wpdb;
    $table_name = $wpdb->prefix . "b4l_teacherLevels"; 
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        language varchar(70) NOT NULL,
        T1 text NOT NULL,
        T2 text NOT NULL,
        T3 text NOT NULL,
        T4 text NOT NULL,
        T5 text NOT NULL,
        T6 text NOT NULL,
        PRIMARY KEY  (language)
  ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

/**
 * Create/Update the '(prefix)b4l_studentLevels' table
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
 */
function b4l_create_db_table_b4l_studentLevels() {
    global $wpdb;
    $table_name = $wpdb->prefix . "b4l_studentLevels"; 
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE $table_name (
        language varchar(70) NOT NULL,
        A1 text NOT NULL,
        A2 text NOT NULL,
        B1 text NOT NULL,
        B2 text NOT NULL,
        C1 text NOT NULL,
        C2 text NOT NULL,
        PRIMARY KEY  (language)
  ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

/**
 * Create/Update the '(prefix)b4l_skills' table
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
 */
function b4l_create_db_table_b4l_skills() {
    global $wpdb;
    $table_name = $wpdb->prefix . "b4l_skills"; 
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id varchar(10) NOT NULL,
        language varchar(3) NOT NULL,
        level varchar(5) NOT NULL,
        listening text NOT NULL,
        reading text NOT NULL,
        spoken_interaction text NOT NULL,
        spoken_production text NOT NULL,
        writing text NOT NULL,
        PRIMARY KEY  (id)
) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

/**
 * Create/Update the '(prefix)b4l_number_certifications' table
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
 */
function b4l_create_db_table_b4l_number_certifications() {
    global $wpdb;
    $table_name = $wpdb->prefix . "b4l_number_certifications"; 
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        A1 text,
        A2 text,
        B1 text,
        B2 text,
        C1 text,
        C2 text,
        T1 text,
        T2 text,
        T3 text,
        T4 text,
        T5 text,
        T6 text,
        UNIQUE KEY id (id)
) $charset_collate;";
    
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

/**
 * Create/Update the '(prefix)b4l_issuer_information' table
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
 */
function b4l_create_db_table_b4l_issuer_information() {
    global $wpdb;
    $table_name = $wpdb->prefix . "b4l_issuer_information"; 
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id bigint(20) NOT NULL,
        issuer_name text,
        issuer_logo text,
        issuer_email text,
        issuer_url text,
        UNIQUE KEY id (id)
) $charset_collate;";
    
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

/**
 * Create/Update the '(prefix)b4l_userStudentBadgesProfil' table
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
 */
function b4l_create_db_table_b4l_userStudentBadgesProfil() {
    global $wpdb;
    $table_name = $wpdb->prefix . "b4l_userStudentBadgesProfil"; 
    $charset_collate = $wpdb->get_charset_collate();
    
    //user_badge_id is a combination of user_id + badge_level + badge_language
    $sql = "CREATE TABLE $table_name (
        user_badge_id varchar(100) NOT NULL,
        user_id bigint(20) NOT NULL,
        badge_level text NOT NULL,
        badge_language text NOT NULL,
        badge_date date NOT NULL,
        badge_image text NOT NULL,
        badge_teacher text NOT NULL,
        badge_comment text NOT NULL,
        PRIMARY KEY  (user_badge_id)
  ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

/**
 * Create/Update the '(prefix)b4l_userTeacherBadgesProfil' table
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
 */
function b4l_create_db_table_b4l_userTeacherBadgesProfil() {
    global $wpdb;
    $table_name = $wpdb->prefix . "b4l_userTeacherBadgesProfil"; 
    $charset_collate = $wpdb->get_charset_collate();
    
    //id is a combination of user_id + badge_level + badge_language + badge_teacher
    $sql = "CREATE TABLE $table_name (
        user_badge_id varchar(100) NOT NULL,
        user_id bigint(20) NOT NULL,
        badge_level text NOT NULL,
        badge_language text NOT NULL,
        badge_date date NOT NULL,
        badge_image text NOT NULL,
        badge_teacher text NOT NULL,
        badge_comment text NOT NULL,
        PRIMARY KEY  (user_badge_id)
  ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}