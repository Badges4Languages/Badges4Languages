<?php

/**
 * Import/Export CSV to Database.
 * 
 * Based on the plugin 'WP CSV TO DB' by 'Tips and Tricks HQ, josh401' v2.2
 * and 'Save DB Table as CSV' by 'umairidrees'.
 * Consult their documentations for more information.
 */
class b4l_wp_csv_to_db {

	// Setup options variables
	protected $option_name = 'b4l_wp_csv_to_db';  // Name of the options array
	protected $data = array(  // Default options values
		'jq_theme' => 'smoothness'
	);
	
	
	public function __construct() {
		
		// Check if is admin
		// We can later update this to include other user roles
		if (is_admin()) {
                        add_action( 'plugins_loaded', array( $this, 'b4l_wp_csv_to_db_plugins_loaded' ));//Handles tasks that need to be done at plugins loaded stage.
			add_action( 'admin_menu', array( $this, 'b4l_wp_csv_to_db_register' ));  // Create admin menu page
			add_action( 'admin_init', array( $this, 'b4l_wp_csv_to_db_settings' ) ); // Create settings
			register_activation_hook( __FILE__ , array($this, 'b4l_wp_csv_to_db_activate')); // Add settings on plugin activation
		}
	}
	
        public function b4l_wp_csv_to_db_plugins_loaded(){
            $this->handle_csv_export_action();
        }
        
	public function b4l_wp_csv_to_db_activate() {
		update_option($this->option_name, $this->data);
	}
	
	public function b4l_wp_csv_to_db_register(){
    	$b4l_wp_csv_to_db_page = add_submenu_page(
                    'edit.php?post_type=badge',
                    __('Initialization - Import Data into Database','b4l_wp_csv_to_db'),
                    __('Initialization - Import Data into Database','b4l_wp_csv_to_db'),
                    'b4l_import_csv_to_db',
                    'csv-custom-submenu-page', 
                    array( $this, 'b4l_wp_csv_to_db_menu_page' ) 
                    );
    	//$b4l_wp_csv_to_db_page = add_submenu_page( 'options-general.php', __('WP CSV/DB','b4l_wp_csv_to_db'), __('WP CSV/DB','b4l_wp_csv_to_db'), 'manage_options', 'b4l_wp_csv_to_db_menu_page', array( $this, 'b4l_wp_csv_to_db_menu_page' )); // Add submenu page to "Settings" link in WP
		add_action( 'admin_print_scripts-' . $b4l_wp_csv_to_db_page, array( $this, 'b4l_wp_csv_to_db_admin_scripts' ) );  // Load our admin page scripts (our page only)
		add_action( 'admin_print_styles-' . $b4l_wp_csv_to_db_page, array( $this, 'b4l_wp_csv_to_db_admin_styles' ) );  // Load our admin page stylesheet (our page only)
	}
	
	public function b4l_wp_csv_to_db_settings() {
		register_setting('b4l_wp_csv_to_db_options', $this->option_name, array($this, 'b4l_wp_csv_to_db_validate'));
	}
	
	public function b4l_wp_csv_to_db_validate($input) {
            $valid = array();
            $valid['jq_theme'] = $input['jq_theme'];
            return $valid;
	}
	
	public function b4l_wp_csv_to_db_admin_scripts() {
		wp_enqueue_script('media-upload');  // For WP media uploader
		wp_enqueue_script('thickbox');  // For WP media uploader
		wp_enqueue_script('jquery-ui-tabs');  // For admin panel page tabs
		wp_enqueue_script('jquery-ui-dialog');  // For admin panel popup alerts
		
		wp_enqueue_script( 'b4l_wp_csv_to_db', plugins_url( '/js/admin_page.js', __FILE__ ), array('jquery') );  // Apply admin page scripts
		wp_localize_script( 'b4l_wp_csv_to_db', 'b4l_wp_csv_to_db_pass_js_vars', array( 'ajax_image' => plugin_dir_url( __FILE__ ).'images/loading.gif', 'ajaxurl' => admin_url('admin-ajax.php') ) );
	}
	
