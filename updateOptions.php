<?php

foreach ( (array) $_POST['collapsArch'] as $widget_number => $widget_collapsArch ) {
  if (!isset($widget_collapsArch['title']) && isset($options[$widget_number]) ) { // user clicked cancel
    continue;
  }
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
  $debug=0;
  if (isset($widget_collapsArch['debug'])) {
    $debug= 1 ;
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
?>
