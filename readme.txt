=== Collapsing Archives ===
Contributors: robfelty
Donate link: http://blog.robfelty.com/wordpress-plugins
Tags: archives, sidebar, widget
Requires at least: 2.0
Tested up to: 2.5
Stable tag: 0.8

This plugin uses Javascript to dynamically expand or collaps the set of
months for each year and posts for each month in the archive listing.

== Description ==

This is a relatively simple plugin that uses Javascript to
make the Archive links in the sidebar collapsable by year.

It is largely based off of the Fancy Archives Plugin by Andrew Rader
 
See the CHANGELOG for more information

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
Collapsing Archives, and Collapsing Archives Widget. You must enable both
of them, in that order. Then simply go the Presentation > Widgets section and
drag over the Collapsing Archives Widget.

== Frequently Asked Questions ==

1. How do I change the style of the collapsing archives lists?
   You can modify the collapsArch.css file to your liking

== Screenshots ==

1. Collapsing archives with default theme
2. available options

== Demo ==

I use this plugin in my blog at http://blog.robfelty.com


== OPTIONS AND CONFIGURATIONS ==

Options for Collapsing Archives are found under Options -> Collapsing
Archives. So far, there are the following options:

  * Use chronological or reverse chronological ordering
  * Links point to root, index.php or archives.php
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

== HISTORY ==

* 0.8 
    * Verified to work with wordpress 2.5
    * Now has custom styling option through the collapsArch.css stylesheet
    * updated screenshots
    * (Hopefully) fixed multi-language support for titles (put htmlentitites back in)
    * moved javascript into collapsArch.php and got rid of separate file

* 0.7.8 
    * Got rid of htmlentities in post titles. Should display better now

* 0.7.7
    * Now links should work with all sorts of permalink structures. Thanks to
      Krysthora http://krysthora.free.fr/ for finding this bug

* 0.7.6
    * fixed some more markup issues to make it valid xhtml

* 0.7.5
    * fixed bug when turning off "month links should expand to show posts" 
      option

* 0.7.4
    * fixed broken links

* 0.7.3
    * posts now have the class "collapsCatPost" and can be styled with CSS.
      Some styling has been added in collapsCat.php
    * removed list icons in front of triangles
    
* 0.7.2
    * Added option to link to index.php, root, or archives.php

* 0.7.1
    * Fixed comment count feature in post links
    * Fixed display of date in post links
    * Fixed automatic loading of options into database

* 0.7:
		* Complete rewrite of database code to reduce the number of queries from
		  2 * #months + 1 to 1 single query

* 0.6.2: 
    * Added collapsing class to <li>s with triangles for CSS styling
    * Added style information to make triangles bigger and give a pointer
      cursor over them
    * Added title tags to triangles to indicate functionality

* 0.6.1:
    * Bug fix - fixed the previous year triangle pointing in the wrong 
      direction
    * Changed default options to reflect how I use it on my website

* 0.6: 
    * Changed name from Fancy Archives to Collapsing Archives
    * Changed author from Andrew Rader to Robert Felty
    * Added option to link to archives.php
    * Added option to list in chronological or reverse chronological order
    * Added triangles which mark the collapsing and expanding features
      That is, clicking on the triangle collapses or expands, while clicking
      on a month or year links to the archives for the said month or year
    * Changed behavior from starting all expanded and then collapsing on page
      load to the opposite
    * Removed the rel='hide' and rel='show' tags, because they are not xhtml
      1.0 compliant. Now uses the CSS classes instead

---------------------------------------------------------------------------
Fancy Archives Changelog
* 0.5:
    * Added option to display Page entries with Posts inside the month links
    * Cleaned up the list generation code

* 0.4:
    * Added option: Trim post titles to a set size
    * Added option: Optionally show ellipsis if a post title was shrunk
    * Fix: Added fix for when page's content-type is "application/xhtml+xml"

* 0.3:
    * Huge rewrite: cleaned up javascript - one function does all the work,
      javascript no longer visible in page source
    * Added options: month links are optional, set current year/month to be
      expanded by default
    * Links now link to 'javascript;' instead of '#'

* 0.2.5:
    * Fixed an issue with displaying comment counts in < WP2.0, fixed by using
      WP's internal comment counting function (Thanks Will)

* 0.2:
    * Massive update, now has a dedicated options page (no more passing
      options to function)
    * Month links can expand to show individual posts

* 0.1:
	* Initial Release