	public function b4l_wp_csv_to_db_admin_styles() {
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
        
        /**
         * Export Database Table into .csv file.
         * 
         * Based on 'Save DB Table as CSV' by 'umairidrees'.
         * https://gist.github.com/umairidrees/8952054#file-php-save-db-table-as-csv
        */
	public function CSV_GENERATE($getTable) {
                $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD) or die( "Unable to Connect database");
                mysqli_select_db(DB_NAME,$con) or die( "Unable to select database");
                // Table Name that you want to export in csv
                $FileName = $getTable."_export.csv";
                $file = fopen($FileName,"w");

                $sql = mysqli_query("SELECT * FROM ".$getTable."");
                $row = mysqli_fetch_assoc($sql);
                // Save headings alon
                $HeadingsArray=array();
                if($row == null) {
                    ?>
                        <script>alert("Warning : The table is empty !")</script>
                    <?php
                }
                else
                {
                    foreach($row as $name => $value){
                        $HeadingsArray[]=$name;
                    }
                    fputcsv($file,$HeadingsArray); 

                    // Save all records without headings
                    while($row = mysqli_fetch_assoc($sql)){
                        $valuesArray=array();
                            foreach($row as $name => $value){
                                $valuesArray[]=$value;
                            }
                        fputcsv($file,$valuesArray); 
                    }
                    fclose($file);
                    header("Location: $FileName");
                }
	}
	
