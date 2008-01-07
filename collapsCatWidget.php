<?php
/*
Plugin Name: Collapsing categories widget
Plugin URI: http://robfelty.com
Description: Use the Collapsing Categories plugin as a widget
Version: 0.2
Author: Robert Felty
Author URI: http://robfelty.com
*/

  function collapsCatWidget() {
?>
    <?php echo $before_widget; ?>
    <?php echo $before_title . $title . $after_title; ?>
      <li><h2>Categories</h2>

      <?php
       if( function_exists('collapsCat') ) {
        collapsCat();
       } else {
        echo "<ul>\n";
        wp_list_cats('sort_column=name&optioncount=1&hierarchical=0');
        echo "</ul>\n";
       }
      ?>

      </li>
    <?php echo $after_widget; ?>
<?php
  }


function collapsCatWidgetInit() {
	if (function_exists('register_sidebar_widget')) {
		register_sidebar_widget('Collapsing Categories Widget', 'collapsCatWidget');
	}
}

// Run our code later in case this loads prior to any required plugins.
if (function_exists('collapsCat')) {
	add_action('plugins_loaded', 'collapsCatWidgetInit');
} else {
	$fname = basename(__FILE__);
	$current = get_settings('active_plugins');
	array_splice($current, array_search($fname, $current), 1 ); // Array-fu!
	update_option('active_plugins', $current);
	do_action('deactivate_' . trim($fname));
	header('Location: ' . get_settings('siteurl') . '/wp-admin/plugins.php?deactivate=true');
	exit;
}

?>
