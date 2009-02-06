<?php

function collapsArchWidget($args, $widget_args=1) {
  extract($args, EXTR_SKIP);
  if ( is_numeric($widget_args) )
    $widget_args = array( 'number' => $widget_args );
  $widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );
  extract($widget_args, EXTR_SKIP);

  $options = get_option('collapsArchOptions');
  if ( !isset($options[$number]) )
    return;

  $title = ($options[$number]['title'] != "") ? $options[$number]['title'] : ""; 

  echo $before_widget . $before_title . __($title) . $after_title;
     if( function_exists('collapsArch') ) {
      collapsArch($number);
     } else {
      echo "<ul>\n";
      wp_list_archives('sort_column=name&optioncount=1&hierarchical=0');
      echo "</ul>\n";
     }

  echo $after_widget;
}


function collapsArchWidgetInit() {
if ( !$options = get_option('collapsArchOptions') )
    $options = array();
  $control_ops = array('width' => 400, 'height' => 350, 'id_base' => 'collapsarch');
	$widget_ops = array('classname' => 'collapsArch', 'description' =>
  __('Archives expand and collapse to show months and/or posts'));
  $name = __('Collapsing Archives');

  $id = false;
  foreach ( array_keys($options) as $o ) {
    // Old widgets can have null values for some reason
    if ( !isset($options[$o]['title']) || !isset($options[$o]['title']) )
      continue;
    $id = "collapsarch-$o"; // Never never never translate an id
    wp_register_sidebar_widget($id, $name, 'collapsArchWidget', $widget_ops, array( 'number' => $o ));
    wp_register_widget_control($id, $name, 'collapsArchWidgetControl', $control_ops, array( 'number' => $o ));
  }

  // If there are none, we register the widget's existance with a generic template
  if ( !$id ) {
    wp_register_sidebar_widget( 'collapsarch-1', $name, 'collapsArchWidget', $widget_ops, array( 'number' => -1 ) );
    wp_register_widget_control( 'collapsarch-1', $name, 'collapsArchWidgetControl', $control_ops, array( 'number' => -1 ) );
  }

}

// Run our code later in case this loads prior to any required plugins.
if (function_exists('collapsArch')) {
	add_action('widgets_init', 'collapsArchWidgetInit');
} else {
	$fname = basename(__FILE__);
	$current = get_settings('active_plugins');
	array_splice($current, array_search($fname, $current), 1 ); // Array-fu!
	update_option('active_plugins', $current);
	do_action('deactivate_' . trim($fname));
	header('Location: ' . get_settings('siteurl') . '/wp-admin/plugins.php?deactivate=true');
	exit;
}

function collapsArchWidgetControl($widget_args) {
  global $wp_registered_widgets;
  static $updated = false;

  if ( is_numeric($widget_args) )
    $widget_args = array( 'number' => $widget_args );
  $widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );
  extract( $widget_args, EXTR_SKIP );

  $options = get_option('collapsArchOptions');
  if ( !is_array($options) )
    $options = array();

  if ( !$updated && !empty($_POST['sidebar']) ) {
    $sidebar = (string) $_POST['sidebar'];

    $sidebars_widgets = wp_get_sidebars_widgets();
    if ( isset($sidebars_widgets[$sidebar]) )
      $this_sidebar =& $sidebars_widgets[$sidebar];
    else
      $this_sidebar = array();

    foreach ( $this_sidebar as $_widget_id ) {
      if ( 'collapsArchWidget' == $wp_registered_widgets[$_widget_id]['callback'] && isset($wp_registered_widgets[$_widget_id]['params'][0]['number']) ) {
        $widget_number = $wp_registered_widgets[$_widget_id]['params'][0]['number'];
        if ( !in_array( "collapsArch-$widget_number", $_POST['widget-id'] ) ) // the widget has been removed.
          unset($options[$widget_number]);
      }
    }
    include('updateOptions.php');
  }
  include('processOptions.php');

    // Here is our little form segment. Notice that we don't need a
    // complete form. This will be embedded into the existing form.
    echo '<p style="text-align:right;"><label for="collapsArch-title-'.$number.'">' . __('Title:') . '<input class="widefat" style="width: 200px;" id="collapsArch-title-'.$number.'" name="collapsArch['.$number.'][title]" type="text" value="'.$title.'" /></label></p>';
  include('options.txt');
  ?>
  <p>Style can be set from the <a
  href='options-general.php?page=collapsArch.php'>options page</a></p>
  <?php
  echo '<input type="hidden" id="collapsArch-submit-'.$number.'" name="collapsArch['.$number.'][submit]" value="1" />';

}
?>
