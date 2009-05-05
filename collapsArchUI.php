<?php
/*
Collapsing Archives version: 1.1.5
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

$options=get_option('collapsArchOptions');
$widgetOn=0;
$number='%i%';
if (empty($options)) {
  $number = '-1';
} elseif (!isset($options['%i%']['title']) || 
    count($options) > 1) {
  $widgetOn=1; 
}

if( isset($_POST['resetOptions']) ) {
  if (isset($_POST['reset'])) {
    delete_option('collapsArchOptions');   
		$widgetOn=0;
    $number = '-1';
  }
} elseif (isset($_POST['infoUpdate'])) {
  $style=$_POST['collapsArchStyle'];
  update_option('collapsArchStyle', $style);
  if ($widgetOn==0) {
    include('updateOptions.php');
  }
}
/*
echo "<pre>\n";
print_r($options);
echo "</pre>\n";
*/
include('processOptions.php');
?>
<div class=wrap>
 <form method="post">
  <h2><? _e('Collapsing Archives Options', 'collapsArch'); ?></h2>
  <fieldset name="Collapsing Archives Options">
   <legend><?php _e('Display Options', 'collapsArch'); ?>:</legend>
   <ul style="list-style-type: none;">
   <?php
   if ($widgetOn==1) {
     echo "
    <div style='width:60em; background:#FFF; color:#444;border: 1px solid #444;padding:0 1em'><p>" . __('If you wish to use the collapsing categories plugin as a widget, you should set the options in the widget page (except for custom styling, which is set here). If you would like to use it manually (that is, you modify your theme), then click below to delete the current widget options.', 'collapsArch') . "</p>
    <form method='post'>
    <p>
       <input type='hidden' name='reset' value='true' />
       <input type='submit' name='resetOptions' value='".__('reset options', 'collapsArch')."' />
       </p>
    </form>
    </div>
    ";
    } else {
     echo '<p style="text-align:left;"><label for="collapsArch-title-'.$number.'">' . __('Title', 'collapsArch') . ': <input class="widefat" style="width: 200px;" id="collapsArch-title-'.$number.'" name="collapsArch['.$number.'][title]" type="text" value="'.$title.'" /></label></p>';
     include('options.txt'); 
   }
   ?>
    <p>
  <input type='hidden' id='collapsArchOrigStyle' value="<?php echo
stripslashes(get_option('collapsArchOrigStyle')) ?>" />
<label for="collapsArchStyle"><? _e('Style info', 'collapsArch'); ?>:</label>
   <input type='button' value='<? _e('restore original style', 'collapsArch'); ?>' onclick='restoreStyle();' /><br />
   <textarea cols='78' rows='10' id="collapsArchStyle" name="collapsArchStyle">
    <?php echo stripslashes(get_option('collapsArchStyle')) ?>
   </textarea>
    </p>
<script type='text/javascript'>
function restoreStyle() {
  var defaultStyle = document.getElementById('collapsArchOrigStyle').value;
  var catStyle = document.getElementById('collapsArchStyle');
  catStyle.value=defaultStyle;
}
</script>
   </ul>
  </fieldset>
  <div class="submit">
   <input type="submit" name="infoUpdate" value="<?php _e('Update options', 'collapsArch'); ?> &raquo;" />
  </div>
 </form>
</div>
