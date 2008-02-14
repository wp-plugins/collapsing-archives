<?php
/*
Collapsing Archives version: 0.7.3

Copyright 2007 Robert Felty

This work is largely based on the arch Archives plugin by Andrew Rader
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
?>

<ul id="collapsArchList">
<?php
global $wpdb, $month;

$now = current_time( 'mysql' );

$post_attrs = "post_date != '0000-00-00 00:00:00' AND post_status = 'publish'";

if( get_option('collapsArchShowPages')=='no' ) {
  $post_attrs .= " AND post_type = 'post'";
}
if (get_option('collapsArchOrder')=='ASC') {
  $order='ASC';
} else {
  $order='DESC';
}
if (get_option('collapsArchLinkToArchives')=='archives') {
  $archives='archives.php/';
} elseif (get_option('collapsArchLinkToArchives')=='index') {
  $archives='index.php/';
} elseif (get_option('collapsArchLinkToArchives')=='root') {
  $archives='';
}


$allPosts = $wpdb->get_results("SELECT ID, post_title,
          post_date, YEAR(post_date) AS `year`,
    MONTH(post_date) AS `month` FROM $wpdb->posts
    WHERE post_date < '$now' AND $post_attrs ORDER BY post_date $order");
/*echo "<!--\n";
print_r($archPosts);
print_r($allPosts);
echo "-->\n";
*/
if( $allPosts ) {
  $currentYear = -1;
  $currentMonth = -1;
  $lastMonth=-1;
  $lastYear=-1;
  foreach ($allPosts as $post) {
    if ($post->year != $lastyear) {
      $lastYear=$post->year;
    }
    if ($post->month != $lastMonth) {
      $lastMonth=$post->month;
    }
    #foreach ($a as $b) {
      $yearCounts{"$lastYear"}++;
      $monthCounts{"$lastYear$lastMonth"}++;
    #}
  }
  #print_r($count_values);
  $newYear = false;
  $newMonth = false;
  $closePreviousYear = false;
  $monthCount=0;
  $i=0;
  foreach( $allPosts as $archPost ) {
    $ding = '&#9658;';
    $i++;
    $yearRel = 'show';
    $monthRel = 'show';
    $yearTitle= 'click to expand';
    $monthTitle= 'click to expand';
    $postStyle = "style='display:none'";
    $monthStyle = "style='display:none'";
    /* rel = show means that it will be hidden, and clicking on the
     * triangle will expand it. rel = hide means that is will be shown, and
     * clicking on the triangle will collapse (hide) it 
     */
    if( get_option('collapsArchExpandCurrentYear')=='yes'
        && $archPost->year == date('Y') ) {
      $ding = '&#9660;';
      $yearRel = "hide";
      $yearTitle= 'click to collapse';
      $monthStyle = '';
    }


    if( $currentYear != $archPost->year ) {
      $lastYear=$currentYear;
      $currentYear = $archPost->year;
      $newYear = true;
      if( get_option('collapsArchShowYearCount')=='yes') {
         $yearCount = ' (' . $yearCounts{"$currentYear"} . ")\n";
      }
      else {
        $yearCount = '';
      }
      
      if($i>=2 && $allPosts[$i-2]->year != $archPost->year ) {
        echo "        </ul>\n      </li> <!-- close month --> \n";
        echo "    </ul>\n  </li> <!-- end year -->\n";
      }
      echo "  <li class='collapsing'><span title='$yearTitle' class='collapsing $yearRel' onclick='hideNestedList(event); return false' >$ding&nbsp;</span>";
      $home = get_settings('home');
      echo "<a href='$home/$archives$currentYear'>$currentYear</a>$yearCount\n";
      echo "    <ul $monthStyle id='collapsArchList-$currentYear'>\n";
      $newYear = false;
    }

    if($currentMonth != $archPost->month ) {
      $currentMonth = $archPost->month;
      $newMonth = true;
      if($newYear == false) { #close off last month
        $newYear=true; 
      } else {
        echo "        </ul>\n      </li> <!-- close month $currentMonth," .  $allPosts[$i-2]->month . " --> \n";
      }

      if( get_option('collapsArchShowMonthCount')=='yes') {
         $monthCount = ' (' . $monthCounts{"$currentYear$currentMonth"} . ")\n";
      } else {
        $monthCount = '';
      }
      if( get_option('collapsArchShowMonths')=='yes' ) {
      $text = sprintf('%s', $month[zeroise($currentMonth,2)]);

      $text = wptexturize($text);
      #$title_text = wp_specialchars($text,1);
      $title_text = strip_tags($text);

      if( get_option('collapsArchExpandMonths')=='yes' ) {
        $link = 'javascript:;';
        $onclick = 'onclick="hideNestedList(event); return false"';
        if( get_option('collapsArchExpandCurrentMonth')=='yes'
            && $currentYear == date('Y')
            && $currentMonth == date('n') ) {
									$monthRel = 'hide';
									$monthTitle= 'click to collapse';
          $postStyle = '';
									$ding = '&#9660;';
        } else {
									$monthRel = 'show';
									$monthTitle= 'click to expand';
									$ding = '&#9658;';
        }
      } else {
        $link = getMonth_link( $currentYear, $currentMonth );
        $onclick = '';
        $monthRel = '';
      }
      $the_link = "<span title='$monthTitle' class='collapsing $monthRel' $onclick>$ding&nbsp;</span>";
      $the_link .="<a href='$home/$archives$currentYear/$currentMonth' title='$title_text'>";
      $the_link .="$text</a>\n";

      echo "      <li class='collapsing'>".$the_link.$monthCount;

    }
    if( get_option('collapsArchShowMonths')=='yes' && get_option('collapsArchExpandMonths')=='yes' ) {
      echo "        <ul $postStyle id=\"collapsArchList-";
      echo "$currentYear-$currentMonth\">\n";
      $text = '';

      if( get_option('collapsArchShowPostNumber')=='yes' ) {
        $text .= '#'.$archPost->ID;
      }

      if( get_option('collapsArchShowPostTitle')=='yes' ) {
        $title_text = strip_tags(htmlentities($archPost->post_title, ENT_QUOTES));

        if( get_option('collapsArchPostTitleLength')> 0 && strlen( $title_text ) > get_option('collapsArchPostTitleLength') ) {
          $title_text = substr($title_text, 0, get_option('collapsArchPostTitleLength') );
          if( get_option('collapsArchShowPostTitleEllipsis')=='yes' ) {
            $title_text .= ' ...';
          }
        }

        $text .= ( $text == '' ? $title_text : ' - '.$title_text );
      }

      if( get_option('collapsArchShowPostDate')=='yes' ) {
        $theDate = mysql2date(get_option('collapsArchPostDateFormat'), $archPost->post_date );
        $text .= ( $text == '' ? $theDate : ', '.$theDate );
      }

      if( get_option('collapsArchShowCommentCount')=='yes' ) {
        $commcount = ' ('.get_comments_number($archPost->ID).')';
      }

      $link = get_permalink( $archPost->ID );
      echo "          <li class='collapsArchPost'><a href=\"$link\" title='$title_text'>$text</a>$commcount</li>\n";
      }
    } else {
      echo "          <li class='collapsArchPost'>";
      $text = '';

      if( get_option('collapsArchShowPostNumber')=='yes' ) {
        $text .= '#'.$archPost->ID;
      }

      if( get_option('collapsArchShowPostTitle')=='yes' ) {
        $title_text = strip_tags(htmlentities($archPost->post_title, ENT_QUOTES));
        $text .= ( $text == '' ? $archPost->post_title : ' - '.$archPost->post_title );
      }

      if( get_option('collapsArchShowPostDate')=='yes' ) {
        $theDate = mysql2date(get_option('collapsArchPostDateFormat'), $archPost->post_date );
        $text .= ( $text == '' ? $theDate : ', foo '.$theDate );
      }

      if( get_option('collapsArchShowCommentCount')=='yes' ) {
        $commcount = ' ('.get_comments_number($archPost->ID).')';
      }

      $link = get_permalink( $archPost->ID );
      echo "<a href='.$link.' title='$title_text'>$text</a>$commcount";
      echo "</li>\n";
    }
  }
} ?>
        </ul>
      </li> <!-- close month -->
    </ul>
  </li> <!-- close year -->
</ul>
