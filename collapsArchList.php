<?php
/*
Collapsing Archives version: 1.2.alpha

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

<?php
function list_archives($args='') {
global $wpdb, $month;
include('defaults.php');
$options=wp_parse_args($args, $defaults);
extract($options);
$idnum= '%i%'==$number ? $idnum=1 : $idnum=$number;
echo "<ul id='collapsArchList-$idnum'>\n";


$post_attrs = "post_date != '0000-00-00 00:00:00' AND post_status = 'publish'";


  if ($expand==1) {
    $expandSym='+';
    $collapseSym='—';
  } elseif ($expand==2) {
    $expandSym='[+]';
    $collapseSym='[—]';
  } elseif ($expand==3) {
    $expandSym="<img src='". get_settings('siteurl') .
         "/wp-content/plugins/collapsing-archives/" . 
         "img/expand.gif' alt='expand' />";
    $collapseSym="<img src='". get_settings('siteurl') .
         "/wp-content/plugins/collapsing-archives/" . 
         "img/collapse.gif' alt='collapse' />";
  } elseif ($expand==4) {
    $expandSym=htmlentities($customExpand);
    $collapseSym=htmlentities($customCollapse);
  } else {
    $expandSym='►';
    $collapseSym='▼';
  }
  if ($expand==3) {
    $expandSymJS='expandImg';
    $collapseSymJS='collapseImg';
  } else {
    $expandSymJS=$expandSym;
    $collapseSymJS=$collapseSym;
  }
	$inExclusionsCat = array();
	if ( !empty($inExcludeCat) && !empty($inExcludeCats) ) {
		$exterms = preg_split('/[,]+/',$inExcludeCats);
    if ($inExcludeCat=='include') {
      $in='IN';
    } else {
      $in='NOT IN';
    }
		if ( count($exterms) ) {
			foreach ( $exterms as $exterm ) {
				if (empty($inExclusionsCat))
					$inExclusionsCat = "'" . sanitize_title($exterm) . "'";
				else
					$inExclusionsCat .= ", '" . sanitize_title($exterm) . "' ";
			}
		}
	}
	if ( empty($inExclusionsCat) ) {
		$inExcludeCatQuery = "";
  } else {
    $inExcludeCatQuery ="AND $wpdb->terms.slug $in ($inExclusionsCat)";
  }
	$inExclusionsYear = array();
	if ( !empty($inExcludeYear) && !empty($inExcludeYears) ) {
		$exterms = preg_split('/[,]+/',$inExcludeYears);
    if ($inExcludeYear=='include') {
      $in='IN';
    } else {
      $in='NOT IN';
    }
		if ( count($exterms) ) {
			foreach ( $exterms as $exterm ) {
				if (empty($inExclusionsYear))
					$inExclusionsYear = "'" .$exterm . "'";
				else
					$inExclusionsYear .= ", '" . $exterm . "' ";
			}
		}
	}
	if ( empty($inExclusionsYear) ) {
		$inExcludeYearQuery = "";
  } else {
    $inExcludeYearQuery ="AND YEAR($wpdb->posts.post_date) $in ($inExclusionsYear)";
  }

  $isPage='';
  if (!$showPages) {
    $isPage="AND $wpdb->posts.post_type='post'";
  }
	if ($defaultExpand!='') {
		$autoExpand = preg_split('/,\s*/',$defaultExpand);
  } else {
	  $autoExpand = array();
  }
if( !$showPages ) {
  $post_attrs .= " AND post_type = 'post'";
}


$postquery= "SELECT $wpdb->terms.slug, $wpdb->posts.ID, $wpdb->posts.post_name, $wpdb->posts.post_title,
    $wpdb->posts.post_date, YEAR($wpdb->posts.post_date) AS 'year',
    MONTH($wpdb->posts.post_date) AS 'month' 
  FROM $wpdb->posts LEFT JOIN $wpdb->term_relationships ON $wpdb->posts.ID =
    $wpdb->term_relationships.object_id 
		LEFT JOIN $wpdb->term_taxonomy ON $wpdb->term_taxonomy.term_taxonomy_id =
																			$wpdb->term_relationships.term_taxonomy_id
		LEFT JOIN $wpdb->terms ON $wpdb->terms.term_id = 
		                          $wpdb->term_taxonomy.term_id 
  WHERE $post_attrs  $inExcludeYearQuery $inExcludeCatQuery 
  GROUP BY $wpdb->posts.ID 
  ORDER BY $wpdb->posts.post_date $archSortOrder";

