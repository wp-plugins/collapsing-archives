<?php
/*
Collapsing Archives version: 0.9alpha

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

  $options=get_option('collapsArchOptions');
  print_r($options);
  extract($options[$number]);
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
  } else {
    $expandSym='►';
    $collapseSym='▼';
  }
	$inExclusions = array();
	if ( !empty($inExclude) && !empty($inExcludeCats) ) {
		$exterms = preg_split('/[,]+/',$inExcludeCats);
    if ($inExclude=='include') {
      $in='IN';
    } else {
      $in='NOT IN';
    }
		if ( count($exterms) ) {
			foreach ( $exterms as $exterm ) {
				if (empty($inExclusions))
					$inExclusions = "'" . sanitize_title($exterm) . "'";
				else
					$inExclusions .= ", '" . sanitize_title($exterm) . "' ";
			}
		}
	}
	if ( empty($inExclusions) ) {
		$inExcludeQuery = "''";
  } else {
    $inExcludeQuery ="AND $wpdb->terms.name $in ($inExclusions)";
  }

  $isPage='';
  if ($showPages=='no') {
    $isPage="AND $wpdb->posts.post_type='post'";
  }
  if ($postSort!='') {
    if ($postSort=='postDate') {
      $postSortColumn="ORDER BY $wpdb->posts.post_date";
    } elseif ($postSort=='postId') {
      $postSortColumn="ORDER BY $wpdb->posts.id";
    } elseif ($postSort=='postTitle') {
      $postSortColumn="ORDER BY $wpdb->posts.post_title";
    } elseif ($postSort=='postComment') {
      $postSortColumn="ORDER BY $wpdb->posts.comment_count";
    }
  } 
	if ($defaultExpand!='') {
		$autoExpand = preg_split('/,\s*/',$defaultExpand);
  } else {
	  $autoExpand = array();
  }
if( $collapsArchShowPages=='no' ) {
  $post_attrs .= " AND post_type = 'post'";
}
if ($collapsArchOrder=='ASC') {
  $order='ASC';
} else {
  $order='DESC';
}
if ($collapsArchLinkToArchives=='archives') {
  $archives='archives.php/';
} elseif ($collapsArchLinkToArchives=='index') {
  $archives='index.php/';
} elseif ($collapsArchLinkToArchives=='root') {
  $archives='';
}
  $exclude=$collapsArchExclude;
	$exclusions = '';
	if ( !empty($exclude) ) {
		$exterms = preg_split('/[,]+/',$exclude);
		if ( count($exterms) ) {
			foreach ( $exterms as $exterm ) {
				if (empty($exclusions))
					$exclusions = "'" . sanitize_title($exterm) . "'";
				else
					$exclusions .= ", '" . sanitize_title($exterm) . "' ";
			}
		}
	}
	if ( empty($exclusions) ) {
		$exclusions = "''";
  }
  $include=$collapsArchInclude;
	$inclusions = '';
	if ( !empty($include) ) {
		$interms = preg_split('/[,]+/',$include);
		if ( count($interms) ) {
			foreach ( $interms as $interm ) {
				if (empty($inclusions))
					$inclusions = "'" . $interm . "'";
				else
					$inclusions .= ", '" . $interm . "' ";
			}
		}
	}
	if ( empty($inclusions) ) {
		$include = "''";
  } else {
    $include ="AND YEAR($wpdb->posts.post_date) IN ($inclusions)";
  }

$collapsArchShowMonths='yes';
$collapsArchExpandMonths='yes';
$collapsArchShowPostTitle='yes';

$postquery= "SELECT $wpdb->posts.ID, $wpdb->posts.post_title,
          $wpdb->posts.post_date, YEAR($wpdb->posts.post_date) AS 'year',
    MONTH($wpdb->posts.post_date) AS 'month' 
FROM $wpdb->posts LEFT JOIN $wpdb->term_relationships ON $wpdb->posts.ID = $wpdb->term_relationships.object_id LEFT JOIN $wpdb->terms ON $wpdb->terms.slug NOT IN ($exclusions) WHERE $wpdb->term_relationships.term_taxonomy_id = $wpdb->terms.term_id AND $post_attrs $include GROUP BY $wpdb->posts.ID ORDER BY $wpdb->posts.post_date $order";

