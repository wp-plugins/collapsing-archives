<?php
/*
Collapsing Archives ver 0.6

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
if (get_option('collapsArchLinkToArchives')=='yes') {
  $archives='archives.php/';
} else {
  $archives='';
}


$archPosts = $wpdb->get_results("SELECT DISTINCT YEAR(post_date) AS `year`,
        MONTH(post_date) AS `month`, count(ID) as posts FROM $wpdb->posts
        WHERE post_date < '$now' AND $post_attrs GROUP BY YEAR(post_date),
        MONTH(post_date) ORDER BY post_date $order");

if( $archPosts ) {
    $currentYear = -1;
    $currentMonth = -1;
    $newYear = false;
    $newMonth = false;
    $closePreviousYear = false;

    $i=0;
    foreach( $archPosts as $archPost ) {
    $ding = '&#9658;';
        $i++;
        $yearRel = 'show';
        $monthRel = 'show';
				$postStyle = "style='display:none'";
				$monthStyle = "style='display:none'";
				/* rel = show means that it will be hidden, and clicking on the
				 * triangle will expand it. rel = hide means that is will be shown, and
				 * clicking on the triangle will collapse (hide) it 
				 */
        if( get_option('collapsArchExpandCurrentYear')=='yes'
                && $archPost->year == date('Y') ) {
            //if ($i==1) {
              $ding = '&#9660;';
           // } else {
        //      $ding = '&#9658;';
            //}
            $yearRel = "hide";
            $monthStyle = '';
        }

        if( $currentYear != $archPost->year
                || $currentMonth != $archPost->month ) {
            $archMonthsposts = $wpdb->get_results("SELECT ID, post_title,
                    post_date FROM $wpdb->posts WHERE YEAR(post_date) = '
                    $archPost->year' AND MONTH(post_date) = '
                    $archPost->month' AND $post_attrs ORDER BY post_date 
                    DESC");
            $currentMonth = $archPost->month;
            $newMonth = true;
        }

        if( $currentYear != $archPost->year ) {
            $archYearposts = $wpdb->get_results("SELECT ID, post_title, 
                    post_date FROM $wpdb->posts WHERE YEAR(post_date) = '
                    $archPost->year' AND $post_attrs");
            $currentYear = $archPost->year;
            $newYear = true;
        }

        if( get_option('collapsArchShowYearCount')=='yes' && $archYearposts ) {
            $yearCount = ' ('.count( $archYearposts ).')';
        }
        else {
            $yearCount = '';
        }

        if( get_option('collapsArchShowMonthCount')=='yes' && $archMonthsposts ) {
            $monthCount = ' ('.count($archMonthsposts ).")\n";
        }
        else {
            $monthCount = '';
        }

        if( $newYear ) {
            if( $closePreviousYear ) {
                echo "</ul></li>";
            }
            $closePreviousYear = true;
            
            echo "<li><span class='collapsing $yearRel' onclick='hideNestedList(event); return false' >$ding&nbsp;</span>";
            $home = get_settings('home');
            echo "<a href='$home/$archives$currentYear'>$currentYear</a>$yearCount\n";
            echo "<ul $monthStyle id='collapsArchList-$currentYear'>\n";
            $newYear = false;
        }
        if( get_option('collapsArchShowMonths')=='yes' ) {
            $text = sprintf('%s', $month[zeroise($currentMonth,2)]);

            $text = wptexturize($text);
            $title_text = wp_specialchars($text,1);

            if( get_option('collapsArchExpandMonths')=='yes' ) {
                $link = 'javascript:;';
                $onclick = 'onclick="hideNestedList(event); return false"';
                if( get_option('collapsArchExpandCurrentMonth')=='yes'
                        && $currentYear == date('Y')
                        && $currentMonth == date('n') ) {
									$monthRel = 'hide';
                  $postStyle = '';
									$ding = '&#9660; foo';
                } else {
									$monthRel = 'show';
									$ding = '&#9658;';
                }
            }
            else {
                $link = getMonth_link( $currentYear, $currentMonth );
                $onclick = '';
                $monthRel = '';
            }
            $the_link = "<span class='collapsing $monthRel' $onclick>$ding&nbsp;</span>";
            $the_link .="<a href='$home/$archives$currentYear/$currentMonth' title='$title_text'>";
            $the_link .="$text</a>";

            echo "<li>".$the_link.$monthCount;

            if( get_option('collapsArchShowMonths')=='yes' && get_option('collapsArchExpandMonths')=='yes' ) {
                echo "<ul $postStyle id=\"collapsArchList-";
                echo "$currentYear-$currentMonth\">\n";

                foreach( $archMonthsposts as $archPost ) {
                    $text = '';

                    if( get_option('collapsArchShowPostNumber')=='yes' ) {
                        $text .= '#'.$archPost->ID;
                    }

                    if( get_option('collapsArchShowPostTitle')=='yes' ) {
                        $title_text = $archPost->post_title;

                        if( get_option('collapsArchPostTitleLength')> 0 && strlen( $title_text ) > get_option('collapsArchPostTitleLength') ) {
                            $title_text = substr( $title_text, 0, get_option('collapsArchPostTitleLength') );
                            if( get_option('collapsArchShowPostTitleEllipsis')=='yes' ) {
                                $title_text .= ' ...';
                            }
                        }

                        $text .= ( $text == '' ? $title_text : ' - '.$title_text );
                    }

                    if( get_option('collapsArchShowPostDate')=='yes' ) {
                        $theDate = mysql2date( get_option('collapsArchPostDateFormat'), $archPost->post_date );
                        $text .= ( $text == '' ? $theDate : ', '.$theDate );
                    }

                    if( get_option('collapsArchShowCommentCount')=='yes' ) {
                        $commcount = ' ('.getCommentsNumber($archPost->ID).')';
                    }

                    $link = get_permalink( $archPost->ID );

                    echo "<li><a href=\"$link\" title=\"$archPost->post_title\">$text</a>$commcount</li>\n";
                }
                echo "</ul>\n";
            }
            echo "</li>\n";
        }
        else {
            foreach( $archMonthsposts as $archPost ) {
                echo '<li>';
                $text = '';

                if( get_option('collapsArchShowPostNumber')=='yes' ) {
                    $text .= '#'.$archPost->ID;
                }

                if( get_option('collapsArchShowPostTitle')=='yes' ) {
                    $text .= ( $text == '' ? $archPost->post_title : ' - '.$archPost->post_title );
                }

                if( get_option('collapsArchShowPostDate')=='yes' ) {
                    $theDate = mysql2date( get_option('collapsArchPostDateFormat')=='yes', $archPost->post_date );
                    $text .= ( $text == '' ? $theDate : ', '.$theDate );
                }

                if( get_option('collapsArchShowCommentCount')=='yes' ) {
                    $commcount = ' ('.getCommentsNumber($archPost->ID).')';
                }

                $link = get_permalink( $archPost->ID );
                echo '<a href="'.$link.'">'.$text.'</a>'.$commcount;
                echo '</li>';
            }
        }
    }
    echo "</ul></li>\n";
} ?>
</ul>
