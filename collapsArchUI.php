<?php
/*
Collapsing Archives ver 0.7
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

	check_admin_referer();

	if( isset($_POST['infoUpdate']) ) {
		if( isset($_POST['expandCurrentYear']) ) {
			update_option( 'collapsArchExpandCurrentYear', 'yes' );
		}
		else {
			update_option( 'collapsArchExpandCurrentYear', 'no' );
		}

		if( isset($_POST['showYearCount']) ) {
			update_option( 'collapsArchShowYearCount', 'yes' );
		}
		else {
			update_option( 'collapsArchShowYearCount', 'no' );
		}

		if( isset($_POST['showMonths']) ) {
			update_option( 'collapsArchShowMonths', 'yes' );
		}
		else {
			update_option( 'collapsArchShowMonths', 'no' );
		}

		if( isset($_POST['expandCurrentMonth']) ) {
			update_option( 'collapsArchExpandCurrentMonth', 'yes' );
		}
		else {
			update_option( 'collapsArchExpandCurrentMonth', 'no' );
		}

		if( isset($_POST['showMonthCount']) ) {
			update_option( 'collapsArchShowMonthCount', 'yes' );
		}
		else {
			update_option( 'collapsArchShowMonthCount', 'no' );
		}

		if( isset($_POST['expandMonths']) ) {
			update_option( 'collapsArchExpandMonths', 'yes' );
		}
		else {
			update_option( 'collapsArchExpandMonths', 'no' );
		}

		if( isset($_POST['showCommentCount']) ) {
			update_option( 'collapsArchShowCommentCount', 'yes' );
		}
		else {
			update_option( 'collapsArchShowCommentCount', 'no' );
		}

		if( isset($_POST['showPages']) ) {
        update_option( 'collapsArchShowpages', 'yes' );
		}
		else {
        update_option( 'collapsArchShowpages', 'no' );
		}

		if( isset($_POST['showPostTitle']) ) {
			update_option( 'collapsArchShowPostTitle', 'yes' );
		}
		else {
			update_option( 'collapsArchShowPostTitle', 'no' );
		}

		$length= $_POST['postTitleLength'];
		update_option( 'collapsArchPosttitleLength', $length );

		if( isset($_POST['showPostTitleEllipsis']) ) {
			update_option( 'collapsArchShowPostTitleEllipsis', 'yes' );
		}
		else {
			update_option( 'collapsArchShowPostTitleEllipsis', 'no' );
		}

		if( isset($_POST['showPostDate']) ) {
			update_option( 'collapsArchShowPostDate', 'yes' );
		}
		else {
			update_option( 'collapsArchShowPostDate', 'no' );
		}

		$format= trim(stripslashes($_POST['postDateFormat']));
		update_option( 'collapsArchPostDateFormat', $format );

		if( isset($_POST['showPostNumber']) ) {
			update_option( 'collapsArchShowPostNumber', 'yes' );
		}
		else {
			update_option( 'collapsArchShowPostNumber', 'no' );
		}
    if($_POST['order'] == 'ASC') {
      update_option( 'collapsArchOrder', 'ASC' );
    } else {
      update_option( 'collapsArchOrder', 'DESC' );
    }
    if($_POST['archives'] == 'yes') {
      update_option( 'collapsArchLinkToArchives', 'yes' );
    } else {
      update_option( 'collapsArchLinkToArchives', 'no' );
    }
	}
?>
<div class=wrap>
 <form method="post">
  <h2>Collapsing Archives Options</h2>
  <fieldset name="Collapsing Archives Options">
   <legend><?php _e('Display Options:'); ?></legend>
   <ul style="list-style-type: none;">
    <li>
     <input type="radio" name="archives" <?php if(get_option('collapsArchLinkToArchives')=='no') echo 'checked'; ?> id="archivesNO" value='no'></input> <label for="orderDESC">Links based on index.php (default)</label>
     <input type="radio" name="archives" <?php if(get_option('collapsArchLinkToArchives')=='yes') echo 'checked'; ?> id="archivesYES" value='yes'></input> <label for="orderASC">Links based on archives.php</label>
    </li>
    <li>
     <input type="radio" name="order" <?php if(get_option('collapsArchOrder')=='DESC') echo 'checked'; ?> id="orderDESC" value='DESC'></input> <label for="orderDESC">Reverse Chronological Order (default)</label>
     <input type="radio" name="order" <?php if(get_option('collapsArchOrder')=='ASC') echo 'checked'; ?> id="orderASC" value='ASC'></input> <label for="orderASC">Chronological Order</label>
    </li>
    <li>
     <input type="checkbox" name="expandCurrentYear" <?php if (get_option('collapsArchExpandCurrentYear')=='yes')  echo 'checked'; ?> id="expandCurrentYear"></input> <label for="expandCurrentYear">Leave Current Year Expanded by Default</label>
    </li>
    <li>
     <input type="checkbox" name="showYearCount" <?php if (get_option('collapsArchYearCount')=='yes')  echo 'checked'; ?> id="showYearCount"></input> <label for="showYearCount">Show Post Count in Year Links</label>
    </li>
    <li>
     <input type="checkbox" name="showMonths" <?php if (get_option('collapsArchShowMonths')=='yes')  echo 'checked'; ?> id="showMonths"></input> <label for="showMonths">Show Month Link</label>
     <ul>
      <li>
       <input type="checkbox" name="showMonthCount" <?php if (get_option( 'collapsArchMonthCount' ) == 'yes') echo 'checked'; ?> id="showMonthCount"></input> <label for="showMonthCount">Show Post Count in Month Links</label>
      </li>
      <li>
       <input type="checkbox" name="expandMonths" <?php if(get_option('collapsArchExpandMonths')=='yes') echo 'checked'; ?> id="expandMonths"></input> <label for="expandMonths">Month Links should expand to show Posts</label>
       <ul>
        <li>
         <input type="checkbox" name="expandCurrentMonth" <?php if (get_option('collapsArchExpandCurrentMonth')=='yes') echo 'checked'; ?> id="expandCurrentMonth"></input> <label for="expandCurrentMonth">Leave Current Month Expanded by Default</label>
        </li>
       </ul>
      </li>
     </ul>
    </li>
   </ul>
   <b>Note</b>: Note, Posts are only shown if either the Month links are set to expand, or if Month links are disabled.
   <ul style="list-style-type: none;">
    <li>
     <input type="checkbox" name="showPages" <?php if(get_option('collapsArchShowPages')=='yes') echo 'checked'; ?> id="showPages"></input> <label for="showPages">Show Pages together with Posts</label>
    </li>
    <li>
     <input type="checkbox" name="showPostNumber" <?php if(get_option('collapsArchShowPostNumber')=='yes') echo 'checked'; ?> id="showPostNumber"></input> <label for="showPostNumber">Show Post Number in Post Links</label>
    </li>
    <li>
     <input type="checkbox" name="showPostTitle" <?php if(get_option('collapsArchShowPostTitle')=='yes') echo 'checked'; ?> id="showPostTitle"></input> <label for="showPostTitle">Show Post Title in Post Links</label>
     <ul>
      <li>
       <label for="postTitleLength">Limit Title Length <small>( 0 for full title )</small>:</label>
       <input type="text" name="postTitleLength" id="postTitleLength" value="<?php echo get_option('collapsArchPostTitleLength'); ?>"></input>
      </li>
      <li>
       <input type="checkbox" name="showPostTitleEllipsis" <?php if(get_option('collapsArchShowPostTitleEllipsis')=='yes') echo 'checked'; ?> id="showPostTitleEllipsis"></input> <label for="showPostTitleEllipsis">Show Ellipsis in Shortened Titles</label>
      </li>
     </ul>
    </li>
    <li>
     <input onchange='checkChecked();' type="checkbox" id="showPostDate" name="showPostDate" <?php if(get_option('collapsArchShowPostDate')=='yes') echo 'checked'; ?> id="showPostDate"></input> <label for="showPostDate">Show Post Date in Post Links</label>
     <ul>
      <li>
		 <input onfocus='checkChecked();'  name="postDateFormat" id="postDateFormat" value="<?php echo get_option('collapsArchPostDateFormat'); ?>"> Format (<a href="http://codex.wordpress.org/Formatting_Date_and_Time">Formatting Docs)</a></input>
		</li>
      <script type='text/javascript'>
        function checkChecked() {
          var dateFormatCheck = document.getElementById('showPostDate');
          var dateFormatInput = document.getElementById('postDateFormat');
          if (dateFormatCheck.checked==true) {
            dateFormatInput.readOnly=false; 
          } else {
            dateFormatInput.readOnly=true;
          }
        }
      </script>
     </ul>
    </li>
    <li>
     <input type="checkbox" name="showCommentCount" <?php if(get_option('collapsArchShowCommentCount')=='yes') echo 'checked'; ?> id="showCommentCount"></input> <label for="showCommentCount">Show Comment Count in Post Links</label>
    </li>
   </ul>
  </fieldset>
  <div class="submit">
   <input type="submit" name="infoUpdate" value="<?php _e('Update options', 'Collapsing Archives'); ?> &raquo;" />
  </div>
 </form>
</div>
