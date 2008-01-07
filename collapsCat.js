/*
Collapsing Categories 0.2
Copyright 2007 Robert Felty

This work is largely based on the Fancy Categories plugin by Andrew Rader
(http://voidsplat.org), which was also distributed under the GPLv2. See the
CHANGELOG file for more information.

This file is part of Collapsing Categories

		Collapsing Categories is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License as published by 
    the Free Software Foundation; either version 2 of the License, or (at your
    option) any later version.

    Collapsing Categories is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Collapsing Categories; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

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
    src.innerHTML="&#9658&nbsp;";
	}
	else {
		childList.style.display = "";
		src.setAttribute("class","collapsing hide");
    src.innerHTML="&#9660&nbsp;";
	}

	if( e.preventDefault ) {
		e.preventDefault();
	}

	return false;
}