$allPosts=$wpdb->get_results($postquery);
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
    $ding = $expandSym;
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
    if( $collapsArchExpandCurrentYear=='yes'
        && $archPost->year == date('Y') ) {
      $ding = $collapseSym;
      $yearRel = "hide";
      $yearTitle= 'click to collapse';
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
      if( $collapsArchShowYearCount=='yes') {
         $yearCount = ' (' . $yearCounts{"$currentYear"} . ")\n";
      }
      else {
        $yearCount = '';
      }
      
      if($i>=2 && $allPosts[$i-2]->year != $archPost->year ) {
				if( $collapsArchShowMonths=='yes' ) {
          if( $collapsArchExpandMonths=='yes' ) {
            echo "        </ul>\n      </li> <!-- close expanded month --> \n";
          } else {
            echo "      </li> <!-- close month --> \n";
          }
          echo "    </ul>\n  </li> <!-- end year -->\n";
        } else {
          echo "  </li> <!-- end year -->\n";
        }
      }
      echo "  <li class='collapsArch'><span title='$yearTitle' class='collapsArch $yearRel' onclick='expandArch(event, $expand); return false' >$ding</span>&nbsp;";
      $home = get_settings('home');
      echo "<a href='".get_year_link($archPost->year). "'>$currentYear</a>$yearCount\n";
      if( $collapsArchShowMonths=='yes' ) {
        echo "    <ul $monthStyle id='collapsArchList-$currentYear'>\n";
      }
      $newYear = false;
    }

    if($currentMonth != $archPost->month) {
      $currentMonth = $archPost->month;
      $newMonth = true;
      if($newYear == false) { #close off last month
        $newYear=true; 
      } else {
				if( $collapsArchShowMonths=='yes' ) {
          if( $collapsArchExpandMonths=='yes' ) {
            echo "        </ul>\n      </li> <!-- close expanded month --> \n";
          } else {
            echo "      </li> <!-- close month --> \n";
          }
        }
      }

      if( $collapsArchShowMonthCount=='yes') {
         $monthCount = ' (' . $monthCounts{"$currentYear$currentMonth"} . ")\n";
      } else {
        $monthCount = '';
      }
      if( $collapsArchShowMonths=='yes' ) {
				$text = sprintf('%s', $month[zeroise($currentMonth,2)]);

				$text = wptexturize($text);
				$title_text = wp_specialchars($text,1);
				#$title_text = wptexturize($text);
				#$title_text = strip_tags($text);

				if( $collapsArchExpandMonths=='yes' ) {
					$link = 'javascript:;';
					$onclick = 'onclick="expandArch(event, $expand); return false"';
					$monthCollapse = 'collapsArch';
					if( $collapsArchExpandCurrentMonth=='yes'
							&& $currentYear == date('Y')
							&& $currentMonth == date('n') ) {
										$monthRel = 'hide';
										$monthTitle= 'click to collapse';
						$postStyle = '';
										$ding = $collapseSym;
					} else {
										$monthRel = 'show';
										$monthTitle= 'click to expand';
										$ding = $expandSym;
					}
					$the_link = "<span title='$monthTitle' class='$monthCollapse $monthRel' $onclick>$ding</span>&nbsp;";
					$the_link .="<a href='".get_month_link($currentYear, $currentMonth)."' title='$title_text'>";
					$the_link .="$text</a>\n";
				} else {
					$link = get_month_link( $currentYear, $currentMonth );
					$onclick = '';
					$monthRel = '';
					$monthTitle = '';
					$monthCollapse = '';
					//$the_link ="<a href='$home/$archives$currentYear/$currentMonth' title='$title_text'>";
					$the_link ="<a href='".get_month_link($currentYear, $currentMonth)."' title='$title_text'>";
					$the_link .="$text</a>\n";
				}

				echo "      <li class='$monthCollapse'>".$the_link.$monthCount;

			}
			if( $collapsArchShowMonths=='yes' && $collapsArchExpandMonths=='yes' ) {
				echo "        <ul $postStyle id=\"collapsArchList-";
				echo "$currentYear-$currentMonth\">\n";
				$text = '';

				if( $collapsArchShowPostNumber=='yes' ) {
					$text .= '#'.$archPost->ID;
				}

				if( $collapsArchShowPostTitle=='yes' ) {
						$title_text = htmlspecialchars(strip_tags($archPost->post_title), ENT_QUOTES);
					#$title_text = strip_tags($archPost->post_title);

					if( $collapsArchPostTitleLength> 0 && strlen( $title_text ) > $collapsArchPostTitleLength ) {
						$title_text = substr($title_text, 0, $collapsArchPostTitleLength );
						if( $collapsArchShowPostTitleEllipsis=='yes' ) {
							$title_text .= ' ...';
						}
					}

					$text .= ( $text == '' ? $title_text : ' - '.$title_text );
				}

				if( $collapsArchShowPostDate=='yes' ) {
					$theDate = mysql2date($collapsArchPostDateFormat, $archPost->post_date );
					$text .= ( $text == '' ? $theDate : ', '.$theDate );
				}

				if( $collapsArchShowCommentCount=='yes' ) {
					$commcount = ' ('.get_comments_number($archPost->ID).')';
				}

				$link = get_permalink( $archPost->ID );
				echo "          <li class='collapsArchPost'><a href='$link' title='$title_text'>$text</a>$commcount</li>\n";
				}
			} else {

				if( $collapsArchShowMonths=='yes' && $collapsArchExpandMonths=='yes' ) {
					$text = '';

					if( $collapsArchShowPostNumber=='yes' ) {
						$text .= '#'.$archPost->ID;
					}

					if( $collapsArchShowPostTitle=='yes' ) {
						#$title_text = strip_tags($archPost->post_title);

						$title_text = htmlspecialchars(strip_tags($archPost->post_title), ENT_QUOTES);
						#$title_text = strip_tags(htmlentities($archPost->post_title, ENT_QUOTES));
						if( $collapsArchPostTitleLength> 0 && strlen( $title_text ) > $collapsArchPostTitleLength ) {
							$title_text = substr($title_text, 0, $collapsArchPostTitleLength );
							if( $collapsArchShowPostTitleEllipsis=='yes' ) {
								$title_text .= ' ...';
							}
						}

						$text .= ( $text == '' ? $title_text : ' - '.$title_text );
					}

					if( $collapsArchShowPostDate=='yes' ) {
						$theDate = mysql2date($collapsArchPostDateFormat, $archPost->post_date );
						$text .= ( $text == '' ? $theDate : ', '.$theDate );
					}

					if( $collapsArchShowCommentCount=='yes' ) {
						$commcount = ' ('.get_comments_number($archPost->ID).')';
					}

					$link = get_permalink( $archPost->ID );
					echo "          <li class='collapsArchPost'><a href='$link' title='$title_text'>$text</a>$commcount</li>\n";
		/*
					}
					echo "          <li class='collapsArchPost'>";
					$text = '';

					if( $collapsArchShowPostNumber=='yes' ) {
						$text .= '#'.$archPost->ID;
					}

					if( $collapsArchShowPostTitle=='yes' ) {
						$title_text = strip_tags(htmlentities($archPost->post_title, ENT_QUOTES));
						$text .= ( $text == '' ? $archPost->post_title : ' - '.$archPost->post_title );
					}

					if( $collapsArchShowPostDate=='yes' ) {
						$theDate = mysql2date($collapsArchPostDateFormat, $archPost->post_date );
						$text .= ( $text == '' ? $theDate : ', foo '.$theDate );
					}

					if( $collapsArchShowCommentCount=='yes' ) {
						$commcount = ' ('.get_comments_number($archPost->ID).')';
					}

					$link = get_permalink( $archPost->ID );
					echo "<a href='$link' title='$title_text'>$text</a>$commcount";
					echo "</li>\n";
		*/
			}
    }
  }
  if( $collapsArchShowMonths=='yes' && $collapsArchExpandMonths=='yes' ) {
    echo "        </ul>\n
      </li> <!-- close month -->
    </ul>";
  }
} ?>
  </li> <!-- close year -->
</ul>
