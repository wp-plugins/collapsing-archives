/*
Collapsing Archives version: 1.1.3
Copyright 2007 Robert Felty

This work is largely based on the Fancy Archives plugin by Andrew Rader
(http://voidsplat.org), which was also distributed under the GPLv2. See the
CHANGELOG file for more information.

This file is part of Collapsing Archives

		Collapsing Archives is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License as published by 
    the Free Software Foundation; either version 2 of the License, or (at your
    option) any later version.

    Collapsing Archives is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Collapsing Archives; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function autoExpandArch() {
  var cookies = document.cookie.split(';');
  for (var cookieIndex=0; cookieIndex<cookies.length; cookieIndex++) {
    var cookieparts= cookies[cookieIndex].split('=');
    var cookiename=cookieparts[0].trim();
    var cookievalue=cookieparts[1].trim();
    if (cookiename.match(/collapsArchList-[0-9]+/)) {
      var expand= document.getElementById(cookiename);
      var thisli = expand.parentNode;
      for (var childI=0; childI< thisli.childNodes.length; childI++) {
        if (thisli.childNodes[childI].nodeName.toLowerCase() == 'span') {
          theSpan=thisli.childNodes[childI];
          if (theSpan.className.match(/^collapsArch/)) {
            if ((theSpan.className == 'collapsArch show' && cookievalue ==1) ||
                (theSpan.className == 'collapsArch hide' && cookievalue ==0)) {
              var theOnclick=theSpan.onclick+"";
              var matches=theOnclick.match(/.*\(event, ?([0-9]).*\)/);
              var expand=matches[1];
              expandArch(theSpan,expand,0);
            }
          }
        } 
      }
    }
  }
}
addLoadEvent(function() {
  autoExpandArch();
});
