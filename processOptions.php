<?php
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
  $debug=0;
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
  $debug = $options[$number]['debug'];
}
?>
