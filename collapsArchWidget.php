<?php
  function collapsArchWidget() {
  extract($args);
  $options = get_option('collapsArchWidget');
  $title = ($options['title'] != "") ? $options['title'] : ""; 
    echo $before_widget . $before_title . $title . $after_title;
       if( function_exists('collapsArch') ) {
        collapsArch();
       } else {
        echo "<ul>\n";
        wp_get_archives('type=monthly');
        echo "</ul>\n";
       }
    echo $after_widget;
  }

function collapsArchWidgetInit() {
	$widget_ops = array('classname' => 'collapsArchWidget', 'description' => __('Archives expand and collapse to show posts'));
	if (function_exists('register_sidebar_widget')) {
    register_sidebar_widget('Collapsing Archives', 'collapsArchWidget');
    register_widget_control('Collapsing Archives', 'collapsArchWidgetControl','300px');
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

	function collapsArchWidgetControl() {
		$options = get_option('collapsArchWidget');
    if ( !is_array($options) ) {
      $options = array('title'=>'Archives'
      );
     }

		if ( $_POST['collapsArch-submit'] ) {
			$options['title']	= strip_tags(stripslashes($_POST['collapsArch-title']));
			include('updateOptions.php');
		}
    update_option('collapsArchWidget', $options);
		$title		= wp_specialchars($options['title']);
    // Here is our little form segment. Notice that we don't need a
    // complete form. This will be embedded into the existing form.
    echo '<p style="text-align:right;"><label for="collapsArch-title">' . __('Title:') . '<input class="widefat" style="width: 200px;" id="collapsArch-title" name="collapsArch-title" type="text" value="'.$title.'" /></label></p>';
  echo "<ul style='list-style-type:none;width:400px;margin:0;padding:0;'>";
    include('options.txt');
  echo "</ul>\n";
   ?>
   <?php
    echo '<input type="hidden" id="collapsArch-submit" name="collapsArch-submit" value="1" />';

	}
?>
