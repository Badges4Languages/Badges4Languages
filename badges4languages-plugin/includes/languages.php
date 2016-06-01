<?php
$filename = WP_PLUGIN_DIR . '/badges4languages-plugin/assets/languages.csv';
$lines = explode(",", file_get_contents($filename));
$colonnes = explode(PHP_EOL, file_get_contents($filename));
for ( $counter = 1; $counter < count($lines) - 1; $counter++ ) {
?>
    <option value="<?php echo $values; ?>"> 
        <?php echo print_r($colonnes);?>
    </option>
<?php 
}
        