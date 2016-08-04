<?php
 /*
  * Create the custom metabox for the custom post 'badge' which displays a link
  * by language to obtain more information about the certification.
  * 
  * This code is highly inspired from 'Repeatable Custom Fields in a Metabox'
  * realised by Helen Hou-Sandi (https://gist.github.com/helen/1593065)
  * 
  * @author Alexandre LEVACHER
  * @package badges4Languages-plugin
  * @subpackage includes/initialisation
  * @since 1.1.0
 */

 
/**
 * Get the 10 most important languages in the world from the Database.
 * 
 * @author Alexandre LEVACHER
 * @since 1.1.0
*/
function b4l_options_custom_metabox() {
    global $wpdb;
    $query = "SELECT language_name FROM ".$wpdb->prefix."b4l_languages WHERE
                language_id = 'eng' OR
                language_id = 'spa' OR
                language_id = 'fra' OR
                language_id = 'cmn' OR
                language_id = 'rus' OR
                language_id = 'por' OR
                language_id = 'deu' OR
                language_id = 'ita' OR
                language_id = 'jpn' OR
                language_id = 'arb'
            ";
    $results = $wpdb->get_results($query, ARRAY_A);
    
    //We create a simplified array, in which $key = {0,1,2...} and $value = {French, Spanish, English, etc...}
    $arrayLanguages = array();
    foreach ( $results as $result ) {
        array_push($arrayLanguages, $result[language_name]);
    }
    return $arrayLanguages;
}


/**
 * Execute the 'b4l_badge_add_meta_boxes' function during the initialization phase.
 */
add_action('admin_init', 'b4l_badge_add_meta_boxes', 1);

/**
 * Create the meta box for the custom post 'badge'.
 * 
 * @author Alexandre LEVACHER
 * @since 1.1.0
*/
function b4l_badge_add_meta_boxes() {
	add_meta_box( 
                'badge-information', 
                'Badge Information', 
                'b4l_display_badge_meta_box', 
                'badge', 
                'normal', 
                'default'
        );
}

/**
 * Display the custom meta box into the editable custom post 'badge'.
 * 
 * It contains a scrollbar menu with the most used languages of the world and an
 * input field to add a link for a selected language. Thanks to jQuery, the user
 * can add or delete a metabox field.
 * 
 * Inspired from 'Repeatable Custom Fields in a Metabox' realised by Helen Hou-Sandi.
 * 
 * @author Alexandre LEVACHER
 * @since 1.1.0
*/
function b4l_display_badge_meta_box() {
	global $post;
	$badge = get_post_meta($post->ID, 'badge_links', true);
	$options = b4l_options_custom_metabox();
	wp_nonce_field( 'b4l_badge_meta_box_nonce', 'b4l_badge_meta_box_nonce' );
	?>
	<script type="text/javascript">
	jQuery(document).ready(function( $ ){
		$( '#add-row' ).on('click', function() {
			var row = $( '.empty-row.screen-reader-text' ).clone(true);
			row.removeClass( 'empty-row screen-reader-text' );
			row.insertBefore( '#repeatable-fieldset-one tbody>tr:last' );
			return false;
		});
  	
		$( '.remove-row' ).on('click', function() {
			$(this).parents('tr').remove();
			return false;
		});
	});
	</script>
  
        <!-- Title's columns  -->
	<table id="repeatable-fieldset-one" width="100%">
	<thead>
		<tr>
			<th width="0%">Language</th>
			<th width="100%">URL</th>
			<th width="0%"></th>
		</tr>
	</thead>
	<tbody>
            
	<?php
        //If there is at least 1 custom metabox field created before.
	if ( $badge ) :
	foreach ( $badge as $field ) {
	?>
	<tr>
            <td>
                <!-- Scrollbar menu with the languages -->
                <select style="width:150px" name="select[]">
                <?php foreach ( $options as $label => $value ) : ?>
			<option value="<?php echo $value; ?>"<?php selected( $field['select'], $value ); ?>><?php echo $value; ?></option>
                <?php endforeach; ?>
                </select>
            </td>

            <td>
                <!-- InputField for the link -->
                <input type="text" class="widefat" name="url[]" value="<?php if ($field['url'] != '') echo esc_attr( $field['url'] ); else echo 'http://'; ?>" />
            </td>
            
            <!-- Remove button -->
            <td><a class="button remove-row" href="#">Remove</a></td>
	</tr>
	<?php
	}
        //If there is not custom metabox (for example when it is a new post).
	else :
	?>
	<tr>
            <td>
                <select style="width:150px" name="select[]">
                <?php foreach ( $options as $label => $value ) : ?>
			<option value="<?php echo $value; ?>"<?php selected( $field['select'], $value ); ?>><?php echo $value; ?></option>
                <?php endforeach; ?>
                </select>
            </td>
	
            <td>
                <input type="text" class="widefat" name="url[]" value="http://" />
            </td>
	
		<td><a class="button remove-row" href="#">Remove</a></td>
	</tr>
	<?php endif; ?>
	
	<!-- empty hidden one for jQuery -->
	<tr class="empty-row screen-reader-text">
            <td>
                <select style="width:150px" name="select[]">
                <?php foreach ( $options as $label => $value ) : ?>
			<option value="<?php echo $value; ?>"<?php selected( $field['select'], $value ); ?>><?php echo $value; ?></option>
                <?php endforeach; ?>
                </select>
            </td>
		
            <td>
                <input type="text" class="widefat" name="url[]" value="http://" />
            </td>
		  
            <td><a class="button remove-row" href="#">Remove</a></td>
	</tr>
	</tbody>
	</table>
	
	<p><a id="add-row" class="button" href="#">Add another</a></p>
	<?php
}


/**
 * Execute the 'b4l_badge_meta_box_save' to save the custom metabox information.
 */
add_action('save_post', 'b4l_badge_meta_box_save');

/**
 * Save the custom metabox information for the custom post 'badge'.
 * 
 * Inspired from 'Repeatable Custom Fields in a Metabox' realised by Helen Hou-Sandi.
 * 
 * @author Alexandre LEVACHER
 * @since 1.1.0
*/
function b4l_badge_meta_box_save($post_id) {
	if ( ! isset( $_POST['b4l_badge_meta_box_nonce'] ) ||
	! wp_verify_nonce( $_POST['b4l_badge_meta_box_nonce'], 'b4l_badge_meta_box_nonce' ) )
		return;
	
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return;
	
	if (!current_user_can('edit_post', $post_id))
		return;
	
	$old = get_post_meta($post_id, 'badge_links', true);
	$new = array();
	$options = b4l_options_custom_metabox();
	
	$selects = $_POST['select'];
	$urls = $_POST['url'];
	
	$count = count( $selects );
	
	for ( $i = 0; $i < $count; $i++ ) {
		if ( ($selects[$i] != '') && !($urls[$i] == 'http://')) :
			
			if ( in_array( $selects[$i], $options ) )
				$new[$i]['select'] = $selects[$i];
			else
				$new[$i]['select'] = '';
		
			$new[$i]['url'] = stripslashes( $urls[$i] );
		endif;
	}
	if ( !empty( $new ) && $new != $old )
		update_post_meta( $post_id, 'badge_links', $new );
	elseif ( empty($new) && $old )
		delete_post_meta( $post_id, 'badge_links', $old );
}
