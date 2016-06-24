<?php
/**
 * Inspired from 'WP CSV TO DB' realized by 'Tips and Tricks HQ, josh401'
 **/
?>

<!-- form for csv upload -->
	<div class="wrap">
		<h2>CSV File Upload</h2>
	<form id="wp_csv_to_db_form" method="post" action="">
                    <table class="form-table"> 
                        <tr valign="top"><th scope="row"><?php _e('Database Table:','wp_csv_to_db'); ?></th>
                            <td>
                                <select id="table_select" name="table_select" value="" selected="selected">
                                <option name="" value=""></option>
                                
                                <?php 
                                global $wpdb;
                                $repop_table=$wpdb->prefix."skills";
                                ?>
                                <option name="<?php echo $repop_table ?>" value="<?php echo $repop_table ?>" ><?php echo $repop_table ?></option>
                                
                                <?php $repop_table=$wpdb->prefix."studentLevels"; ?>
                                <option name="<?php echo $repop_table ?>" value="<?php echo $repop_table ?>" ><?php echo $repop_table ?></option>
                                
                                <?php $repop_table=$wpdb->prefix."teacherLevels"; ?>
                                <option name="<?php echo $repop_table ?>" value="<?php echo $repop_table ?>" ><?php echo $repop_table ?></option>
                                
                                
                                <?php
                                //if you want to display all the table names in the database use the code in comments /**/
                                // Get all db table names
                               /* $sql = "SHOW TABLES";
                                $results = $wpdb->get_results($sql);
                                $repop_table = isset($_POST['table_select']) ? $_POST['table_select'] : null;
                                foreach($results as $index => $value) {
                                    foreach($value as $tableName) {
                                        ?><option name="<?php echo $tableName ?>" value="<?php echo $tableName ?>" <?php if($repop_table === $tableName) { echo 'selected="selected"'; } ?>><?php echo $tableName ?></option><?php
                                    }
                                }*/
                                ?>
                            </select>
                                
                            <h3><?php _e('Table Preview:','wp_csv_to_db'); ?><input id="repop_table_ajax" name="repop_table_ajax" value="<?php _e('Reload Table Preview','wp_csv_to_db'); ?>" type="button" style="margin-left:20px;" /></h3>  
                            <div id="table_preview"> </div>
                            <p><?php _e('Click on the "Reload table preview" to see the fields.','wp_csv_to_db'); ?>
                            <br><?php _e('Use the outputed fields as reference when verifying the .csv file is formatted properly.','wp_csv_to_db'); ?>
        
                            </td> 
                        </tr>
                        <tr valign="top"><th scope="row"><?php _e('Levels CSV file for upload:','wp_csv_to_db'); ?></th>
                            <td>
                                <?php $repop_file = isset($_POST['csv_file']) ? $_POST['csv_file'] : null; ?>
                                <?php $repop_csv_cols = isset($_POST['num_cols_csv_file']) ? $_POST['num_cols_csv_file'] : '0'; ?>
                                <input id="csv_file" name="csv_file" type="text" size="70" value="<?php echo $repop_file; ?>" />
                                <input id="csv_file_button" type="button" value="Upload" />
                                <input id="num_cols" name="num_cols" type="hidden" value="" />
                                <input id="num_cols_csv_file" name="num_cols_csv_file" type="hidden" value="" />
                                <br><?php _e('File must end with a .csv extension.','wp_csv_to_db'); ?>
                                <br><?php _e('Number of .csv file Columns:','wp_csv_to_db'); echo ' '; ?><span id="return_csv_col_count"><?php echo $repop_csv_cols; ?></span>
                            </td>
                        </tr>
                       <!-- <tr valign="top"><th scope="row"><?php// _e('Select Starting Row:','wp_csv_to_db'); ?></th>
                            <td>
                            	<?php// $repop_row = isset($_POST['sel_start_row']) ? $_POST['sel_start_row'] : null; ?>
                                <input id="sel_start_row" name="sel_start_row" type="text" size="10" value="<?php// echo $repop_row; ?>" />
                                <br><?php// _e('Defaults to row 1 (top row) of .csv file.','wp_csv_to_db'); ?>
                            </td>
                        </tr>-->
                        <!--<tr valign="top"><th scope="row"><?php _e('Disable "auto_increment" Column:','wp_csv_to_db'); ?></th>-->
                            <td>
                                <input id="remove_autoinc_column" name="remove_autoinc_column" type="hidden"/>
                               <!-- <br><?php _e('Bypasses the "auto_increment" column;','wp_csv_to_db'); ?>
                                <br><?php _e('This will reduce (for the purposes of importation) the number of DB columns by "1".'); ?>-->
                            </td>
                        </tr>
                        <tr valign="top"><th scope="row"><?php _e('Update Database Rows:'); ?></th>
                            <td>
                                <input id="update_db" name="update_db" type="checkbox" />
                                <br><?php _e('Will update exisiting database rows when a duplicated primary key is encountered.'); ?>
                                <br><?php _e('Defaults to all rows inserted as new rows.'); ?>
                            </td>
                        </tr>
                    </table>
                    
                    <p class="submit">
                        <input id="execute_button" name="execute_button" type="submit" class="button-primary" value="<?php _e('Import to DB', 'wp_csv_to_db') ?>" />
                    </p>
                   </form>
                   </div>
        
        
        <!-- Alert invalid .csv file - jquery dialog -->
        <div id="dialog_csv_file" title="<?php _e('Invalid File Extension','wp_csv_to_db'); ?>" style="display:none;">
        	<p><?php _e('This is not a valid .csv file extension.','wp_csv_to_db'); ?></p>
        </div>
        
        <!-- Alert select db table - jquery dialog --
        <div id="dialog_select_db" title="<?php _e('Database Table not Selected','wp_csv_to_db'); ?>" style="display:none;">
        	<p><?php _e('First, please select a database table from the dropdown list.','wp_csv_to_db'); ?></p>