/*
    $wpdb->term_relationships.term_taxonomy_id = $wpdb->terms.term_id 
$allPosts=wp_cache_get('collapsArchPosts');
if (false === $allPosts) {
  echo "not using cache";
  $allPosts=$wpdb->get_results($postquery);
  wp_cache_set('collapsArchPosts', $allPosts,$expire=200);
} else {
  echo "using cache";
}
*/
  $allPosts=$wpdb->get_results($postquery);

if ($debug==1) {
  echo "<pre style='display:none' >";
  printf ("MySQL server version: %s\n", mysql_get_server_info());
  echo "\ncollapsArch options:\n";
  print_r($options[$number]);
  echo "POST QUERY:\n $postquery\n";
  echo "\nPOST QUERY RESULTS\n";
  print_r($allPosts);
  echo "</pre>";
}

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
    $yearCounts{"$lastYear"}++;
    $monthCounts{"$lastYear$lastMonth"}++;
  }
  $newYear = false;
  $newMonth = false;
  $closePreviousYear = false;
  $monthCount=0;
  $i=0;
  foreach( $allPosts as $archPost ) {
    $ding = $expandSym;
    $i++;
    $yearRel = 'show';
    $monthRel = 'show';
    $yearTitle= __('click to expand', 'collapsArch');
    $monthTitle= __('click to expand', 'collapsArch');
    $postStyle = "style='display:none'";
    $monthStyle = "style='display:none'";
    /* rel = show means that it will be hidden, and clicking on the
     * triangle will expand it. rel = hide means that is will be shown, and
     * clicking on the triangle will collapse (hide) it 
     */
    if( $expandCurrentYear
        && $archPost->year == date('Y') ) {
      $ding = $collapseSym;
      $yearRel = "hide";
      $yearTitle= __('click to collapse', 'collapsArch');
      $monthStyle = '';
    }


    if( $currentYear != $archPost->year ) {
      $lastYear=$currentYear;
      $currentYear = $archPost->year;
      /* this should fix the "sparse year" problem
       * Thanks to Aishda
       */
      $currentMonth = 0;
      $newYear = true;
      if( $showYearCount) {
         $yearCount = ' (' . $yearCounts{"$currentYear"} . ")\n";
      }
      else {
        $yearCount = '';
      }
      
      if($i>=2 && $allPosts[$i-2]->year != $archPost->year ) {
				if( $showMonths ) {
          if( $expandMonths ) {
            echo "        </ul>\n      </li> <!-- close expanded month --> \n";
          } else {
            echo "      </li> <!-- close month --> \n";
          }
          echo "    </ul>\n  </li> <!-- end year -->\n";
        } else {
          echo "  </li> <!-- end year -->\n";
        }
      }
      $home = get_settings('home');
      if( $showMonths ) {
				echo "  <li class='collapsArch'><span title='$yearTitle' " .
				    "class='collapsArch $yearRel' " .
            "onclick='expandCollapse(event" .
            ", \"$expandSymJS\", \"$collapseSymJS\", $animate," .
            "\"collapsArch\"); return false' ><span class='sym'>$ding</span>";
			} else {
			  echo "  <li class='collapsArchPost'>\n";
			}
      if ($linkToArch) {
        echo  "</span>";
        echo "<a href='".get_year_link($archPost->year). "'>$currentYear</a>$yearCount\n";
      } else {
        echo "$currentYear$yearCount\n";
        echo "</span>";
      }
      if( $showMonths ) {
        echo "    <ul $monthStyle id='collapsArchList-$currentYear-$idnum'>\n";
      }
      $newYear = false;
    }

    if($currentMonth != $archPost->month) {
      $currentMonth = $archPost->month;
      $newMonth = true;
      if($newYear == false) { #close off last month
        $newYear=true; 
      } else {
				if( $showMonths ) {
          if( $expandMonths ) {
            echo "        </ul>\n      </li> <!-- close expanded month --> \n";
          } else {
            echo "      </li> <!-- close month --> \n";
          }
        }
      }

      if( $showMonthCount) {
         $monthCount = ' (' . $monthCounts{"$currentYear$currentMonth"} . ")\n";
      } else {
        $monthCount = '';
      }
      if( $showMonths ) {
				$text = sprintf('%s', $month[zeroise($currentMonth,2)]);

				$text = wptexturize($text);
				$title_text = wp_specialchars($text,1);

				if ($expandMonths ) {
					$link = 'javascript:;';
					$onclick = "onclick='expandCollapse(event" . 
              ", \"$expandSymJS\", \"$collapseSymJS\", $animate, " .
					    "\"collapsArch\"); return false'";
					$monthCollapse = 'collapsArch';
					if( $expandCurrentMonth
							&& $currentYear == date('Y')
							&& $currentMonth == date('n') ) {
										$monthRel = 'hide';
										$monthTitle= 'click to collapse';
                    $postStyle = '';
										$ding = $collapseSym;
					} else {
										$monthRel = 'show';
                    $monthTitle= __('click to collapse', 'collapsArch');
										$ding = $expandSym;
					}
					$the_link = "<span title='$monthTitle' " .
					    "class='$monthCollapse $monthRel' $onclick>" .
							"<span class='sym'>$ding</span>";
          if ($linkToArch) {
            $the_link.= "</span>";
            $the_link .="<a href='".get_month_link($currentYear, $currentMonth).
						    "' title='$title_text'>";
            $the_link .="$text</a>\n";
          } else {
            $the_link .="$text\n";
            $the_link.="</span>";
          }
				} else {
					$link = get_month_link( $currentYear, $currentMonth );
					$onclick = '';
					$monthRel = '';
					$monthTitle = '';
					$monthCollapse = 'collapsArchMonth';
					$the_link ="<a href='".get_month_link($currentYear, $currentMonth).
					    "' title='$title_text'>";
					$the_link .="$text</a>\n";
				}

				echo "      <li class='$monthCollapse'>".$the_link.$monthCount;

			}
			if ($showMonths && $expandMonths=='yes' ) {
				echo "        <ul $postStyle id=\"collapsArchList-";
				echo "$currentYear-$currentMonth-$idnum\">\n";
				$text = '';
      }
		} else {

			if( $showMonths && $expandMonths=='yes' ) {
				$text = '';
			}
		}
		if( $showPostNumber ) {
			$text .= '#'.$archPost->ID;
		}

		if ($showPostTitle  && $expandMonths=='yes') {

			$title_text = htmlspecialchars(strip_tags(__($archPost->post_title)), ENT_QUOTES);
			if(strlen(trim($title_text))==0) {
				$title_text = $noTitle;
			}
			$tmp_text = '';
			if ($postTitleLength>0 && strlen($title_text)>$postTitleLength ) {
				$tmp_text = substr($title_text, 0, $postTitleLength );
				$tmp_text .= ' &hellip;';
			}

			$text .= ( $tmp_text == '' ? $title_text : $tmp_text );
      if ($showPostDate ) {
        $theDate = mysql2date($postDateFormat, $archPost->post_date );
        $text .= ( $text == '' ? $theDate : " $theDate" );
      }

      if ($showCommentCount ) {
        $commcount = ' ('.get_comments_number($archPost->ID).')';
      }

      $link = get_permalink($archPost);
      echo "          <li class='collapsArchPost'><a href='$link' " .
          "title='$title_text'>$text</a>$commcount</li>\n";
    }
	}
  if ($showMonths ) {
    if ($expandMonths) {
      echo "        </ul>\n";
    }
    echo "  </li> <!-- close month -->
          </ul>";
  }
} 
   echo "  </li> <!-- close year -->
  </ul> <!-- end of collapsing archives -->";
  return;
}
		echo "<script type=\"text/javascript\">\n";
		echo "// <![CDATA[\n";
		echo '/* These variables are part of the Collapsing Archives Plugin
		       * version: 1.2.alpha
					 * revision: $Id: collapsArch.php 103059 2009-03-17 20:12:01Z robfelty $
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
 ?>
