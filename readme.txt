=== Collapsing Archives ===
Contributors: robfelty
Donate link: http://blog.robfelty.com/wordpress-plugins
Tags: archives
Requires at least: 2.0
Tested up to: 2.3
Stable tag: 0.6.1

This plugin uses Javascript to dynamically expand or collaps the set of
months for each year and posts for each month in the archive listing.

== Description ==

This is a relatively simple plugin that uses Javascript to
make the Archive links in the sidebar collapsable by year.

It is largely based off of the Collapsing Categories Plugin by Andrew Rader

== Installation ==

MANUAL INSTALLATION

Unpack the tar contents to wp-content/plugins/ so that the
files are in a collapsArch directory. Now enable the
plugin. To use the plugin, change the following here
appropriate (most likely sidebar.php):

Change From:

    <ul>
     `<?php wp_get_archives(your_options_here); ?>`
    </ul>

To something of the following:
`
    <?php
     if( function_exists('collapsArch') ) {
      collapsArch();
     } else {
      echo "<ul>\n";
      wp_get_archives(your_options_here);
      echo "</ul>\n";
     }
    ?>
`

This way, if you ever disable the plugin, your blog doesn't die.

**Note**: `wp_get_archives` can be substituted for
`wp_list_archives` depending on the design of the theme, so
be sure to edit appropriately. Also, substitute
`your_options_here` with what the appropriate options are
for your theme.

WIDGET INSTALLATION

For those who have widget capabilities, (default in Wordpress 2.3+), installation is easier. 

Unpackage contents to wp-content/plugins/ so that the files are in a collapsCat
directory. There should be 2 new plugins in your Wordpress Admin interface --
Collapsing Categories, and Collapsing Categories Widget. You must enable both
of them, in that order. Then simply go the Presentation > Widgets section and
drag over the Collapsing Categories Widget.

== Frequently Asked Questions ==

None yet.

== Screenshots ==

1. available options

== Demo ==

I use this plugin in my blog at http://blog.robfelty.com


== OPTIONS AND CONFIGURATIONS ==

Options for Collapsing Archives are found under Options -> Collapsing
Archives. So far, there are the following options:

  * Use chronological or reverse chronological ordering
  * Links point to index.php or archives.php
  * Leave Current Year Expanded by Default
  * Display number of posts in a year
  * Show Month Link
    * Display number of posts in a month
    * Enable month links to expand to show posts
      * Display pages and posts, or just posts
    * Leave Current Month Expanded by Defaul
  * Posts can be displayed with any of the following:
    * Number (ID)
    * Title
    * Comment Count

== CAVEAT ==

This plugin relies on Javascript, but does degrade
gracefully if it is not present/enabled to show all of the
archive links as usual.


