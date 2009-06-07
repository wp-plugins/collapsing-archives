    <?php
$style="#sidebar span.collapsArch {
        border:0;
        padding:0; 
        margin:0; 
        cursor:pointer;
} 

#sidebar span.monthCount, span.yearCount {text-decoration:none; color:#333}
#sidebar li.widget_collapspage h2 span.sym {float:right;padding:0 .5em}
#sidebar li.collapsArch a.self {font-weight:bold}
#sidebar ul.collapsArchList ul.collapsArchList:before {content:'';} 
#sidebar ul.collapsArchList li.collapsArch:before {content:'';} 
#sidebar ul.collapsArchList li.collapsArch {list-style-type:none}
#sidebar ul.collapsArchList li {
       margin:0 0 0 .8em;
       text-indent:-1em}
#sidebar ul.collapsArchList li.collapsArchPost:before {content: '\\\\00BB \\\\00A0' !important;} 
#sidebar ul.collapsArchList li.collapsArch .sym {
   font-size:1.2em;
   font-family:Monaco, 'Andale Mono', 'FreeMono', 'Courier new', 'Courier', monospace;
    padding-right:5px;}";

$default=$style;

$block="#sidebar li.collapsArch a {
            display:block;
            text-decoration:none;
            margin:0;
            padding:0;
            }
#sidebar li.collapsArch a:hover {
            background:#CCC;
            text-decoration:none;
          }
#sidebar span.collapsArch {
        border:0;
        padding:0; 
        margin:0; 
        cursor:pointer;
}

#sidebar li.widget_collapspage h2 span.sym {float:right;padding:0 .5em}
#sidebar li.collapsArch a.self {background:#CCC;
                       font-weight:bold}
#sidebar ul.collapsArchList ul.collapsArchList:before, 
  #sidebar ul.collapsArchList li.collapsArch:before,
  #sidebar ul.collapsArchList li.collapsArchPost:before {
        content:'';
  } 
#sidebar ul.collapsArchList li.collapsArch {list-style-type:none}
#sidebar ul.collapsArchList li.collapsItem {
      }
#sidebar ul.collapsArchList li.collapsArch .sym {
   font-size:1.2em;
   font-family:Monaco, 'Andale Mono', 'FreeMono', 'Courier new', 'Courier', monospace;
    float:left;
    padding-right:5px;
}
";

$noArrows="#sidebar span.collapsArch {
        border:0;
        padding:0; 
        margin:0; 
        cursor:pointer;
}
#sidebar span.monthCount, span.yearCount {text-decoration:none; color:#333}
#sidebar li.collapsArch a.self {font-weight:bold}
#sidebar ul.collapsArchList li {
       margin:0 0 0 .8em;
       text-indent:-1em}

#sidebar li.widget_collapspage h2 span.sym {float:right;padding:0 .5em}
#sidebar ul.collapsArchList ul.collapsArchList:before, 
  #sidebar ul.collapsArchList li.collapsArch:before, 
  #sidebar ul.collapsArchList li.collapsArchPost:before {
       content:'';
  } 
#sidebar ul.collapsArchList li.collapsArch {list-style-type:none}
#sidebar ul.collapsArchList li.collapsArch .sym {
   font-size:1.2em;
   font-family:Monaco, 'Andale Mono', 'FreeMono', 'Courier new', 'Courier', monospace;
    padding-right:5px;}";
$selected='default';
$custom=get_option('collapsArchStyle');
?>
