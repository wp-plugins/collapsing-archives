<?php
/*
Collapsing Archives version: 0.9.5alphaalpha
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
  if (isset($_POST['reset'])) {
    delete_option('collapsArchOptions');   
  } else {
    include('updateOptions.php');
  }
}
$theOptions=get_option('collapsArchOptions');
if (empty($theOptions)) {
  $number = -1;
} else {
   echo "
  <h2>Collapsing Archives Options</h2>
<p>If you wish to use the collapsing archives plugin as a widget, you should set the options in the widget page. If you would like to use it manually (that is, you modify your theme), then click below to delete the current widget options</p>
<form method='post'>
   <input type='hidden' name='reset' value='true' />
   <input type='submit' name='infoUpdate' value='reset options' />
</form>
";
  return;
  $numbers=array_keys($theOptions);
  $number= $numbers[0];
}
include('processOptions.php');
?>
<div class=wrap>
 <form method="post">
  <h2>Collapsing Archives Options</h2>
  <fieldset name="Collapsing Archives Options">
   <legend><?php _e('Display Options:'); ?></legend>
   <ul style="list-style-type: none;">
   <?php
    echo '<p style="text-align:left;"><label for="collapsArch-title-'.$number.'">' . __('Title:') . '<input class="widefat" style="width: 200px;" id="collapsArch-title-'.$number.'" name="collapsArch['.$number.'][title]" type="text" value="'.$title.'" /></label></p>';
   include('options.txt'); ?>
   </ul>
  </fieldset>
  <div class="submit">
   <input type="submit" name="infoUpdate" value="<?php _e('Update options', 'Collapsing Archives'); ?> &raquo;" />
  </div>
 </form>
</div>
