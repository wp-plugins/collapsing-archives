<?php
/*
Plugin Name: Collapsing Archives
Plugin URI: http://blog.robfelty.com/plugins
Description: Allows users to expand and collapse archive links like Blogger 
Author: Robert Felty
Version: 1.2.beta
Author URI: http://robfelty.com

Copyright 2007-2009 Robert Felty

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

// LOCALIZATION
function collapsArch_load_domain() {
	load_plugin_textdomain( 'collapsArch', WP_PLUGIN_DIR."/".basename(dirname(__FILE__)), basename(dirname(__FILE__)) );
}
add_action('init', 'collapsArch_load_domain'); 


/****************/
if (!is_admin()) {
  add_action('wp_head', wp_enqueue_script('scriptaculous-effects'));
  add_action('wp_head', wp_enqueue_script('collapsFunctions',
  "$url/wp-content/plugins/collapsing-archives/collapsFunctions.js", '', '1.4'));
  add_action( 'wp_head', array('collapsArch','get_head'));
//  add_action( 'wp_footer', array('collapsArch','get_foot'));
}
add_action('admin_menu', array('collapsArch','setup'));
add_action('activate_collapsing-archives/collapsArch.php', array('collapsArch','init'));

class collapsArch {

	function init() {
    include('collapsArchStyles.php');
    $defaultStyles=compact('selected','default','block','noArrows','custom');
    if( function_exists('add_option') ) {
      update_option( 'collapsArchOrigStyle', $style);
      update_option( 'collapsArchDefaultStyles', $defaultStyles);
    }
    if (!get_option('collapsArchStyle')) {
			add_option( 'collapsArchStyle', $style);
		}
    if (!get_option('collapsPageSidebarId')) {
      add_option( 'collapsPageSidebarId', 'sidebar');
    }

	}

	function setup() {
		if( function_exists('add_options_page') ) {
			add_options_page(__('Collapsing Archives', 'collapsArch'),__('Collapsing Archives', 'collapsArch'),1,basename(__FILE__),array('collapsArch','ui'));
		}
	}

	function ui() {
		include_once( 'collapsArchUI.php' );
	}



	function get_head() {
    $style=stripslashes(get_option('collapsArchStyle'));
    echo "<style type='text/css'>
    $style
    </style>\n";
	}
  function get_foot() {
    $url = get_settings('siteurl');
		echo "<script type=\"text/javascript\">\n";
		echo "// <![CDATA[\n";
		echo '/* These variables are part of the Collapsing Archives Plugin
		       * version: 1.2.beta
					 * revision: $Id$
					 * Copyright 2008 Robert Felty (robfelty.com)
					 */' ."\n";

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

function collapsArch($args='') {
  include_once( 'collapsArchList.php' );
  if (!is_admin()) {
    list_archives($args);
  }
}
include('collapsArchWidget.php');
?>
