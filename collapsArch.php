<?php
/*
Plugin Name: Collapsing Archives
Plugin URI: http://blog.robfelty.com/plugins/collapsing-archives
Description: Allows users to expand and collapse archive links like Blogger.  <a href='options-general.php?page=collapsArch.php'>Options and Settings</a> | <a href='http://wordpress.org/extend/plugins/collapsing-archives/other_notes'>Manual</a> | <a href='http://wordpress.org/extend/plugins/collapsing-archives/faq'>FAQ</a> | <a href='http://forum.robfelty.com/forum/collapsing-archives'>User forum</a> 
Author: Robert Felty
Version: 1.3
Author URI: http://robfelty.com

Copyright 2007-2010 Robert Felty

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
global $collapsArchVersion;
$collapsArchVersion = '1.3';

// LOCALIZATION
function collapsArch_load_domain() {
	load_plugin_textdomain( 'collapsArch', WP_PLUGIN_DIR."/".basename(dirname(__FILE__)), basename(dirname(__FILE__)) );
}
add_action('init', 'collapsArch_load_domain'); 


/****************/
if (!is_admin()) {
  wp_enqueue_script('collapsFunctions',
      WP_PLUGIN_URL . "/collapsing-archives/collapsFunctions.js",
      array('jquery'), '1.7');
  add_action( 'wp_head', array('collapsArch','get_head'));
} else {
  // call upgrade function if current version is lower than actual version
  $dbversion = get_option('collapsArchVersion');
  if (!$dbversion || $collapsArchVersion != $dbversion)
    collapsArch::init();
}
add_action('admin_menu', array('collapsArch','setup'));
add_action('activate_collapsing-archives/collapsArch.php', array('collapsArch','init'));

class collapsArch {

	function init() {
    global $collapsArchVersion;
    include('collapsArchStyles.php');
    $dbversion = get_option('collapsArchVersion');
    if ($collapsArchVersion != $dbversion && $selected!='custom') {
      $style = $defaultStyles[$selected];
      update_option( 'collapsArchStyle', $style);
      update_option( 'collapsArchVersion', $collapsArchVersion);
    }
    $defaultStyles=compact('selected','default','block','noArrows','custom');
    if( function_exists('add_option') ) {
      update_option( 'collapsArchOrigStyle', $style);
      update_option( 'collapsArchDefaultStyles', $defaultStyles);
    }
    if (!get_option('collapsArchStyle')) {
			add_option( 'collapsArchStyle', $style);
		}
    if (!get_option('collapsArchSidebarId')) {
      add_option( 'collapsArchSidebarId', 'sidebar');
    }
    if (!get_option('collapsArchVersion')) {
      add_option( 'collapsArchVersion', $collapsArchVersion);
		}

	}

	function setup() {
		if( function_exists('add_options_page') && current_user_can('manage_options') ) {
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
}

  include_once( 'collapsArchList.php' );
function collapsArch($args='') {
  if (!is_admin()) {
    $archives = list_archives($args);
  }
		$archives .= "<script type=\"text/javascript\">\n";
		$archives .= "// <![CDATA[\n";
		$archives .= '/* These variables are part of the Collapsing Archives Plugin
 * version: 1.2.2
 * revision: $Id$
 * Copyright 2008 Robert Felty (robfelty.com)
         */' ."\n";

    $expandSym="<img src='". $url .
         "/wp-content/plugins/collapsing-archives/" . 
         "img/expand.gif' alt='expand' />";
    $collapseSym="<img src='". $url .
         "/wp-content/plugins/collapsing-archives/" . 
         "img/collapse.gif' alt='collapse' />";
    $archives .= "var expandSym=\"$expandSym\";";
    $archives .= "var collapseSym=\"$collapseSym\";";
    $archives .="
    collapsAddLoadEvent(function() {
      autoExpandCollapse('collapsArch');
    });
    ";
		$archives .= ";\n// ]]>\n</script>\n";
  print $archives;
}
$version = get_bloginfo('version');
if (preg_match('/^2\.[8-9]/', $version)) 
  include('collapsArchWidget.php');
?>
