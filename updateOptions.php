<?php
		if( isset($_POST['expandCurrentYear']) ) {
			update_option( 'collapsArchExpandCurrentYear', 'yes' );
		}
		else {
			update_option( 'collapsArchExpandCurrentYear', 'no' );
		}

		if( isset($_POST['showYearCount']) ) {
			update_option( 'collapsArchShowYearCount', 'yes' );
		}
		else {
			update_option( 'collapsArchShowYearCount', 'no' );
		}

		if( isset($_POST['showMonths']) ) {
			update_option( 'collapsArchShowMonths', 'yes' );
		}
		else {
			update_option( 'collapsArchShowMonths', 'no' );
			update_option( 'collapsArchExpandCurrentMonth', 'no' );
			update_option( 'collapsArchShowMonthCount', 'no' );
			update_option( 'collapsArchExpandMonths', 'no' );
		}

		if( isset($_POST['expandCurrentMonth']) ) {
			update_option( 'collapsArchExpandCurrentMonth', 'yes' );
		}
		else {
			update_option( 'collapsArchExpandCurrentMonth', 'no' );
		}

		if( isset($_POST['showMonthCount']) ) {
			update_option( 'collapsArchShowMonthCount', 'yes' );
		}
		else {
			update_option( 'collapsArchShowMonthCount', 'no' );
		}

		if( isset($_POST['expandMonths']) ) {
			update_option( 'collapsArchExpandMonths', 'yes' );
		}
		else {
			update_option( 'collapsArchExpandMonths', 'no' );
		}

		if( isset($_POST['showCommentCount']) ) {
			update_option( 'collapsArchShowCommentCount', 'yes' );
		}
		else {
			update_option( 'collapsArchShowCommentCount', 'no' );
		}

		if( isset($_POST['showPages']) ) {
        update_option( 'collapsArchShowpages', 'yes' );
		}
		else {
        update_option( 'collapsArchShowpages', 'no' );
		}

		if( isset($_POST['showPostTitle']) ) {
			update_option( 'collapsArchShowPostTitle', 'yes' );
		}
		else {
			update_option( 'collapsArchShowPostTitle', 'no' );
		}

		$length= $_POST['postTitleLength'];
		update_option( 'collapsArchPosttitleLength', $length );

		if( isset($_POST['showPostTitleEllipsis']) ) {
			update_option( 'collapsArchShowPostTitleEllipsis', 'yes' );
		}
		else {
			update_option( 'collapsArchShowPostTitleEllipsis', 'no' );
		}

		if( isset($_POST['showPostDate']) ) {
			update_option( 'collapsArchShowPostDate', 'yes' );
		}
		else {
			update_option( 'collapsArchShowPostDate', 'no' );
		}

		$format= trim(stripslashes($_POST['postDateFormat']));
		update_option( 'collapsArchPostDateFormat', $format );

		if( isset($_POST['showPostNumber']) ) {
			update_option( 'collapsArchShowPostNumber', 'yes' );
		}
		else {
			update_option( 'collapsArchShowPostNumber', 'no' );
		}
    if($_POST['order'] == 'ASC') {
      update_option( 'collapsArchOrder', 'ASC' );
    } else {
      update_option( 'collapsArchOrder', 'DESC' );
    }
    if($_POST['archives'] == 'root') {
      update_option( 'collapsArchLinkToArchives', 'root' );
    } elseif ($_POST['archives'] == 'archives') {
      update_option( 'collapsArchLinkToArchives', 'archives' );
    } elseif ($_POST['archives'] == 'index') {
      update_option( 'collapsArchLinkToArchives', 'index' );
    }
    $excludeSafe=addslashes($_POST['collapsArchExclude']);
    update_option( 'collapsArchExclude', $excludeSafe);

    $includeSafe=addslashes($_POST['collapsArchInclude']);
    update_option( 'collapsArchInclude', $includeSafe);
?>
