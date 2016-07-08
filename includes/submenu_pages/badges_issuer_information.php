<?php
 /*
  * Create a submenu padge in the administration menu to fill a form with the issuer information (company info).
  * 
  * @author Alexandre Levacher
  * @package badges4languages-plugin
  * @subpackage includes/submenu_pages
  * @since 1.0.0
 */


/**
 * Adds b4l_badges_issuer_information_submenu_page during to the admin menu.
 */
add_action('admin_menu', 'b4l_badges_issuer_information_submenu_page');
 
/**
 * Creates the submenu page.
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
 */
function b4l_badges_issuer_information_submenu_page() {
    add_submenu_page(
        'edit.php?post_type=badge',
        'Badges Issuer Information',
        'Badges Issuer Information',
        'b4l_badges_issuer_information',
        'badges-issuer-information-submenu-page',
        'b4l_badges_issuer_information_page_callback' 
    );
}
 

/**
 * Calls 2 functions : One for displaying the content of the submenu page,
 * the other to save data into Database Table
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
 */
function b4l_badges_issuer_information_page_callback() {
    b4l_badges_issuer_information_html();
    b4l_badges_issuer_information_save_into_db_table();
}


/**
 * Displays all the HTML content with the issuer information (if the DB Table
 * is not empty).
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
 */
function b4l_badges_issuer_information_html(){
    ?>
    <div class="wrap"><div id="icon-tools" class="icon32"></div>
        <h2>Badges Issuer Information</h2>
        <p><b>You have to give the issuer information before sending badges !</b></p>
        <p><b>If you don't do it, the OpenBadges badge will not be valid !</b></p>
        <p>Issuer information is information about your group/firm.</p>
        <br/>
        <form action="<?php get_page_link(); ?>" method="POST">
            <h3>Issuer Name</h3>
            <input id="issuer_name" name="issuer_name"  type="text" size="70" value="<?php b4l_badges_issuer_information_select_from_db_table("issuer_name") ?>" />
            <p>Name of your firm/group/etc...</p>
            <br/>
            <h3>Issuer Logo Link</h3>
            <input id="issuer_logo" name="issuer_logo"  type="text" size="70" value="<?php b4l_badges_issuer_information_select_from_db_table("issuer_logo") ?>" />
            <p>Logo link must begin by "http(s)" (and not "www").</p>
            <br/>
            <h3>Issuer Email</h3>
            <input id="issuer_email" name="issuer_email"  type="text" size="70" value="<?php b4l_badges_issuer_information_select_from_db_table("issuer_email") ?>" />
            <p>Email must be valid.</p>
            <br/>
            <h3>Issuer URL</h3>
            <input id="issuer_url" name="issuer_url"  type="text" size="70" value="<?php b4l_badges_issuer_information_select_from_db_table("issuer_url") ?>"  />
            <p>URL must begin by "http(s)" (and not "www").</p>
            <br/>
            <input id="issuer_email_button" name="issuer_email_button" type="submit" class="button-primary" value="Upload" />
        </form>
    </div>
<?php
}


/**
 * Loads DB Table data into the page.
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
 * @param string $idElement HTML id element
 */
function b4l_badges_issuer_information_select_from_db_table($idElement){
    global $wpdb;
    $queryInfo = "SELECT * FROM ".$wpdb->prefix."b4l_issuer_information ";
    $results = $wpdb->get_results($queryInfo, ARRAY_A); 
    echo $results[0][$idElement];
}


/**
 * Saves into the DB Table the issuer information given by the admin.
 * Verify the validity of the information.
 * 
 * @author Alexandre LEVACHER
 * @since 1.0.0
 */
function b4l_badges_issuer_information_save_into_db_table(){
    global $wpdb;
    
    if(isset($_POST['issuer_email_button'])){
        
        //Filters to verify the validity of elements
        if (filter_var($_POST['issuer_email'], FILTER_VALIDATE_EMAIL)) {
            $email = $_POST['issuer_email'];
        } else {
            $email = "Invalid email !";
        }
        if (preg_match("/\b(?:(?:https?|ftp):\/\/)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$_POST['issuer_url'])) {
            $url = $_POST['issuer_url'];
        } else {
            $url = "Invalid URL !";
        }
        if (preg_match("/\b(?:(?:https?|ftp):\/\/)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$_POST['issuer_logo'])) {
            $logo = $_POST['issuer_logo'];
        } else {
            $logoErr = "Invalid Logo Link !";
        }
        
        //If a row with the information exists, we update it.
        if($wpdb->get_row($wpdb->prepare( "SELECT * FROM ".$wpdb->prefix."b4l_issuer_information WHERE id = 1", "" ))) {
            $wpdb->update(
                        $wpdb->prefix . 'b4l_issuer_information',
                        array(
                            'issuer_name' => $_POST['issuer_name'],
                            'issuer_logo' => $logo,
                            'issuer_email' => $email,
                            'issuer_url' => $url
                        ),
                        array('id' => '1')
                    );
        //If not exist, we create the new row which will contain the information
        } else {
            $wpdb->insert(
                        $wpdb->prefix . 'b4l_issuer_information',
                        array(
                            'id' => 1,
                            'issuer_name' => $_POST['issuer_name'],
                            'issuer_logo' => $logo,
                            'issuer_email' => $email,
                            'issuer_url' => $url
                        )
                    );
        }
        ?>
        <script>
            //Informs the user that the data have been saved
            alert("Your changes have been saved and will be effective when you will change your current page !");
        </script>
        <?php
    }
}


?>
