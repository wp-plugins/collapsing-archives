<?php
/*
Plugin Name: Collapsing Archives
Plugin URI: http://blog.robfelty.com/plugins
Description: Allows users to expand and collapse archive links like Blogger 
Author: Robert Felty
Version: 0.6
Author URI: http://robfelty.com

Copyright 2007 Robert Felty

This work is largely based on the Fancy Archives plugin by Andrew Rader
(http://nymb.us), which was also distributed under the GPLv2. I have tried
contacting him, but his website has been down for quite some time now. See the
CHANGELOG file for more information.

This file is part of Collapsing Archives

    Collapsing Archives is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    Collapsing Archives is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Collapsing Archives; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/ 

add_action( 'wp_head', array('collapsArch','get_head'));
add_action('activate_Collapsing-Archives/CollapsArch.php', array('collapsArch','init'));
add_action('admin_menu', array('collapsArch','setup'));

class collapsArch {

	function init() {
		if( function_exists('add_option') ) {
			add_option( 'collapsArchOrder', 'DESC' );
			add_option( 'collapsArchLinkToArchives', 'no' );
			add_option( 'collapsArchExpandCurrentYear', 'no' );
			add_option( 'collapsArchExpandCurrentMonth', 'no' );
			add_option( 'collapsArchShowYearCount', 'no' );
			add_option( 'collapsArchShowMonths', 'yes' );
			add_option( 'collapsArchShowMonthCount', 'no' );
			add_option( 'collapsArchExpandMonths', 'no' );
			add_option( 'collapsArchShowCommentCount', 'no' );
      add_option( 'collapsArchShowPages', 'no' );
			add_option( 'collapsArchShowPostTitle', 'no' );
			add_option( 'collapsArchShowPostTitleLength', '0' );
			add_option( 'collapsArchShowPostTitleEllipsis', 'yes' );
			add_option( 'collapsArchShowPostDate', 'no' );
			add_option( 'collapsArchPostDateFormat', '' );
			add_option( 'collapsArchShowPostNumber', 'no' );
		}
	}

	function setup() {
		if( function_exists('add_options_page') ) {
			add_options_page(__('Collapsing Archives'),__('Collapsing Archives'),1,basename(__FILE__),array('collapsArch','ui'));
		}
	}

	function ui() {
		include_once( 'collapsArchUI.php' );
	}

	function list_archives() {
		global $wpdb;

		include( 'collapsArchList.php' );

		return;
	}

	function get_head() {
		$url = get_settings('siteurl');
		echo "<script type=\"text/javascript\" src=\"$url/wp-content/plugins/collapsing-archives/collapsArch.js\"></script>\n";
		echo "<script type=\"text/javascript\">\n";
		echo "// <![CDATA[\n";
		echo "// These variables are part of the Collapsing Archives Plugin\n// Copyright 2007 Robert Felty (robfelty.com)\n";
		echo "collapsArchExpCurrYear = ";
		if (get_option('collapsArchExpandCurrentYear')=='yes') {
			echo "true";
		}
		else {
			echo "false";
		}
		echo ";\ncollapsArchExpCurrMonth = ";
		if (get_option('collapsArchExpandCurrentMonth')=='yes') {
			echo "true";
		}
		else {
			echo "false";
		}
		echo ";\n// ]]>\n</script>\n";
	}

}

function collapsArch() {
	collapsArch::list_archives();
}
?>
