<?php
/*
Plugin Name: Collapsing Archive widget
Plugin URI: http://robfelty.com
Description: Use the Collapsing Archives plugin as a widget
Version: 0.7.6
Author: Robert Felty
Author URI: http://robfelty.com
*/

  function collapsArchWidget() {
    //extract($args);
   /*
    $options	= get_option('widget_get_weather');
    $z		= empty($options['zip']) ? '02334' : $options['zip'];
    $l		= empty($options['location']) ? 'Mass' : $options['location'];
$o		= empty($options['options']) ? 'icon,temp,forecast,curtime,sunrise,sunset' : $options['options'];
    $title		= empty($options['title']) ? __('Local Weather') : $options['title'];
*/
?>
    <?php echo $before_widget; ?>
    <?php echo $before_title . $title . $after_title; ?>
      <li><h2>Archives</h2>

      <?php
       if( function_exists('collapsArch') ) {
        collapsArch();
       } else {
        echo "<ul>\n";
        wp_get_archives('type=monthly');
        echo "</ul>\n";
       }
      ?>

      </li>
    <?php echo $after_widget; ?>
<?php
  }

  /*
	function widget_fancy_archives_control() {
		$options = $newoptions = get_option('widget_fancy_archives');
		if ( $_POST['fancy_archives-submit'] ) {
			$newoptions['zip']	= strip_tags(stripslashes($_POST['get_weather-zip']));
			$newoptions['location']	= strip_tags(stripslashes($_POST['get_weather-location']));
			$newoptions['options']	= strip_tags(stripslashes($_POST['get_weather-options']));
			$newoptions['title']	= strip_tags(stripslashes($_POST['get_weather-title']));
		}
		if ( $options != $newoptions ) {
			$options = $newoptions;
			update_option('widget_get_weather', $options);
		}
		$title		= wp_specialchars($options['title']);
		$zip		= wp_specialchars($options['zip']);
		$location	= wp_specialchars($options['location']);
		$options	= wp_specialchars($options['options']);
 
  */
	//}

function collapsArchWidgetInit() {
	if (function_exists('register_sidebar_widget')) {
		register_sidebar_widget('Collapsing Archives', 'collapsArchWidget');
	//	register_widget_control('Fancy Archives', 'widget_fancy_archives_control', 300, 200);
	}
}

// Run our code later in case this loads prior to any required plugins.
if (function_exists('collapsArch')) {
	add_action('plugins_loaded', 'collapsArchWidgetInit');
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
