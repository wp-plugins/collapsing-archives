<?php
/*
Plugin Name: Collapsing Archives
Plugin URI: http://blog.robfelty.com/plugins
Description: Allows users to expand and collapse archive links like Blogger 
Author: Robert Felty
Version: 0.9.7
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
$url = get_settings('siteurl');
add_action('wp_head', wp_enqueue_script('scriptaculous-effects'));
add_action('wp_head', wp_enqueue_script('collapsFunctions', "$url/wp-content/plugins/collapsing-archives/collapsFunctions.js"));
add_action( 'wp_head', array('collapsArch','get_head'));
add_action( 'wp_footer', array('collapsArch','get_foot'));
add_action('admin_menu', array('collapsArch','setup'));
add_action('activate_collapsing-archives/collapsArch.php', array('collapsArch','init'));

class collapsArch {

	function init() {
    if (!get_option('collapsArchOptions')) {
      $options=array('%i%' => array('title' => 'Archives',
                   'showPostCount' => 'yes',
                   'inExcludeCat' => 'exclude', 'inExcludeCats' => '',
                   'inExcludeYear' => 'exclude', 'inExcludeYears' => '',
                   'showPosts' => 'yes', 'showPages' => 'no',
                   'linkToArch' => 'no', 'showYearCount' => 'yes',
                   'expandCurrentYear' => 'yes', 'expandMonths' => 'yes',
                   'showMonths' => 'yes', 'expandCurrentMonth' => 'yes',
                   'showMonthCount' => 'yes', 'showPostTitle' => 'yes',
                   'expand' => '0', 'showPostDate' => 'no', 
                   'postDateFormat' => 'm/d', 'animate' => '1',
                   'postTitleLength' => ''));
      update_option('collapsArchOptions', $options);
    }
    if( function_exists('add_option') ) {
      $style="span.collapsArch {border:0;
padding:0; 
margin:0; 
cursor:pointer;
}
#sidebar li.collapsArch:before {content:'';} 
#sidebar li.collapsArch {list-style-type:none}
#sidebar li.collapsArchPost {
       text-indent:-1em;
        list-style-type:none;
       margin:0 0 0 1em;}
#sidebar li.collapsArchPost:before {content: \"\\00BB \\00A0\" !important;} 
#sidebar li.collapsArch .sym {
         font-size:1.2em;
         font-family:Monaco, 'Andale Mono', 'FreeMono', 'Courier new', 'Courier', monospace;
         margin:2px 5px 0px 0; 
         line-height:.8em;
         padding:0;
         /* uncomment to put a box around +/-
         border:1px solid;
         height:.9em;
         display:inline-block;
         vertical-align:baseline;
         */
        }";
      add_option( 'collapsPageStyle', $style);
    }
	}

	function setup() {
		if( function_exists('add_options_page') ) {
			add_options_page(__('Collapsing Archives'),__('Collapsing
      Archives'),1,basename(__FILE__),array('collapsArch','ui'));
		}
	}

	function ui() {
		include_once( 'collapsArchUI.php' );
	}

	function list_archives($number) {
		global $wpdb;

		include( 'collapsArchList.php' );

		return;
	}

	function get_head() {
    $style=get_option('collapsArchStyle');
    echo "<style type='text/css'>
    $style
    </style>\n";
	}
  function get_foot() {
    $url = get_settings('siteurl');
		echo "<script type=\"text/javascript\">\n";
		echo "// <![CDATA[\n";
		echo "// These variables are part of the Collapsing Archives Plugin version: 0.9.7\n// Copyright 2008 Robert Felty (robfelty.com)\n";

    $expandSym="<img src='". $url .
         "/wp-content/plugins/collapsing-archives/" . 
         "img/expand.gif' alt='expand' />";
    $collapseSym="<img src='". $url .
         "/wp-content/plugins/collapsing-archives/" . 
         "img/collapse.gif' alt='collapse' />";
    echo "var expandSym=\"$expandSym\";";
    echo "var collapseSym=\"$collapseSym\";";
    echo"
    addLoadEvent(function() {
      autoExpandCollapse('collapsArch');
    });
    ";
		echo ";\n// ]]>\n</script>\n";
  }
}

function collapsArch($number) {
	collapsArch::list_archives($number);
}
include('collapsArchWidget.php');
?>
