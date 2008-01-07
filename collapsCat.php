<?php
/*
Plugin Name: Collapsing Categories
Plugin URI: http://blog.robfelty.com/plugins
Description: Uses javascript to expand and collapse categories to show the posts that belong to the category 
Author: Robert Felty
Version: 0.2
Author URI: http://robfelty.com

Copyright 2007 Robert Felty

This work is largely based on the Fancy Categories plugin by Andrew Rader
(http://nymb.us), which was also distributed under the GPLv2. I have tried
contacting him, but his website has been down for quite some time now. See the
CHANGELOG file for more information.

This file is part of Collapsing Categories

		Collapsing Categories is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License as published by 
    the Free Software Foundation; either version 2 of the License, or (at your
    option) any later version.

    Collapsing Categories is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Collapsing Categories; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/ 

add_action( 'wp_head', array('collapsCat','get_head'));
add_action('activate_fancy-categories/fancy-categories.php', array('collapsCat','fancy_init'));
add_action('admin_menu', array('collapsCat','fancy_setup'));

class collapsCat {

	function fancy_init() {
		if( function_exists('add_option') ) {
			add_option( 'collapsCatShowPostCount', 'yes' );
		}
	}

	function fancy_setup() {
		if( function_exists('add_options_page') ) {
			add_options_page(__('Collapsing Categories'),__('Collapsing Categories'),1,basename(__FILE__),array('collapsCat','ui'));
		}
	}

	function ui() {
		include_once( 'collapsCatUI.php' );
	}

	function list_categories() {
		global $wpdb;

		include( 'collapsCatList.php' );

		return;
	}

	function get_head() {
		$url = get_settings('siteurl');
		echo "<script type=\"text/javascript\" src=\"$url/wp-content/plugins/collapsCat/collapsCat.js\"></script>\n";
		echo "<script type=\"text/javascript\">\n";
		echo "// <![CDATA[\n";
		echo "// These variables are part of the Collapsing Categories Plugin\n// Copyright 2007 Robert Felty (robfelty.com)\n";
		echo "// ]]>\n</script>\n";
    echo "
       <style type='text/css'>
         #sidebar ul ul li:before {content:'';}        
         span.collapsing {border:0;
           font-size:1em; 
           padding:0; 
           margin:0; 
           cursor:default}
       </style>
         ";
	}
}

function collapsCat() {
	collapsCat::list_categories();
}
?>