	public function b4l_wp_csv_to_db_menu_page() {

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
				$success_message .= __('Congratulations!  The database table has been deleted successfully.','b4l_wp_csv_to_db');
			}
			else {
				$error_message .= '* '.__('Error deleting table. Please verify the table exists.','b4l_wp_csv_to_db');
			}
		}
		
		if ((isset($_POST['export_to_csv_button'])) && (empty($_POST['table_select']))) {
			$error_message .= '* '.__('No Database Table was selected to export. Please select a Database Table for exportation.','b4l_wp_csv_to_db').'<br />';
		}
		
		// If button is pressed to "Import to DB"
		if (isset($_POST['execute_button'])) {
			
			// If the "Select Table" input field is empty
			if(empty($_POST['table_select'])) {
				$error_message .= '* '.__('No Database Table was selected. Please select a Database Table.','b4l_wp_csv_to_db').'<br />';
			}
			// If the "Select Input File" input field is empty
			if(empty($_POST['csv_file'])) {
				$error_message .= '* '.__('No Input File was selected. Please enter an Input File.','b4l_wp_csv_to_db').'<br />';
			}
			// Check that "Input File" has proper .csv file extension
			$ext = pathinfo($_POST['csv_file'], PATHINFO_EXTENSION);
			if($ext !== 'csv') {
				$error_message .= '* '.__('The Input File does not contain the .csv file extension. Please choose a valid .csv file.','b4l_wp_csv_to_db');
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
                                        
					
					// 1st row are column's titles so we start at the 2nd row
					$num_var = 1;  // Subtract one to make counting easy on the non-techie folk!  (1 is actually 0 in binary)
						
					// If user input number exceeds available .csv rows
					if($num_var > count($values)) {
						$error_message .= '* '.__('Starting Row value exceeds the number of entries being updated to the database from the .csv file.','b4l_wp_csv_to_db').'<br />';
						$too_many = 'true';  // set alert variable
					}
					// Else splice array and remove number (rows) user selected
					else {
						$values = array_slice($values, $num_var);
					}
					
                                        
					// If there are no rows in the .csv file AND the user DID NOT input more rows than available from the .csv file
					if( empty( $values ) && ($too_many !== 'true')) {
						$error_message .= '* '.__('Columns do not match.','b4l_wp_csv_to_db').'<br />';
						$error_message .= '* '.__('The number of columns in the database for this table does not match the number of columns attempting to be imported from the .csv file.','b4l_wp_csv_to_db').'<br />';
						$error_message .= '* '.__('Please verify the number of columns attempting to be imported in the "Select Input File" exactly matches the number of columns displayed in the "Table Preview".','b4l_wp_csv_to_db').'<br />';
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
								$success_message = __('Congratulations!  The database has been updated successfully.','b4l_wp_csv_to_db');
							}
							// If db db_query_insert is successful
							elseif ($db_query_insert) {
								$success_message = __('Congratulations!  The database has been updated successfully.','b4l_wp_csv_to_db');
								$success_message .= '<br /><strong>'.count($values).'</strong> '.__('record(s) were inserted into the', 'b4l_wp_csv_to_db').' <strong>'.$_POST['table_select'].'</strong> '.__('database table.','b4l_wp_csv_to_db');
							}
							// If db db_query_insert is successful AND there were no rows to udpate
							elseif( ($db_query_update === 0) && ($db_query_insert === '') ) {
								$message_info_style .= '* '.__('There were no rows to update. All .csv values already exist in the database.','b4l_wp_csv_to_db').'<br />';
							}
							else {
								$error_message .= '* '.__('There was a problem with the database query.','b4l_wp_csv_to_db').'<br />';
								$error_message .= '* '.__('A duplicate entry was found in the database for a .csv file entry.','b4l_wp_csv_to_db').'<br />';
								$error_message .= '* '.__('If necessary; please use the option below to "Update Database Rows".','b4l_wp_csv_to_db').'<br />';
							}
						}
					}
				}
				else {
					$error_message .= '* '.__('No valid .csv file was found at the specified url. Please check the "Select Input File" field and ensure it points to a valid .csv file.','b4l_wp_csv_to_db').'<br />';
				}
			}
		}
		
		// If there is a message - info-style
		if(!empty($message_info_style)) {
			echo '<div class="info_message_dismiss">';
			echo $message_info_style;
			echo '<br /><em>('.__('click to dismiss','b4l_wp_csv_to_db').')</em>';
			echo '</div>';
		}
		
		// If there is an error message	
		if(!empty($error_message)) {
			echo '<div class="error_message">';
			echo $error_message;
			echo '<br /><em>('.__('click to dismiss','b4l_wp_csv_to_db').')</em>';
			echo '</div>';
		}
		
		// If there is a success message
		if(!empty($success_message)) {
			echo '<div class="success_message">';
			echo $success_message;
			echo '<br /><em>('.__('click to dismiss','b4l_wp_csv_to_db').')</em>';
			echo '</div>';
		}
		?>
		<div class="wrap">
        
            <h2><?php _e('WordPress CSV to Database Options','b4l_wp_csv_to_db'); ?></h2>
            
            <p>This plugin allows you to insert CSV file data into your WordPress database table. You can also export the content of a database using this plugin.</p>            
            <p>Please read the documentation before using this tool.</p> 
            
            <div id="tabs">
                
        	<form id="b4l_wp_csv_to_db_form" method="post" action="">
                    <table class="form-table"> 
                        
                        <tr valign="top"><th scope="row"><?php _e('Select Content To Upload:','b4l_wp_csv_to_db'); ?></th>
                            <td>
                                <select id="table_select" name="table_select" value="">
                                <option name="" value=""></option>
                                
                                <?php 
                                global $wpdb;
                                $repop_table=$wpdb->prefix."b4l_languages";
                                ?>
                                <option name="<?php echo $repop_table ?>" value="<?php echo $repop_table ?>" ><?php echo 'Languages' ?></option>
                                
                                <?php $repop_table=$wpdb->prefix."b4l_skills"; ?>
                                <option name="<?php echo $repop_table ?>" value="<?php echo $repop_table ?>" ><?php echo 'Skills' ?></option>
                                
                                <?php $repop_table=$wpdb->prefix."b4l_studentLevels"; ?>
                                <option name="<?php echo $repop_table ?>" value="<?php echo $repop_table ?>" ><?php echo 'StudentLevels' ?></option>
                                
                                <?php $repop_table=$wpdb->prefix."b4l_teacherLevels"; ?>
                                <option name="<?php echo $repop_table ?>" value="<?php echo $repop_table ?>" ><?php echo 'TeacherLevels' ?></option>
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
                        
                        <!-- Give information about the selected Database Table -->
                        <tr valign="top"><th scope="row"><?php _e('Content Information:','b4l_wp_csv_to_db'); ?></th>
                            <td>
                                <!-- Script which displays the information -->
                            <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
                            <script>
                                $('#table_select').change(function() {
                                    value = $( "#table_select option:selected" ).text();
                                    switch(value){
                                        case "Languages" :
                                            $('#db_table_description').text("Contains the language code (3 letters), the country code (2 letters) and the full language name. On the website, the 10 most spoken languages are displayed alphabeticaly, and then all the others.");
                                            $('#csv_example').html('<a href="<?php echo WP_PLUGIN_URL.'/badges4languages-plugin/included_plugins/wp_csv_to_db/csv_examples/languageExample.csv'; ?>">languageExample</a>');                                          
                                            break;
                                        case "Skills" :
                                            $('#db_table_description').text("What know the students : listening, reading, spoken interaction, spoken production, and writing.");
                                            $('#csv_example').html('<a href="<?php echo WP_PLUGIN_URL.'/badges4languages-plugin/included_plugins/wp_csv_to_db/csv_examples/skillsExample.csv'; ?>">skillsExample</a>');                                                                                     
                                            break;
                                        case "StudentLevels" :
                                            $('#csv_example').html('<a href="<?php echo WP_PLUGIN_URL.'/badges4languages-plugin/included_plugins/wp_csv_to_db/csv_examples/studentLevelsExample.csv'; ?>">studentLevelsExample</a>');                                                                                     
                                            $('#db_table_description').text("To give a certification between A1 and C2 to a student.");
                                            break;
                                        case "TeacherLevels" :
                                            $('#db_table_description').text("To give a certification between T1 and T6 to a teacher.");
                                            $('#csv_example').html('<a href="<?php echo WP_PLUGIN_URL.'/badges4languages-plugin/included_plugins/wp_csv_to_db/csv_examples/teacherLevelsExample.csv'; ?>">teacherLevelsExample</a>');                                                                                                                             
                                            break;
                                        case "" :
                                           $('#db_table_description').text("");
                                           $('#csv_example').text("");
                                           break;;
                                    }
                                });
                            </script>
                            
                            <div id="db_table_description">
                            </div>
                            
                            <div id="csv_example">
                            </div>
                            
                            </td>
                        </tr>
                        
                        
                        
                        
                        
                        <tr valign="top"><th scope="row"><?php _e('Select Input File:','b4l_wp_csv_to_db'); ?></th>
                            <td>
                                <?php $repop_file = isset($_POST['csv_file']) ? $_POST['csv_file'] : null; ?>
                                <?php $repop_csv_cols = isset($_POST['num_cols_csv_file']) ? $_POST['num_cols_csv_file'] : '0'; ?>
                                <br><?php _e('If you have a permission problem when you import a CSV file (not allowed to import it) on a multisite Wordpress, go to the multisite "network settings", then to the category "Upload file types" and add "csv" to the list.','b4l_wp_csv_to_db'); ?>
                                <input id="csv_file" name="csv_file"  type="text" size="70" value="<?php echo $repop_file; ?>" />
                                <input id="csv_file_button" type="button" value="Upload" />
                                <input id="num_cols" name="num_cols" type="hidden" value="" />
                                <input id="num_cols_csv_file" name="num_cols_csv_file" type="hidden" value="" />
                                <br><?php _e('File must end with a .csv extension.','b4l_wp_csv_to_db'); ?>
                                <br><?php _e('Number of .csv file Columns:','b4l_wp_csv_to_db'); echo ' '; ?><span id="return_csv_col_count"><?php echo $repop_csv_cols; ?></span>
                            </td>
                        </tr>
                        <tr valign="top"><th scope="row"><?php _e('Update Database Rows:','b4l_wp_csv_to_db'); ?></th>
                            <td>
                                <input id="update_db" name="update_db" type="checkbox" />
                                <br><?php _e('Will update exisiting database rows when a duplicated primary key is encountered.','b4l_wp_csv_to_db'); ?>
                                <br><?php _e('Defaults to all rows inserted as new rows.','b4l_wp_csv_to_db'); ?>
                            </td>
                        </tr>
                    </table>
                    
                    <p class="submit">
                        <input id="execute_button" name="execute_button" type="submit" class="button-primary" value="<?php _e('Import to DB', 'b4l_wp_csv_to_db') ?>" />
                        <input id="export_to_csv_button" name="export_to_csv_button" type="submit" class="button-primary" value="<?php _e('Export to CSV', 'b4l_wp_csv_to_db') ?>" />
                        <!--<input id="delete_db_button" name="delete_db_button" type="button" class="button-secondary" value="<?php// _e('Delete Table', 'b4l_wp_csv_to_db') ?>" />-->
                        <input type="hidden" id="delete_db_button_hidden" name="delete_db_button_hidden" value="" />
                    </p>
                </form>
            </div> <!-- End #tabs -->
        </div> <!-- End page wrap -->
        
        <h3><?php _e('Table Preview:','b4l_wp_csv_to_db'); ?><input id="repop_table_ajax" name="repop_table_ajax" value="<?php _e('Reload Table Preview','b4l_wp_csv_to_db'); ?>" type="button" style="margin-left:20px;" /></h3>
            
        <div id="table_preview">
        </div>
        
        <p><?php _e('After selecting a database table from the dropdown above; the table column names will be shown.','b4l_wp_csv_to_db'); ?>
        <br><?php _e('This may be used as a reference when verifying the .csv file is formatted properly.','b4l_wp_csv_to_db'); ?>
        <br><?php _e('If an "auto-increment" column exists; it will be rendered in the color "red".','b4l_wp_csv_to_db'); ?>
        
        <!-- Alert invalid .csv file - jquery dialog -->
        <div id="dialog_csv_file" title="<?php _e('Invalid File Extension','b4l_wp_csv_to_db'); ?>" style="display:none;">
        	<p><?php _e('This is not a valid .csv file extension.','b4l_wp_csv_to_db'); ?></p>
        </div>
        
        <!-- Alert select db table - jquery dialog -->
        <div id="dialog_select_db" title="<?php _e('Database Table not Selected','b4l_wp_csv_to_db'); ?>" style="display:none;">
        	<p><?php _e('First, please select a database table from the dropdown list.','b4l_wp_csv_to_db'); ?></p>
        </div>
        <?php
	}
	
}
$b4l_wp_csv_to_db = new b4l_wp_csv_to_db();

