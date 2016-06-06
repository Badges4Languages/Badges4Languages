<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.badges4languages.org
 * @since             1.0.0
 * @package           Badges4languages_Plugin
 *
 * @wordpress-plugin
 * Plugin Name:       Badges4languages-plugin
 * Plugin URI:        http://www.badges4languages.org
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Alexandre Levacher
 * Author URI:        http://www.badges4languages.org
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       badges4languages-plugin
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-badges4languages-plugin-activator.php
 */
function activate_badges4languages_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-badges4languages-plugin-activator.php';
	Badges4languages_Plugin_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-badges4languages-plugin-deactivator.php
 */
function deactivate_badges4languages_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-badges4languages-plugin-deactivator.php';
	Badges4languages_Plugin_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_badges4languages_plugin' );
register_deactivation_hook( __FILE__, 'deactivate_badges4languages_plugin' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-badges4languages-plugin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_badges4languages_plugin() {

	$plugin = new Badges4languages_Plugin();
	$plugin->run();

}
run_badges4languages_plugin();




/**************************************************************************
 ************************** CREATION/DECLARATION **************************
 *************************************************************************/

/**
 * Executes the 'b4l_create_db_table_b4l_languages' function
 * during the initialization phase.
 */
add_action('init', 'b4l_create_db_table_b4l_languages');

/**
 * Create/Update the '(prefix)b4l_languages' table
 */
function b4l_create_db_table_b4l_languages() {
    global $wpdb;
    $table_name = $wpdb->prefix . "b4l_languages"; 
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        langue_id varchar(3) NOT NULL,
        country_id varchar(2) NOT NULL,
        country_name varchar(70) NOT NULL,
        PRIMARY KEY langue_id (langue_id)
  ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

/**
 * Executes the 'b4l_create_db_table_b4l_teacherLevels' function
 * during the initialization phase.
 */
add_action('init', 'b4l_create_db_table_b4l_teacherLevels');

/**
 * Create/Update the '(prefix)b4l_teacherLevels' table
 */
function b4l_create_db_table_b4l_teacherLevels() {
    global $wpdb;
    $table_name = $wpdb->prefix . "b4l_teacherLevels"; 
    $charset_collate = $wpdb->get_charset_collate();
    
    /*
     * Definition of the database table with an id (int)
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        language varchar(15) NOT NULL,
        T1 text NOT NULL,
        T2 text NOT NULL,
        T3 text NOT NULL,
        T4 text NOT NULL,
        T5 text NOT NULL,
        T6 text NOT NULL,
        UNIQUE KEY id (id)
  ) $charset_collate;";
     */

    $sql = "CREATE TABLE $table_name (
        language varchar(15) NOT NULL,
        T1 text NOT NULL,
        T2 text NOT NULL,
        T3 text NOT NULL,
        T4 text NOT NULL,
        T5 text NOT NULL,
        T6 text NOT NULL,
        PRIMARY KEY language (language)
  ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

/**
 * Executes the 'b4l_create_db_table_b4l_studentLevels' function
 * during the initialization phase.
 */
add_action('init', 'b4l_create_db_table_b4l_studentLevels');

/**
 * Create/Update the '(prefix)b4l_studentLevels' table
 */
function b4l_create_db_table_b4l_studentLevels() {
    global $wpdb;
    $table_name = $wpdb->prefix . "b4l_studentLevels"; 
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE $table_name (
        language varchar(15) NOT NULL,
        A1 text NOT NULL,
        A2 text NOT NULL,
        B1 text NOT NULL,
        B2 text NOT NULL,
        C1 text NOT NULL,
        C2 text NOT NULL,
        PRIMARY KEY language (language)
  ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

/**
 * Executes the 'b4l_create_db_table_b4l_skills' function
 * during the initialization phase.
 */
add_action('init', 'b4l_create_db_table_b4l_skills');

/**
 * Create/Update the '(prefix)b4l_skills' table
 */
function b4l_create_db_table_b4l_skills() {
    global $wpdb;
    $table_name = $wpdb->prefix . "b4l_skills"; 
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        language varchar(15) NOT NULL,
        Listening text NOT NULL,
        Reading text NOT NULL,
        Speaking text NOT NULL,
        Writing text NOT NULL,
        PRIMARY KEY language (language)
) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

/**
 * Executes the Custom Function named b4l_create_badges_register 
 * during the initialization phase.
 */
add_action('init', 'b4l_create_badges_register');

/**
 * Creates the Custom Post
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
		'query_var' => true,
		'menu_icon' => 'dashicons-welcome-learn-more',
		'rewrite' => true,
		'capability_type' => 'post',
		//Capabilities just for admin (only admin can see the custom post)
		'capabilities'=>array(
			'edit_post'=>'update_core',
			'read_post'=>'update_core',
			'delete_post'=>'update_core',
			'edit_posts'=>'update_core',
			'edit_others_posts'=>'update_core',
			'publish_posts'=>'update_core',
			'read_private_posts'=>'update_core'
		),
		
		'hierarchical' => false,
		'menu_position' => 15,
		'supports' => array('title','editor','thumbnail','page-attributes'),
		'has_archive'=>true
	  ); 
	
	//Registering the custom post type
	register_post_type( 'badge' , $args );
        
        //flush_rewrite_rules();    A EVITER !!!!!!!!!!!!
}

/**
 * Executes b4l_create_my_taxonomies during the initialization phase.
 */
add_action( 'init', 'b4l_create_my_taxonomies', 0 );

/**
 * Creates the Custom Taxonomies (categories) for the 
 * Custom Post 'badge'.
 */
function b4l_create_my_taxonomies() {
    b4l_create_TeacherLevels_taxonomies();
    b4l_create_StudentLevels_taxonomies();
    b4l_create_Skills_taxonomies();
    b4l_create_Tags_taxonomies();
}

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










//PEUT ETRE A DEGAGER  !!!!!!!!!!!!!!!!!!!!
/**
 * Executes b4l_my_admin when the WordPress Admin interface is visited.
 */
add_action( 'admin_init', 'b4l_my_admin' );

/**
 * Registers and associates a MetaBox with the 'badge' Custom Post
 */
function b4l_my_admin() {
    add_meta_box( 'translation_meta_box',
        'Translation to another language',
        'b4l_display_translation_meta_box',
        'badge', 'normal', 'high'
    );
}

/**
 * Displays the MetaBox on the page
 */
function b4l_display_translation_meta_box( $badge ) {
    $translation_language = esc_html( get_post_meta( $badge->ID, 'translation_language', true ) );
    $translation_text = esc_html( get_post_meta( $badge->ID, 'translation_text', true ) );
    ?>
    <table>
        <tr>
            <td style="width: 150px">Your language</td>
            <td>
                <!-- Selects the language of the translation -->
                <select style="width: 100px" name="badge_translation_language">
                    <?php 
                    $filename = WP_PLUGIN_DIR . '/badges4languages-plugin/assets/languages.csv';
                    $lines = explode(PHP_EOL, file_get_contents($filename));
                    for ( $counter = 1; $counter < count($lines) - 1; $counter++ ) { 
                        $firstLineElement = explode(",", $lines[$counter]);
                    ?>
                    <option value="<?php echo $firstLineElement[0]; ?>"> 
                        <?php echo $firstLineElement[0];?>
                    </option>
                    <?php 
                    }
                    ?>
                </select>
            </td>
        </tr>
        
        
        <tr>
            <td style="width: 150px">Your level</td>
            <td>
                <!-- Selects the language of the translation -->
                <select style="width: 100px" name="badge_translation_language">
                    <?php 
                    $filename1 = WP_PLUGIN_DIR . '/badges4languages-plugin/assets/languages.csv';
                    $lines = explode(PHP_EOL, file_get_contents($filename1));
                    $colonnes = explode(",", $lines[0]);
                    for ( $counter = 1; $counter < count($colonnes) - 1; $counter++ ) { 
                        $firstColumnElement = explode(PHP_EOL, $colonnes[$counter]);
                    ?>
                    <option value="<?php echo $firstColumnElement[0]; ?>"> 
                        <?php echo $firstColumnElement[0];?>
                    </option>
                    <?php 
                    }
                    ?>
                </select>
            </td>
        </tr>
        
        
        <tr>
            <!-- Builds the TextField of the translation -->
            <td style="width: 100%">Your text</td>
            <td><input type="text" size="80" name="badge_translation_text" placeholder="Test" value=" <?php echo $translation_text; ?>" /></td>
        </tr>
    </table>
    <?php
}

/**
add_action( 'save_post', 'b4l_add_movie_review_fields', 10, 2 );
function b4l_add_movie_review_fields( $movie_review_id, $movie_review ) {
    // Check post type for movie reviews
    if ( $movie_review->post_type == 'movie_reviews' ) {
        // Store data in post meta table if present in post data
        if ( isset( $_POST['movie_review_director_name'] ) && $_POST['movie_review_director_name'] != '' ) {
            update_post_meta( $movie_review_id, 'movie_director', $_POST['movie_review_director_name'] );
        }
        if ( isset( $_POST['movie_review_rating'] ) && $_POST['movie_review_rating'] != '' ) {
            update_post_meta( $movie_review_id, 'movie_rating', $_POST['movie_review_rating'] );
        }
    }
}

*/


//DECLARATION OF THE TEMPLATE
add_filter( 'template_include', 'b4l_include_template_function', 1 );
function b4l_include_template_function( $template_path ) {
    if ( get_post_type() == 'badge' ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'single-badge.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . 'templates/single-badge.php';
            }
        }
    }
    return $template_path;
}






















add_action('admin-print-styles', 'bsp_admin_styles');
add_action('admin_print_scripts', 'bsp_admin_scripts');
add_action( 'wp_ajax_bsp_award_ajax', 'bsp_award_ajax_handle' );
add_action( 'wp_ajax_nopriv_bsp_award_ajax', 'bsp_award_ajax_handle' );
function bsp_issuer_api(){
	//wp_enqueue_script( 'openbadges', 'https://backpack.openbadges.org/issuer.js', array()); //for issuer API //not working if included like that, don't know why
	/*wp_enqueue_script( 'bsp-awards', plugins_url( 'js/award_badge.js', __FILE__ ), array( 'jquery' ) );*/
	wp_localize_script( 'bsp-awards', 'BSP_Awards', array(
                'ajaxurl'       => admin_url( 'admin-ajax.php' ),
            ) );
	wp_enqueue_script('custom', plugins_url( 'js/scripts.js', __FILE__ ), array( 'jquery' ));
}

//function for ajax request
function bsp_award_ajax_handle(){
	wp_die();
}

//enqueueing scripts
function bsp_admin_scripts(){
	wp_enqueue_script('media-upload'); //for wp media upload
	wp_enqueue_script('thickbox'); //for wp media upload
	wp_enqueue_script('jquery-ui-dialog');  // For admin panel popup alerts
	
	wp_register_script( 'wp_csv_to_db', plugins_url( 'js/admin_page.js', __FILE__ ), array('jquery','media-upload','thickbox') );  //including external admin_page javascript file
	wp_enqueue_script('wp_csv_to_db');
	wp_localize_script( 'wp_csv_to_db', 'bsp_pass_js_vars', array( 'ajax_image' => plugin_dir_url( __FILE__ ).'images/loading.gif', 'ajaxurl' => admin_url('admin-ajax.php') ) );
	wp_enqueue_media();
}

//function for enqueueing styles
function bsp_admin_styles(){
	wp_enqueue_style('thickbox');
}



//CODE HTML POUR L UPLOAD
add_action('admin_menu', 'b4l_csv_custom_submenu_page');

function b4l_csv_custom_submenu_page() {
    add_submenu_page(
        'edit.php?post_type=badge',
        'CSV File Upload',
        'CSV File Upload',
        'manage_options',
        'csv-custom-submenu-page',
        'b4l_csv_custom_submenu_page_callback' );
}
 
function b4l_csv_custom_submenu_page_callback() {
    //require_once plugin_dir_path( __FILE__ ) . 'wp-csv-to-database/wp-csv-to-database.php'; 
}
















// IMPORTATION OF THE PLUGIN CSV TO DB (~ 700 lignes de code)

class wp_csv_to_db {

	// Setup options variables
	protected $option_name = 'wp_csv_to_db';  // Name of the options array
	protected $data = array(  // Default options values
		'jq_theme' => 'smoothness'
	);
	
	
	public function __construct() {
		
		// Check if is admin
		// We can later update this to include other user roles
		if (is_admin()) {
                        add_action( 'plugins_loaded', array( $this, 'wp_csv_to_db_plugins_loaded' ));//Handles tasks that need to be done at plugins loaded stage.
			add_action( 'admin_menu', array( $this, 'wp_csv_to_db_register' ));  // Create admin menu page
			add_action( 'admin_init', array( $this, 'wp_csv_to_db_settings' ) ); // Create settings
			register_activation_hook( __FILE__ , array($this, 'wp_csv_to_db_activate')); // Add settings on plugin activation
		}
	}
	
        public function wp_csv_to_db_plugins_loaded(){
            $this->handle_csv_export_action();
        }
        
	public function wp_csv_to_db_activate() {
		update_option($this->option_name, $this->data);
	}
	
	public function wp_csv_to_db_register(){
    	$wp_csv_to_db_page = add_submenu_page( 'options-general.php', __('WP CSV/DB','wp_csv_to_db'), __('WP CSV/DB','wp_csv_to_db'), 'manage_options', 'wp_csv_to_db_menu_page', array( $this, 'wp_csv_to_db_menu_page' )); // Add submenu page to "Settings" link in WP
		add_action( 'admin_print_scripts-' . $wp_csv_to_db_page, array( $this, 'wp_csv_to_db_admin_scripts' ) );  // Load our admin page scripts (our page only)
		add_action( 'admin_print_styles-' . $wp_csv_to_db_page, array( $this, 'wp_csv_to_db_admin_styles' ) );  // Load our admin page stylesheet (our page only)
	}
	
	public function wp_csv_to_db_settings() {
		register_setting('wp_csv_to_db_options', $this->option_name, array($this, 'wp_csv_to_db_validate'));
	}
	
	public function wp_csv_to_db_validate($input) {
            $valid = array();
            $valid['jq_theme'] = $input['jq_theme'];
            return $valid;
	}
	
	public function wp_csv_to_db_admin_scripts() {
		wp_enqueue_script('media-upload');  // For WP media uploader
		wp_enqueue_script('thickbox');  // For WP media uploader
		wp_enqueue_script('jquery-ui-tabs');  // For admin panel page tabs
		wp_enqueue_script('jquery-ui-dialog');  // For admin panel popup alerts
		
		wp_enqueue_script( 'wp_csv_to_db', plugins_url( '/js/admin_page.js', __FILE__ ), array('jquery') );  // Apply admin page scripts
		wp_localize_script( 'wp_csv_to_db', 'wp_csv_to_db_pass_js_vars', array( 'ajax_image' => plugin_dir_url( __FILE__ ).'images/loading.gif', 'ajaxurl' => admin_url('admin-ajax.php') ) );
	}
	
	public function wp_csv_to_db_admin_styles() {
		wp_enqueue_style('thickbox');  // For WP media uploader
		wp_enqueue_style('sdm_admin_styles', plugins_url( '/css/admin_page.css', __FILE__ ));  // Apply admin page styles
		
		// Get option for jQuery theme
		$options = get_option($this->option_name);
		$select_theme = isset($options['jq_theme']) ? $options['jq_theme'] : 'smoothness';
		?><link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/<?php echo $select_theme; ?>/jquery-ui.css"><?php  // For jquery ui styling - Direct from jquery
	}

        public function handle_csv_export_action(){
            	if ((isset($_POST['export_to_csv_button'])) && (!empty($_POST['table_select']))) {
                    if(!current_user_can('manage_options')){
                        wp_die('Error! Only site admin can perform this operation');
                    }

                    $this->CSV_GENERATE($_POST['table_select']);
		}
        }
        
	// Helper function for .csv file exportation
	public function CSV_GENERATE($getTable) {
                //https://gist.github.com/umairidrees/8952054#file-php-save-db-table-as-csv
                $con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die( "Unable to Connect database");
                mysql_select_db(DB_NAME,$con) or die( "Unable to select database");
                // Table Name that you want
                // to export in csv

                $FileName = "_export.csv";
                $file = fopen($FileName,"w");

                $sql = mysql_query("SELECT * FROM ".$getTable."");
                $row = mysql_fetch_assoc($sql);
                // Save headings alon
                        $HeadingsArray=array();
                        foreach($row as $name => $value){
                                $HeadingsArray[]=$name;
                        }
                        fputcsv($file,$HeadingsArray); 

                // Save all records without headings

                        while($row = mysql_fetch_assoc($sql)){
                        $valuesArray=array();
                                foreach($row as $name => $value){
                                $valuesArray[]=$value;
                                }
                        fputcsv($file,$valuesArray); 
                        }
                        fclose($file);

                header("Location: $FileName");
	}
	
	public function wp_csv_to_db_menu_page() {

                if(!current_user_can('manage_options')){
                    wp_die('Error! Only site admin can perform this operation');
                }
            
		// Set variables		
		global $wpdb;
		$error_message = '';
		$success_message = '';
		$message_info_style = '';
		
		//
		// If Delete Table button was pressed
		if(!empty($_POST['delete_db_button_hidden'])) {
			
			$del_qry = 'DROP TABLE '.$_POST['table_select'];
			$del_qry_success = $wpdb->query($del_qry);
			
			if($del_qry_success) {
				$success_message .= __('Congratulations!  The database table has been deleted successfully.','wp_csv_to_db');
			}
			else {
				$error_message .= '* '.__('Error deleting table. Please verify the table exists.','wp_csv_to_db');
			}
		}
		
		if ((isset($_POST['export_to_csv_button'])) && (empty($_POST['table_select']))) {
			$error_message .= '* '.__('No Database Table was selected to export. Please select a Database Table for exportation.','wp_csv_to_db').'<br />';
		}
		
		// If button is pressed to "Import to DB"
		if (isset($_POST['execute_button'])) {
			
			// If the "Select Table" input field is empty
			if(empty($_POST['table_select'])) {
				$error_message .= '* '.__('No Database Table was selected. Please select a Database Table.','wp_csv_to_db').'<br />';
			}
			// If the "Select Input File" input field is empty
			if(empty($_POST['csv_file'])) {
				$error_message .= '* '.__('No Input File was selected. Please enter an Input File.','wp_csv_to_db').'<br />';
			}
			// Check that "Input File" has proper .csv file extension
			$ext = pathinfo($_POST['csv_file'], PATHINFO_EXTENSION);
			if($ext !== 'csv') {
				$error_message .= '* '.__('The Input File does not contain the .csv file extension. Please choose a valid .csv file.','wp_csv_to_db');
			}
			
			// If all fields are input; and file is correct .csv format; continue
			if(!empty($_POST['table_select']) && !empty($_POST['csv_file']) && ($ext === 'csv')) {
				
				// If "disable auto_inc" is checked.. we need to skip the first column of the returned array (or the column will be duplicated)
				if(isset($_POST['remove_autoinc_column'])) {
					$db_cols = $wpdb->get_col( "DESC " . $_POST['table_select'], 0 );  
					unset($db_cols[0]);  // Remove first element of array (auto increment column)
				} 
				// Else we just grab all columns
				else {
					$db_cols = $wpdb->get_col( "DESC " . $_POST['table_select'], 0 );  // Array of db column names
				}
				// Get the number of columns from the hidden input field (re-auto-populated via jquery)
				$numColumns = $_POST['num_cols'];
				
				// Open the .csv file and get it's contents
				if(( $fh = @fopen($_POST['csv_file'], 'r')) !== false) {
					
					// Set variables
					$values = array();
					$too_many = '';  // Used to alert users if columns do not match
					
					while(( $row = fgetcsv($fh)) !== false) {  // Get file contents and set up row array
						if(count($row) == $numColumns) {  // If .csv column count matches db column count
							$values[] = '("' . implode('", "', $row) . '")';  // Each new line of .csv file becomes an array
						}
					}
					
					// If user elects to input a starting row for the .csv file
					if(isset($_POST['sel_start_row']) && (!empty($_POST['sel_start_row']))) {
						
						// Get row number from user
						$num_var = $_POST['sel_start_row'] - 1;  // Subtract one to make counting easy on the non-techie folk!  (1 is actually 0 in binary)
						
						// If user input number exceeds available .csv rows
						if($num_var > count($values)) {
							$error_message .= '* '.__('Starting Row value exceeds the number of entries being updated to the database from the .csv file.','wp_csv_to_db').'<br />';
							$too_many = 'true';  // set alert variable
						}
						// Else splice array and remove number (rows) user selected
						else {
							$values = array_slice($values, $num_var);
						}
					}
					
					// If there are no rows in the .csv file AND the user DID NOT input more rows than available from the .csv file
					if( empty( $values ) && ($too_many !== 'true')) {
						$error_message .= '* '.__('Columns do not match.','wp_csv_to_db').'<br />';
						$error_message .= '* '.__('The number of columns in the database for this table does not match the number of columns attempting to be imported from the .csv file.','wp_csv_to_db').'<br />';
						$error_message .= '* '.__('Please verify the number of columns attempting to be imported in the "Select Input File" exactly matches the number of columns displayed in the "Table Preview".','wp_csv_to_db').'<br />';
					}
					else {
						// If the user DID NOT input more rows than are available from the .csv file
						if($too_many !== 'true') {
							
							$db_query_update = '';
							$db_query_insert = '';
								
							// Format $db_cols to a string
							$db_cols_implode = implode(',', $db_cols);
								
							// Format $values to a string
							$values_implode = implode(',', $values);
							
							
							// If "Update DB Rows" was checked
							if (isset($_POST['update_db'])) {
								
								// Setup sql 'on duplicate update' loop
								$updateOnDuplicate = ' ON DUPLICATE KEY UPDATE ';
								foreach ($db_cols as $db_col) {
									$updateOnDuplicate .= "$db_col=VALUES($db_col),";
								}
								$updateOnDuplicate = rtrim($updateOnDuplicate, ',');
								
								
								$sql = 'INSERT INTO '.$_POST['table_select'] . ' (' . $db_cols_implode . ') ' . 'VALUES ' . $values_implode.$updateOnDuplicate;
								$db_query_update = $wpdb->query($sql);
							}
							else {
								$sql = 'INSERT INTO '.$_POST['table_select'] . ' (' . $db_cols_implode . ') ' . 'VALUES ' . $values_implode;
								$db_query_insert = $wpdb->query($sql);
							}
							
							// If db db_query_update is successful
							if ($db_query_update) {
								$success_message = __('Congratulations!  The database has been updated successfully.','wp_csv_to_db');
							}
							// If db db_query_insert is successful
							elseif ($db_query_insert) {
								$success_message = __('Congratulations!  The database has been updated successfully.','wp_csv_to_db');
								$success_message .= '<br /><strong>'.count($values).'</strong> '.__('record(s) were inserted into the', 'wp_csv_to_db').' <strong>'.$_POST['table_select'].'</strong> '.__('database table.','wp_csv_to_db');
							}
							// If db db_query_insert is successful AND there were no rows to udpate
							elseif( ($db_query_update === 0) && ($db_query_insert === '') ) {
								$message_info_style .= '* '.__('There were no rows to update. All .csv values already exist in the database.','wp_csv_to_db').'<br />';
							}
							else {
								$error_message .= '* '.__('There was a problem with the database query.','wp_csv_to_db').'<br />';
								$error_message .= '* '.__('A duplicate entry was found in the database for a .csv file entry.','wp_csv_to_db').'<br />';
								$error_message .= '* '.__('If necessary; please use the option below to "Update Database Rows".','wp_csv_to_db').'<br />';
							}
						}
					}
				}
				else {
					$error_message .= '* '.__('No valid .csv file was found at the specified url. Please check the "Select Input File" field and ensure it points to a valid .csv file.','wp_csv_to_db').'<br />';
				}
			}
		}
		
		// If there is a message - info-style
		if(!empty($message_info_style)) {
			echo '<div class="info_message_dismiss">';
			echo $message_info_style;
			echo '<br /><em>('.__('click to dismiss','wp_csv_to_db').')</em>';
			echo '</div>';
		}
		
		// If there is an error message	
		if(!empty($error_message)) {
			echo '<div class="error_message">';
			echo $error_message;
			echo '<br /><em>('.__('click to dismiss','wp_csv_to_db').')</em>';
			echo '</div>';
		}
		
		// If there is a success message
		if(!empty($success_message)) {
			echo '<div class="success_message">';
			echo $success_message;
			echo '<br /><em>('.__('click to dismiss','wp_csv_to_db').')</em>';
			echo '</div>';
		}
		?>
		<div class="wrap">
        
            <h2><?php _e('WordPress CSV to Database Options','wp_csv_to_db'); ?></h2>
            
            <p>This plugin allows you to insert CSV file data into your WordPress database table. You can also export the content of a database using this plugin.</p>            
            
            <div id="tabs">
                
        	<form id="wp_csv_to_db_form" method="post" action="">
                    <table class="form-table"> 
                        
                        <tr valign="top"><th scope="row"><?php _e('Select Database Table:','wp_csv_to_db'); ?></th>
                            <td>
                                <select id="table_select" name="table_select" value="">
                                <option name="" value=""></option>
                                
                                <?php 
                                global $wpdb;
                                $repop_table=$wpdb->prefix."b4l_languages";
                                ?>
                                <option name="<?php echo $repop_table ?>" value="<?php echo $repop_table ?>" ><?php echo $repop_table ?></option>
                                
                                <?php $repop_table=$wpdb->prefix."b4l_skills"; ?>
                                <option name="<?php echo $repop_table ?>" value="<?php echo $repop_table ?>" ><?php echo $repop_table ?></option>
                                
                                <?php $repop_table=$wpdb->prefix."b4l_studentLevels"; ?>
                                <option name="<?php echo $repop_table ?>" value="<?php echo $repop_table ?>" ><?php echo $repop_table ?></option>
                                
                                <?php $repop_table=$wpdb->prefix."b4l_teacherLevels"; ?>
                                <option name="<?php echo $repop_table ?>" value="<?php echo $repop_table ?>" ><?php echo $repop_table ?></option>
                                <?php  // Get all db table names
                                /*
                                global $wpdb;
                                $sql = "SHOW TABLES";
                                $results = $wpdb->get_results($sql);
                                $repop_table = isset($_POST['table_select']) ? $_POST['table_select'] : null;
                                
                                foreach($results as $index => $value) {
                                    foreach($value as $tableName) {
                                        ?><option name="<?php echo $tableName ?>" value="<?php echo $tableName ?>" <?php if($repop_table === $tableName) { echo 'selected="selected"'; } ?>><?php echo $tableName ?></option><?php
                                    }
                                }
                                */
                                ?>
                            </select>
                            </td> 
                        </tr>
                        <tr valign="top"><th scope="row"><?php _e('Select Input File:','wp_csv_to_db'); ?></th>
                            <td>
                                <?php $repop_file = isset($_POST['csv_file']) ? $_POST['csv_file'] : null; ?>
                                <?php $repop_csv_cols = isset($_POST['num_cols_csv_file']) ? $_POST['num_cols_csv_file'] : '0'; ?>
                                <input id="csv_file" name="csv_file"  type="text" size="70" value="<?php echo $repop_file; ?>" />
                                <input id="csv_file_button" type="button" value="Upload" />
                                <input id="num_cols" name="num_cols" type="hidden" value="" />
                                <input id="num_cols_csv_file" name="num_cols_csv_file" type="hidden" value="" />
                                <br><?php _e('File must end with a .csv extension.','wp_csv_to_db'); ?>
                                <br><?php _e('Number of .csv file Columns:','wp_csv_to_db'); echo ' '; ?><span id="return_csv_col_count"><?php echo $repop_csv_cols; ?></span>
                            </td>
                        </tr>
                        <tr valign="top"><th scope="row"><?php _e('Select Starting Row:','wp_csv_to_db'); ?></th>
                            <td>
                            	<?php $repop_row = isset($_POST['sel_start_row']) ? $_POST['sel_start_row'] : null; ?>
                                <input id="sel_start_row" name="sel_start_row" type="text" size="10" value="<?php echo $repop_row; ?>" />
                                <br><?php _e('Defaults to row 1 (top row) of .csv file.','wp_csv_to_db'); ?>
                                <br><?php _e('If the columns in the .csv file have a name, write "2".','wp_csv_to_db'); ?>
                            </td>
                        </tr>
                        <tr valign="top"><th scope="row"><?php _e('Update Database Rows:','wp_csv_to_db'); ?></th>
                            <td>
                                <input id="update_db" name="update_db" type="checkbox" />
                                <br><?php _e('Will update exisiting database rows when a duplicated primary key is encountered.','wp_csv_to_db'); ?>
                                <br><?php _e('Defaults to all rows inserted as new rows.','wp_csv_to_db'); ?>
                            </td>
                        </tr>
                    </table>
                    
                    <p class="submit">
                        <input id="execute_button" name="execute_button" type="submit" class="button-primary" value="<?php _e('Import to DB', 'wp_csv_to_db') ?>" />
                        <input id="export_to_csv_button" name="export_to_csv_button" type="submit" class="button-secondary" value="<?php _e('Export to CSV', 'wp_csv_to_db') ?>" />
                        <input id="delete_db_button" name="delete_db_button" type="button" class="button-secondary" value="<?php _e('Delete Table', 'wp_csv_to_db') ?>" />
                        <input type="hidden" id="delete_db_button_hidden" name="delete_db_button_hidden" value="" />
                    </p>
                </form>
            </div> <!-- End #tabs -->
        </div> <!-- End page wrap -->
        
        <h3><?php _e('Table Preview:','wp_csv_to_db'); ?><input id="repop_table_ajax" name="repop_table_ajax" value="<?php _e('Reload Table Preview','wp_csv_to_db'); ?>" type="button" style="margin-left:20px;" /></h3>
            
        <div id="table_preview">
        </div>
        
        <p><?php _e('After selecting a database table from the dropdown above; the table column names will be shown.','wp_csv_to_db'); ?>
        <br><?php _e('This may be used as a reference when verifying the .csv file is formatted properly.','wp_csv_to_db'); ?>
        <br><?php _e('If an "auto-increment" column exists; it will be rendered in the color "red".','wp_csv_to_db'); ?>
        
        <!-- Alert invalid .csv file - jquery dialog -->
        <div id="dialog_csv_file" title="<?php _e('Invalid File Extension','wp_csv_to_db'); ?>" style="display:none;">
        	<p><?php _e('This is not a valid .csv file extension.','wp_csv_to_db'); ?></p>
        </div>
        
        <!-- Alert select db table - jquery dialog -->
        <div id="dialog_select_db" title="<?php _e('Database Table not Selected','wp_csv_to_db'); ?>" style="display:none;">
        	<p><?php _e('First, please select a database table from the dropdown list.','wp_csv_to_db'); ?></p>
        </div>
        <?php
	}
	
}
$wp_csv_to_db = new wp_csv_to_db();

//  Ajax call for showing table column names
add_action( 'wp_ajax_wp_csv_to_db_get_columns', 'wp_csv_to_db_get_columns_callback' );
function wp_csv_to_db_get_columns_callback() {
	
	// Set variables
	global $wpdb;
	$sel_val = isset($_POST['sel_val']) ? $_POST['sel_val'] : null;
	$disable_autoinc = isset($_POST['disable_autoinc']) ? $_POST['disable_autoinc'] : 'false';
	$enable_auto_inc_option = 'false';
	$content = '';
	
	// Ran when the table name is changed from the dropdown
	if ($sel_val) {
		
		// Get table name
		$table_name = $sel_val;
		
		// Setup sql query to get all column names based on table name
		$sql = 'SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = "'.$wpdb->dbname.'" AND TABLE_NAME ="'.$table_name.'" AND EXTRA like "%auto_increment%"';
		
		// Execute Query
		$run_qry = $wpdb->get_results($sql);
		
		//
		// Begin response content
		$content .= '<table id="ajax_table"><tr>';
		
		// If the db query contains an auto_increment column
		if((isset($run_qry[0]->EXTRA)) && (isset($run_qry[0]->COLUMN_NAME))) {
			//$content .= 'auto: '.$run_qry[0]->EXTRA.'<br />';
			//$content .= 'column: '.$run_qry[0]->COLUMN_NAME.'<br />';
			
			// If user DID NOT check 'disable_autoinc'; we need to add that column back with unique formatting 
			if($disable_autoinc === 'false') {
				$content .= '<td class="auto_inc"><strong>'.$run_qry[0]->COLUMN_NAME.'</strong></td>';
			}
			
			// Get all column names from database for selected table
			$column_names = $wpdb->get_col( 'DESC ' . $table_name, 0 );
			$counter = 0;
			
			//
			// IMPORTANT - If the db results contain an auto_increment; we remove the first column below; because we already added it above.
			foreach ( $column_names as $column_name ) {
				if( $counter++ < 1) continue;  // Skip first iteration since 'auto_increment' table data cell will be duplicated
			    $content .= '<td><strong>'.$column_name.'</strong></td>';
			}
		}
		// Else get all column names from database (unfiltered)
		else {
			$column_names = $wpdb->get_col( 'DESC ' . $table_name, 0 );
			foreach ( $column_names as $column_name ) {
			  $content .= '<td><strong>'.$column_name.'</strong></td>';
			}
		}
		$content .= '</tr></table><br />';
		$content .= __('Number of Database Columns:','wp_csv_to_db').' <span id="column_count"><strong>'.count($column_names).'</strong></span><br />';
		
		// If there is an auto_increment column in the returned results
		if((isset($run_qry[0]->EXTRA)) && (isset($run_qry[0]->COLUMN_NAME))) {
			// If user DID NOT click the auto_increment checkbox
			if($disable_autoinc === 'false') {
				$content .= '<div class="warning_message">';
				$content .= __('This table contains an "auto increment" column.','wp_csv_to_db').'<br />';
				$content .= __('Please be sure to use unique values in this column from the .csv file.','wp_csv_to_db').'<br />';
				$content .= __('Alternatively, the "auto increment" column may be bypassed by clicking the checkbox above.','wp_csv_to_db').'<br />';
				$content .= '</div>';
				
				// Send additional response
				$enable_auto_inc_option = 'true';
			}
			// If the user clicked the auto_increment checkbox
			if($disable_autoinc === 'true') {
				$content .= '<div class="info_message">';
				$content .= __('This table contains an "auto increment" column that has been removed via the checkbox above.','wp_csv_to_db').'<br />';
				$content .= __('This means all new .csv entries will be given a unique "auto incremented" value when imported (typically, a numerical value).','wp_csv_to_db').'<br />';
				$content .= __('The Column Name of the removed column is','wp_csv_to_db').' <strong><em>'.$run_qry[0]->COLUMN_NAME.'</em></strong>.<br />';
				$content .= '</div>';
				
				// Send additional response 
				$enable_auto_inc_option = 'true';
			}
		}
	}
	else {
		$content = '';
		$content .= '<table id="ajax_table"><tr><td>';
		$content .= __('No Database Table Selected.','wp_csv_to_db');
		$content .= '<br />';
		$content .= __('Please select a database table from the dropdown box above.','wp_csv_to_db');
		$content .= '</td></tr></table>';
	}
	
	// Set response variable to be returned to jquery
	$response = json_encode( array( 'content' => $content, 'enable_auto_inc_option' => $enable_auto_inc_option ) );
	header( "Content-Type: application/json" );
	echo $response;
	die();
}

// Ajax call to process .csv file for column count
add_action('wp_ajax_wp_csv_to_db_get_csv_cols','wp_csv_to_db_get_csv_cols_callback');
function wp_csv_to_db_get_csv_cols_callback() {
	
	// Get file upload url
	$file_upload_url = $_POST['file_upload_url'];
	
	// Open the .csv file and get it's contents
	if(( $fh = @fopen($_POST['file_upload_url'], 'r')) !== false) {
		
		// Set variables
		$values = array();
		
		// Assign .csv rows to array
		while(( $row = fgetcsv($fh)) !== false) {  // Get file contents and set up row array
			//$values[] = '("' . implode('", "', $row) . '")';  // Each new line of .csv file becomes an array
			$rows[] = array(implode('", "', $row));
		}
		
		// Get a single array from the multi-array... and process it to count the individual columns
		$first_array_elm = reset($rows);
		$xplode_string = explode(", ", $first_array_elm[0]);
		
		// Count array entries
		$column_count = count($xplode_string);
	}
	else {
		$column_count = 'There was an error extracting data from the.csv file. Please ensure the file is a proper .csv format.';
	}
	
	// Set response variable to be returned to jquery
	$response = json_encode( array( 'column_count' => $column_count ) );
	header( "Content-Type: application/json" );
	echo $response;
	die();
}

// Add plugin settings link to plugins page
add_filter( 'plugin_action_links', 'wp_csv_to_db_plugin_action_links', 10, 4 );
function wp_csv_to_db_plugin_action_links( $links, $file ) {
	
	$plugin_file = 'wp_csv_to_db/main.php';
	if ( $file == $plugin_file ) {
		$settings_link = '<a href="' .
			admin_url( 'options-general.php?page=wp_csv_to_db_menu_page' ) . '">' .
			__( 'Settings', 'wp_csv_to_db' ) . '</a>';
		array_unshift( $links, $settings_link );
	}
	return $links;
}

// Load plugin language localization
add_action('plugins_loaded', 'wp_csv_to_db_lang_init');
function wp_csv_to_db_lang_init() {
	load_plugin_textdomain( 'wp_csv_to_db', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}




?>