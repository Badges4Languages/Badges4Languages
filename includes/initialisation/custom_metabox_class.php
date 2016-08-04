<?php
 /*
  * Create custom metaboxes for the custom post 'class' which contains information
  * about the class, like the level, the language or the dates.
  * 
  * @author Alexandre LEVACHER
  * @package badges4Languages-plugin
  * @subpackage includes/initialisation
  * @since 1.1.3
 */


/**
 * Register with hook 'admin_enqueue_scripts', which can be used for CSS and jQuery
 */
add_action('admin_enqueue_scripts', 'b4l_enqueue_class_custom_metabox_calendar');

/**
 * Enqueue plugin style-file (add the CSS file)
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
 */
function b4l_enqueue_class_custom_metabox_calendar(){
    wp_enqueue_script(
        'field-date', 
        WP_PLUGIN_URL.'/badges4languages-plugin/js/custom_metaboxes.js', 
        array('jquery', 'jquery-ui-core', 'jquery-ui-datepicker'),
        time(),
        true
    );  
    wp_enqueue_style( 'jquery-ui-datepicker' );
}


/**
 * Execute the 'b4l_class_add_meta_boxes' function during the initialization phase.
 */
add_action('admin_init', 'b4l_class_add_meta_boxes', 1);

/**
 * Create the meta box for the custom post 'class'.
 * 
 * @author Alexandre LEVACHER
 * @since 1.1.3
*/
function b4l_class_add_meta_boxes() {
	add_meta_box( 
                'class-information', 
                'Class Information', 
                'b4l_display_class_meta_box', 
                'class', 
                'normal', 
                'default'
        );
}

/**
 * Display the custom metaboxes into the editable custom post 'class'.
 * 
 * The writer (admin, teacher, badgeeditor or university) can add the class's level,
 * the class's language, the class's starting date and the class's ending date.
 * 
 * @author Alexandre LEVACHER
 * @since 1.1.3
 * @param WordpressObject $post Custom post 'class'
*/
function b4l_display_class_meta_box($post) {
        $class_language = get_post_meta( $post->ID, 'class_language', true );
        $class_level = get_post_meta( $post->ID, 'class_level', true );
        $class_starting_date = get_post_meta( $post->ID, 'class_starting_date', true );
        $class_ending_date = get_post_meta( $post->ID, 'class_ending_date', true );
        wp_nonce_field( 'b4l_class_meta_box_nonce', 'b4l_class_meta_box_nonce' );
        ?>

        <!-- Title's columns  -->
        <table width="100%">
            <tr>
                <th>Language</th>
                <td>
                    <select style="width: 250px" name="class_language" id="class_language">
                        <option value="" selected> Select a language </option>
                        <?php
                            global $wpdb;
                            $query = "SELECT language_name FROM ".$wpdb->prefix."b4l_languages ORDER BY 
                                        (CASE 
                                            WHEN language_id = 'arb' THEN 1
                                            WHEN language_id = 'cmn' THEN 1
                                            WHEN language_id = 'deu' THEN 1
                                            WHEN language_id = 'eng' THEN 1
                                            WHEN language_id = 'fra' THEN 1 
                                            WHEN language_id = 'ita' THEN 1
                                            WHEN language_id = 'jpn' THEN 1
                                            WHEN language_id = 'por' THEN 1
                                            WHEN language_id = 'rus' THEN 1
                                            WHEN language_id = 'spa' THEN 1
                                            WHEN language_id = 'vlc' THEN 1
                                            WHEN language_id = '---' THEN 2
                                            ELSE language_name 
                                        END)";
                            $languages = $wpdb->get_results($query, ARRAY_A);
                            foreach($languages as $language) {
                                if($language[language_name] == $class_language) {
                                    echo '<option value="'.$language[language_name].'" selected>'.$language[language_name].'</option>';
                                } else {
                                    echo '<option value="'.$language[language_name].'">'.$language[language_name].'</option>';
                                }
                            }?>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Level</th>
                <td>
                    <select style="width:250px" name="class_level" id="class_level">
                        <?php
                        $options = array( 'A1', 'A2', 'B1', 'B2', 'C1', 'C2');
                        for( $i=0; $i<count($options); $i++ ) {
                          echo '<option '.($class_level == $options[$i] ? 'selected="selected"' : '' ).'>'.$options[$i].'</option>';
                        }
                        ?>
                </select>
                </td>
            </tr>
            <tr>
                <th>Starting Date</th>
                <td><input type="text" style="width:250px" name="class_starting_date" class="class_calendar" value="<?php echo $class_starting_date; ?>" /></td>
            </tr>
            <tr>
                <th>Ending Date</th>
                <td><input type="text" style="width:250px" name="class_ending_date" class="class_calendar" value="<?php echo $class_ending_date; ?>"/></td>
            </tr>
        </table>
        <?php
}


/**
 * Execute the 'b4l_class_meta_box_save' to save the custom metabox information.
 */
add_action('save_post', 'b4l_class_meta_box_save');

/**
 * Save the custom metabox information for the custom post 'class'.
 * 
 * @author Alexandre LEVACHER
 * @since 1.1.3
 * @param Int $post_id ID of the custom post 'class'
*/
function b4l_class_meta_box_save($post_id) {
        if ( isset( $_POST['class_language'] ) ) {
            update_post_meta( $post_id, 'class_language', $_POST['class_language'] );
        }
        if ( isset( $_POST['class_level'] ) ) {
            update_post_meta( $post_id, 'class_level', $_POST['class_level'] );
        }
        if($_POST['class_starting_date'] < $_POST['class_ending_date']) {
            $starting_date = explode("/", $_POST['class_starting_date']);
            $ending_date = explode("/", $_POST['class_ending_date']);
            if ( !empty($_POST['class_starting_date']) && checkdate($starting_date[0], $starting_date[1], $starting_date[2]) ) {
                update_post_meta( $post_id, 'class_starting_date', $_POST['class_starting_date'] );
            }
            if ( !empty($_POST['class_ending_date']) && checkdate($ending_date[0], $ending_date[1], $ending_date[2]) ) {
                update_post_meta( $post_id, 'class_ending_date', $_POST['class_ending_date'] );
            }
        }
}
?>