//  Ajax call for showing table column names
add_action( 'wp_ajax_b4l_wp_csv_to_db_get_columns', 'b4l_wp_csv_to_db_get_columns_callback' );
function b4l_wp_csv_to_db_get_columns_callback() {
	
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
		$content .= __('Number of Database Columns:','b4l_wp_csv_to_db').' <span id="column_count"><strong>'.count($column_names).'</strong></span><br />';
		
		// If there is an auto_increment column in the returned results
		if((isset($run_qry[0]->EXTRA)) && (isset($run_qry[0]->COLUMN_NAME))) {
			// If user DID NOT click the auto_increment checkbox
			if($disable_autoinc === 'false') {
				$content .= '<div class="warning_message">';
				$content .= __('This table contains an "auto increment" column.','b4l_wp_csv_to_db').'<br />';
				$content .= __('Please be sure to use unique values in this column from the .csv file.','b4l_wp_csv_to_db').'<br />';
				$content .= __('Alternatively, the "auto increment" column may be bypassed by clicking the checkbox above.','b4l_wp_csv_to_db').'<br />';
				$content .= '</div>';
				
				// Send additional response
				$enable_auto_inc_option = 'true';
			}
			// If the user clicked the auto_increment checkbox
			if($disable_autoinc === 'true') {
				$content .= '<div class="info_message">';
				$content .= __('This table contains an "auto increment" column that has been removed via the checkbox above.','b4l_wp_csv_to_db').'<br />';
				$content .= __('This means all new .csv entries will be given a unique "auto incremented" value when imported (typically, a numerical value).','b4l_wp_csv_to_db').'<br />';
				$content .= __('The Column Name of the removed column is','b4l_wp_csv_to_db').' <strong><em>'.$run_qry[0]->COLUMN_NAME.'</em></strong>.<br />';
				$content .= '</div>';
				
				// Send additional response 
				$enable_auto_inc_option = 'true';
			}
		}
	}
	else {
		$content = '';
		$content .= '<table id="ajax_table"><tr><td>';
		$content .= __('No Database Table Selected.','b4l_wp_csv_to_db');
		$content .= '<br />';
		$content .= __('Please select a database table from the dropdown box above.','b4l_wp_csv_to_db');
		$content .= '</td></tr></table>';
	}
	
	// Set response variable to be returned to jquery
	$response = json_encode( array( 'content' => $content, 'enable_auto_inc_option' => $enable_auto_inc_option ) );
	header( "Content-Type: application/json" );
	echo $response;
	die();
}

// Ajax call to process .csv file for column count
add_action('wp_ajax_b4l_wp_csv_to_db_get_csv_cols','b4l_wp_csv_to_db_get_csv_cols_callback');
function b4l_wp_csv_to_db_get_csv_cols_callback() {
	
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
add_filter( 'plugin_action_links', 'b4l_wp_csv_to_db_plugin_action_links', 10, 4 );
function b4l_wp_csv_to_db_plugin_action_links( $links, $file ) {
	
	$plugin_file = 'b4l_wp_csv_to_db/main.php';
	if ( $file == $plugin_file ) {
		$settings_link = '<a href="' .
			admin_url( 'options-general.php?page=b4l_wp_csv_to_db_menu_page' ) . '">' .
			__( 'Settings', 'b4l_wp_csv_to_db' ) . '</a>';
		array_unshift( $links, $settings_link );
	}
	return $links;
}
