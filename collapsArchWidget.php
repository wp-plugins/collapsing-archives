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

    echo $before_widget . $before_title . $title . $after_title;
       if( function_exists('collapsArch') ) {
        collapsArch($number);
       } else {
        echo "<ul>\n";
        wp_list_cats('sort_column=name&optioncount=1&hierarchical=0');
        echo "</ul>\n";
       }

    echo $after_widget;
  }


function collapsArchWidgetInit() {
if ( !$options = get_option('collapsArchOptions') )
    $options = array();
  $control_ops = array('width' => 400, 'height' => 350, 'id_base' => 'collapsArch');
	$widget_ops = array('classname' => 'collapsArch', 'description' =>
  __('Archives expand and collapse to show months and/or posts'));
  $name = __('Collapsing Archives');

  $id = false;
  foreach ( array_keys($options) as $o ) {
    // Old widgets can have null values for some reason
    if ( !isset($options[$o]['title']) || !isset($options[$o]['title']) )
      continue;
    $id = "collapsArch-$o"; // Never never never translate an id
    wp_register_sidebar_widget($id, $name, 'collapsArchWidget', $widget_ops, array( 'number' => $o ));
    wp_register_widget_control($id, $name, 'collapsArchWidgetControl', $control_ops, array( 'number' => $o ));
  }

  // If there are none, we register the widget's existance with a generic template
  if ( !$id ) {
    wp_register_sidebar_widget( 'collapsArch-1', $name, 'collapsArchWidget', $widget_ops, array( 'number' => -1 ) );
    wp_register_widget_control( 'collapsArch-1', $name, 'collapsArchWidgetControl', $control_ops, array( 'number' => -1 ) );
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

    foreach ( (array) $_POST['collapsArch'] as $widget_number => $widget_collapsArch ) {
      if ( !isset($widget_collapsArch['title']) && isset($options[$widget_number]) ) // user clicked cancel
        continue;
      $title = strip_tags(stripslashes($widget_collapsArch['title']));
      $archSortOrder= 'DESC' ;
      if($widget_collapsArch['archSortOrder'] == 'ASC') {
        $archSortOrder= 'ASC' ;
      }
      $showPosts= 'yes' ;
      if($widget_collapsArch['showPosts'] == 'no') {
        $showPosts= 'no' ;
      }
      $linkToArch= 'yes' ;
      if($widget_collapsArch['linkToArch'] == 'no') {
        $linkToArch= 'no' ;
      }
      $showPostCount= 'no' ;
      if( isset($widget_collapsArch['showPostCount'])) {
        $showPostCount= 'yes' ;
      }
      $showPages= 'no' ;
      if( isset($widget_collapsArch['showPages'])) {
        $showPages= 'yes' ;
      }
      $showYearCount= 'yes' ;
      if( !isset($widget_collapsArch['showYearCount'])) {
        $showYearCount= 'no' ;
      }
      $expandCurrentYear= 'yes' ;
      if( !isset($widget_collapsArch['expandCurrentYear'])) {
        $expandCurrentYear= 'no' ;
      }
      $expand= $widget_collapsArch['expand'];
      $inExcludeYear= 'include' ;
      if($widget_collapsArch['inExcludeYear'] == 'exclude') {
        $inExcludeYear= 'exclude' ;
      }
      if($widget_collapsArch['inExcludeCat'] == 'exclude') {
        $inExcludeCat= 'exclude' ;
      }
      $showMonths='yes';
      if( !isset($widget_collapsArch['showMonths'])) {
        $showMonths= 'no' ;
      }
      $showMonthCount='yes';
      if( !isset($widget_collapsArch['showMonthCount'])) {
        $showMonthCount= 'no' ;
      }
      $expandMonths='yes';
      if( !isset($widget_collapsArch['expandMonths'])) {
        $expandMonths= 'no' ;
      }
      $showPostTitle='yes';
      if( !isset($widget_collapsArch['showPostTitle'])) {
        $showPostTitle= 'no' ;
      }
      $animate=1;
      if( !isset($widget_collapsArch['animate'])) {
        $animate= 0 ;
      }
      $showPostDate='no';
      if( isset($widget_collapsArch['showPostDate'])) {
        $showPostDate= 'yes' ;
      }
      $postDateFormat=addslashes($widget_collapsArch['postDateFormat']);
      $expandCurrentMonth='yes';
      if( !isset($widget_collapsArch['expandCurrentMonth'])) {
        $expandCurrentMonth= 'no' ;
      }
      $inExcludeYears=addslashes($widget_collapsArch['inExcludeYears']);
      $postTitleLength=addslashes($widget_collapsArch['postTitleLength']);
      $inExcludeCats=addslashes($widget_collapsArch['inExcludeCats']);
      $defaultExpand=addslashes($widget_collapsArch['defaultExpand']);
      $options[$widget_number] = compact( 'title','showPostCount',
          'inExcludeCat', 'inExcludeCats', 'inExcludeYear', 'inExcludeYears',
          'archSortOrder', 'showPosts', 'showPages', 'linkToArch',
          'showYearCount', 'expandCurrentYear','expandMonths', 'showMonths',
          'expandCurrentMonth','showMonthCount', 'showPostTitle', 'expand',
          'showPostDate', 'postDateFormat','animate','postTitleLength');
    }

    update_option('collapsArchOptions', $options);
    $updated = true;
  }

 if ( -1 == $number ) {
    /* default options go here */
    $title = 'Archives';
    $text = '';
    $showPostCount = 'yes';
    $archSortOrder = 'DESC';
    $defaultExpand='';
    $number = '%i%';
    $expand='1';
    $inExcludeCat='include';
    $inExcludeYear='include';
    $inExcludeCats='';
    $inExcludeYears='';
    $postTitleLength='';
    $showPosts='yes';
    $linkToArch='yes';
    $showPages='no';
    $expandCurrentYear='yes';
    $showYearCount='yes';
    $expandCurrentMonth='yes';
    $expandMonths='yes';
    $showMonthCount='yes';
    $showMonths='yes';
    $showPostTitle='yes';
    $showPostDate='no';
    $postDateFormat='m/d';
    $animate=1;
  } else {
    $title = attribute_escape($options[$number]['title']);
    $showPostCount = $options[$number]['showPostCount'];
    $expand = $options[$number]['expand'];
    $inExcludeCats = $options[$number]['inExcludeCats'];
    $inExcludeYears = $options[$number]['inExcludeYears'];
    $postTitleLength = $options[$number]['postTitleLength'];
    $inExcludeCat = $options[$number]['inExcludeCat'];
    $inExcludeYear = $options[$number]['inExcludeYear'];
    $archSortOrder = $options[$number]['archSortOrder'];
    $defaultExpand = $options[$number]['defaultExpand'];
    $showPosts = $options[$number]['showPosts'];
    $showPages = $options[$number]['showPages'];
    $linkToArch = $options[$number]['linkToArch'];
    $showYearCount = $options[$number]['showYearCount'];
    $expandCurrentYear = $options[$number]['expandCurrentYear'];
    $showMonthCount = $options[$number]['showMonthCount'];
    $showMonths = $options[$number]['showMonths'];
    $expandMonths = $options[$number]['expandMonths'];
    $expandCurrentMonth = $options[$number]['expandCurrentMonth'];
    $showPostTitle = $options[$number]['showPostTitle'];
    $showPostDate = $options[$number]['showPostDate'];
    $postDateFormat = $options[$number]['postDateFormat'];
    $animate = $options[$number]['animate'];
  }

		//$title		= wp_specialchars($options['title']);
    // Here is our little form segment. Notice that we don't need a
    // complete form. This will be embedded into the existing form.
    echo '<p style="text-align:right;"><label for="collapsArch-title-'.$number.'">' . __('Title:') . '<input class="widefat" style="width: 200px;" id="collapsArch-title-'.$number.'" name="collapsArch['.$number.'][title]" type="text" value="'.$title.'" /></label></p>';
  ?>
    <p>
     <input type="checkbox" name="collapsArch[<?php echo $number ?>][showPostCount]" <?php if ($showPostCount=='yes')  echo 'checked'; ?> id="collapsArch-showPostCount-<?php echo $number ?>"></input> <label for="collapsArchShowPostCount">Show Post Count </label>
     <input type="checkbox" name="collapsArch[<?php echo $number
     ?>][showPages]" <?php if ($showPages=='yes')  echo 'checked'; ?>
     id="collapsArch-showPages-<?php echo $number ?>"></input> <label
     for="collapsArchShowPages">Show Pages as well as posts </label>
    </p>
     <p>Display archives in 
     <select name="collapsArch[<?php echo $number ?>][archSortOrder]">
     <option  <?php if($archSortOrder=='ASC') echo 'selected'; ?>
     id="archSortOrderInclude-<?php echo $number ?>"
     value='include'>Chronological</option>
     <option  <?php if($archSortOrder=='DESC') echo 'selected'; ?>
     id="archSortOrderExclude-<?php echo $number ?>"
     value='exclude'>Reverse Chronological</option>
     </select>
    order
    </p>
    <p>Clicking on year/month :<br />
     <input type="radio" name="collapsArch[<?php echo $number ?>][linkToArch]"
     <?php if($linkToArch=='yes') echo 'checked'; ?>
     id="collapsArch-linkToArchYes-<?php echo $number ?>"
     value='yes'></input> <label for="collapsArch-linkToArchYes">Links to archive</label>
     <input type="radio" name="collapsArch[<?php echo $number ?>][linkToArch]"
     <?php if($linkToArch=='no') echo 'checked'; ?>
     id="collapsArch-linkToArchNo-<?php echo $number ?>"
     value='no'></input> <label for="linkToArchNo">Expands list </label>
    </p>
    <p>Expanding and collapse characters:<br />
     html: <input type="radio" name="collapsArch[<?php echo $number ?>][expand]" <?php if($expand==0) echo 'checked'; ?> id="expand0" value='0'></input> <label for="expand0">&#9658;&nbsp;&#9660;</label>
     <input type="radio" name="collapsArch[<?php echo $number ?>][expand]" <?php if($expand==1) echo 'checked'; ?> id="expand1" value='1'></input> <label for="expand1">+&nbsp;&mdash;</label>
     <input type="radio" name="collapsArch[<?php echo $number ?>][expand]"
     <?php if($expand==2) echo 'checked'; ?> id="expand2" value='2'></input>
     <label for="expand2">[+]&nbsp;[&mdash;]</label><br />
     images:
     <input type="radio" name="collapsArch[<?php echo $number ?>][expand]"
     <?php if($expand==3) echo 'checked'; ?> id="expand0" value='3'></input>
     <label for="expand3"><img src='<?php echo get_settings('siteurl') .
     "/wp-content/plugins/collapsing-archives/" ?>img/collapse.gif' />&nbsp;<img
     src='<?php echo get_settings('siteurl') .
     "/wp-content/plugins/collapsing-archives/" ?>img/expand.gif' /></label>
    </p>
    <p>
     <select name="collapsArch[<?php echo $number ?>][inExcludeYear]">
     <option  <?php if($inExcludeYear=='include') echo 'selected'; ?> id="inExcludeYearInclude-<?php echo $number ?>" value='include'>Include</option>
     <option  <?php if($inExcludeYear=='exclude') echo 'selected'; ?> id="inExcludeYearExclude-<?php echo $number ?>" value='exclude'>Exclude</option>
     </select>
     these years separated by commas):<br />
    <input type="text" name="collapsArch[<?php echo $number
    ?>][inExcludeYears]" value="<?php echo $inExcludeYears ?>"  
    id="collapsArch-inExcludeYears-<?php echo $number ?>"></input> 
    </p>
    <p>
     <select name="collapsArch[<?php echo $number ?>][inExcludeCat]">
     <option  <?php if($inExcludeCat=='include') echo 'selected'; ?> id="inExcludeCatInclude-<?php echo $number ?>" value='include'>Include</option>
     <option  <?php if($inExcludeCat=='exclude') echo 'selected'; ?> id="inExcludeCatExclude-<?php echo $number ?>" value='exclude'>Exclude</option>
     </select>
     these categories separated by commas):<br />
    <input type="text" name="collapsArch[<?php echo $number
    ?>][inExcludeCats]" value="<?php echo $inExcludeCats ?>"
    id="collapsArch-inExcludeCats-<?php echo $number ?>"><?php echo
    $inExcludeCats ?></input> 
    </p>
     <p>
     <input type="checkbox" name="collapsArch[<?php echo $number
     ?>][expandCurrentYear]" <?php if ($expandCurrentYear=='yes')  echo
     'checked'; ?> id="expandCurrentYear-<?php echo $number ?>"></input> <label for="expandCurrentYear">Leave Current Year Expanded by Default</label>
    </p>
    <p>
     <input type="checkbox" name="collapsArch[<?php echo $number
     ?>][showYearCount]" <?php if ($showYearCount=='yes')  echo 'checked'; ?>
     id="showYearCount=<?php echo $number ?>"></input> <label for="showYearCount">Show Post Count in Year Links</label>
    </p>
    <p>
     <input type="checkbox" name="collapsArch[<?php echo $number
     ?>][showMonths]" <?php if ($showMonths=='yes')  echo 'checked'; ?>
     id="showMonths-<?php echo $number ?>"></input> <label for="showMonths">Show Month Link</label>
   </p>
   <p>
      &nbsp;&nbsp;<input type="checkbox" name="collapsArch[<?php echo $number
      ?>][showMonthCount]" <?php if ($showMonthCount == 'yes') echo
      'checked'; ?> id="showMonthCount-<?php echo $number ?>"></input> <label
      for="showMonthCount">Show Post Count in Month Links</label><br />

        &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="collapsArch[<?php
        echo $number ?>][expandMonths]" <?php if($expandMonths=='yes') echo
        'checked'; ?> id="expandMonths-<?php echo $number ?>"></input> <label
        for="expandMonths">Month Links should expand to show Posts</label><br />
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox"
          name="collapsArch[<?php echo $number ?>][expandCurrentMonth]" <?php
          if ($expandCurrentMonth=='yes') echo 'checked'; ?>
          id="expandCurrentMonth-<?php echo $number ?>"></input> <label
          for="expandCurrentMonth">Leave Current Month Expanded by
          Default</label>
   </p>
   <p>
   <input type="checkbox" name="collapsArch[<?php echo $number
   ?>][showPostTitle]" <?php if ($showPostTitle=='yes') echo
   'checked'; ?> id="showPostTitle-<?php echo $number ?>"></input> <label
   for="showPostTitle">Show Post Title</label>
   | Truncate Post Title to
   <input type="text" size='3' name="collapsArch[<?php echo $number
   ?>][postTitleLength]"
   id="postTitleLength-<?php echo $number ?>" value="<?php echo
   $postTitleLength; ?>"></input> <label
   for="postTitleLength"> characters</label>
   </p>
   <p>
   <input type="checkbox" name="collapsArch[<?php echo $number
   ?>][showPostDate]" <?php if ($showPostDate=='yes') echo
   'checked'; ?> id="showPostDate-<?php echo $number ?>"></input> <label
   for="showPostDate">Show Post Date</label> | 
   <label for="postDateFormat">Post Date Format:</label>
   <input type="text" size='3' name="collapsArch[<?php echo $number
   ?>][postDateFormat]" value="<?php echo $postDateFormat; ?>" 
   id="postDateFormat-<?php echo $number ?>"></input>
   </p>
   <p>
   <input type="checkbox" name="collapsArch[<?php echo $number
   ?>][animate]" <?php if ($animate==1) echo
   'checked'; ?> id="animate-<?php echo $number ?>"></input> <label
   for="animate">Animate collapsing and expanding</label>
   </p>
   <?php
    echo '<input type="hidden" id="collapsArch-submit-'.$number.'" name="collapsArch['.$number.'][submit]" value="1" />';

	}
?>
