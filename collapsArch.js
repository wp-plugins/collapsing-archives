/*
Collapsing Archives ver 0.6.1
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

if( window.addEventListener ) {
	window.addEventListener( 'load', hideAll, false );
} else {
	window.attachEvent('onload',hideAll);
}

function hideAll() {
	items = document.getElementsByTagName("ul");
	for( i = 0; i < items.length; i++ ) {
		if( items[i].id.indexOf( "collapsArchList-" ) == 0 ) {
			year = (new Date()).getYear();
			month = (new Date()).getMonth() + 1;
			if( year < 2000 ) {
				year += 1900;
			}

			items[i].style.display = "none";

			if( collapsArchExpCurrYear && items[i].id == "collapsArchList-" + year ) {
				items[i].style.display = "";
			}

			if( collapsArchExpCurrMonth && items[i].id == "collapsArchList-" + year + "-" + month ) {
				items[i].style.display = "";
			}
		}
	}
}

function hideNestedList( e ) {
	if( e.target ) {
		src = e.target;
	}
	else {
		src = window.event.srcElement;
	}
  //alert(src.getAttribute('class'));

	srcList = src.parentNode;
	childList = null;

	for( i = 0; i < srcList.childNodes.length; i++ ) {
		if( srcList.childNodes[i].nodeName.toLowerCase() == 'ul' ) {
			childList = srcList.childNodes[i];
		}
	}

	if( src.getAttribute( "class" ) == 'collapsing hide' ) {
		childList.style.display = "none";
		src.setAttribute("class","collapsing show");
		src.setAttribute("title","click to expand");
    src.innerHTML="&#9658&nbsp;";
	}
	else {
		childList.style.display = "";
		src.setAttribute("class","collapsing hide");
		src.setAttribute("title","click to collapse");
    src.innerHTML="&#9660&nbsp;";
	}

	if( e.preventDefault ) {
		e.preventDefault();
	}

	return false;
}
