<?php
 /*
  * Description:        Create a submenu padge in the administration menu to
  *                     fill a form with the issuer information (company info).
  * Version:            1.0.0
  * Author:             Alexandre Levacher
 */

add_action('admin_menu', 'b4l_badges_issuer_information_submenu_page');
 
function b4l_badges_issuer_information_submenu_page() {
    add_submenu_page(
        'edit.php?post_type=badge',
        'Badges Issuer Information',
        'Badges Issuer Information',
        'manage_options',
        'badges-issuer-information-submenu-page',
        'b4l_badges_issuer_information_page_callback' 
    );
}
 
function b4l_badges_issuer_information_page_callback() {
    b4l_code_html();
    b4l_php_code();
}



function b4l_code_html(){
    ?>
    <div class="wrap"><div id="icon-tools" class="icon32"></div>
        <h2>Badges Issuer Information</h2>
        <p>You have to give the issuer information before sending badges.</p>
        <p>Issuer information is information about your group/firm.</p>
        <br/>
        <form action="<?php esc_url(get_permalink(get_page_by_title('Your changes have been saved'))) ?>" method="POST">
            <h3>Issuer Name</h3>
            <input id="issuer_name" name="issuer_name"  type="text" size="70" value="<?php b4l_db_table_b4l_issuer_information_select("issuer_name") ?>" />
            <br/>
            <h3>Issuer Logo</h3>
            <input id="issuer_logo" name="issuer_logo"  type="text" size="70" value="<?php b4l_db_table_b4l_issuer_information_select("issuer_logo") ?>" />
            <br/>
            <h3>Issuer Email</h3>
            <input id="issuer_email" name="issuer_email"  type="text" size="70" value="<?php b4l_db_table_b4l_issuer_information_select("issuer_email") ?>" />
            <br/>
            <h3>Issuer URL</h3>
            <input id="issuer_url" name="issuer_url"  type="text" size="70" value="<?php b4l_db_table_b4l_issuer_information_select("issuer_url") ?>"  />
            <br/>
            <input id="issuer_email_button" name="issuer_email_button" type="submit" class="button-primary" value="Upload" />
        </form>
    </div>
<?php
}

function b4l_db_table_b4l_issuer_information_select($idElement){
    global $wpdb;
    $queryInfo = "SELECT * FROM ".$wpdb->prefix."b4l_issuer_information ";
    $results = $wpdb->get_results($queryInfo, ARRAY_A); 
    echo $results[0][$idElement];
}

function b4l_php_code(){
    global $wpdb;
    
    if(isset($_POST['issuer_email_button'])){
        if($wpdb->get_row($wpdb->prepare( "SELECT * FROM ".$wpdb->prefix."b4l_issuer_information WHERE id = 1", "" ))) {
            $wpdb->update(
                        $wpdb->prefix . 'b4l_issuer_information',
                        array(
                            'issuer_name' => $_POST['issuer_name'],
                            'issuer_logo' => $_POST['issuer_logo'],
                            'issuer_email' => $_POST['issuer_email'],
                            'issuer_url' => $_POST['issuer_url']
                        ),
                        array('id' => '1')
                    );
        } else {
            $wpdb->insert(
                        $wpdb->prefix . 'b4l_issuer_information',
                        array(
                            'id' => 1,
                            'issuer_name' => $_POST['issuer_name'],
                            'issuer_logo' => $_POST['issuer_logo'],
                            'issuer_email' => $_POST['issuer_email'],
                            'issuer_url' => $_POST['issuer_url']
                        )
                    );
        }
    }
}


?>


<script>
    document.getElementById("issuer_name").value = <?php $_POST['issuer_name'] ?>;
    document.getElementById("issuer_logo").value = <?php $results[0]["issuer_logo"] ?>;
</script>
       
