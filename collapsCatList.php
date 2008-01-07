<?php
/*

Collapsing Categories 0.2
Copyright 2007 Robert Felty

This work is largely based on the Collapsing Categories plugin by Andrew Rader
(http://voidsplat.org), which was also distributed under the GPLv2. I have tried
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

// Helper functions

/* the category and tagging database structures changed drastically between wordpress 2.1 and 2.3. We will use different queries for category based vs. term_taxonomy based database structures */
//$taxonomy=false;
$taxonomy=true;
$tables = $wpdb->query("show tables like 'wp_term_relationships'"); 
if ($tables==0) {
  $taxonomy=false;
}
function print_category( $cat, $categories, $taxonomy, $nested = false ) {
  global $wpdb;
  if ($taxonomy==true) {
    $link = '<a href="'.$cat->slug.'" ';
  } else {
    $link = '<a href="'.get_category_link($cat->cat_ID).'" ';
  }
  if ( empty($cat->category_description) ) {
      $link .= 'title="'. sprintf(__("View all posts filed under %s"), wp_specialchars($cat->name)) . '"';
  }
  else {
      $link .= 'title="' . wp_specialchars(apply_filters('category_description',$cat->category_description,$cat)) . '"';
  }
  $link .= '>';
  if ($taxonomy==true) {
    $link .= apply_filters('list_cats', $cat->name, $cat).'</a>';
  } else {
    $link .= apply_filters('list_cats', $cat->cat_name, $cat).'</a>';
  }

  if( get_option('collapsCatShowPostCount')=='yes') {
    if ($taxonomy==true) {
      $link .= ' ('.intval($cat->count).')';
    } else {
      $link .= ' ('.intval($cat->category_count).')';
    }
  }
  print( "<li>\n" );

// this statement works from the command line. It returns all the post titles for a given category. 
//echo "taxonomy = $taxonomy\n";
  if ($taxonomy==1) {
    $postquery = "SELECT $wpdb->posts.post_title, $wpdb->posts.post_name, date($wpdb->posts.post_date) as 'date' FROM $wpdb->posts, $wpdb->terms, $wpdb->term_taxonomy, $wpdb->term_relationships  WHERE $wpdb->posts.ID = $wpdb->term_relationships.object_id AND $wpdb->term_taxonomy.term_taxonomy_id=$cat->term_id AND $wpdb->posts.post_status='publish' AND $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id AND $wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id";
  } else {
    $postquery = "SELECT $wpdb->posts.post_title, $wpdb->posts.post_name, DATE($wpdb->posts.post_date) AS 'date' FROM $wpdb->posts, $wpdb->post2cat, $wpdb->categories where $wpdb->post2cat.category_id = $cat->cat_ID and $wpdb->posts.ID = $wpdb->post2cat.post_id and $wpdb->categories.cat_ID = $wpdb->post2cat.category_id and $wpdb->posts.post_status = 'publish' and $wpdb->categories.category_count>0";
  }
  $posts= $wpdb->get_results($postquery); 

    $url = get_settings('siteurl');
    if( empty( $posts ) && empty($categories)) {
        print( "<span class='collapsing show' onclick='hideNestedList(event); return false'>&#9660;&nbsp;</span>" );
    }
    else {
        print( "<span class='collapsing show' onclick='hideNestedList(event); return false'>&#9658;&nbsp;</span>" );
    }

    print( $link );

    if( ! empty($posts) ) {
        //echo "<!--\n";
        print( "\n<ul style=\"display:none;\">\n" );
        foreach ($posts as $post ) {
          $date=preg_replace("/-/", '/', $post->date);
          $name=$post->post_name;
          echo "<li><a href='$url/$date/$name'>" .  $post->post_title . "</a></li>\n";
        }
        print( "\n</ul>\n" );
        //echo "-->\n";
    }


    print( "\n</li>\n" );
}
?>

<ul id="collapsCat" class="collapsCat">
<?php
    global $wpdb;

if ($taxonomy==true) {
  $query = "SELECT $wpdb->terms.term_id, $wpdb->terms.name, $wpdb->terms.slug, $wpdb->term_taxonomy.count FROM $wpdb->terms, $wpdb->term_taxonomy WHERE $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id AND $wpdb->term_taxonomy.count >0 AND $wpdb->terms.name != 'Blogroll' AND $wpdb->term_taxonomy.taxonomy = 'category'";
} else {
  $query = "SELECT cat_ID, cat_name, category_nicename, category_description, category_parent, category_count FROM $wpdb->categories WHERE cat_ID > 0 AND category_parent = 0 AND category_count > 0";
}
  $categories = $wpdb->get_results($query);

  foreach( $categories as $cat ) {
      print_category( $cat, $categories,$taxonomy );
  }
?>
</ul>
