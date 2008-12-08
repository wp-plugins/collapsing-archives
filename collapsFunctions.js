String.prototype.trim = function() {
  return this.replace(/^\s+|\s+$/g,"");
}

function createCookie(name,value,days) {
  if (days) {
    var date = new Date();
    date.setTime(date.getTime()+(days*24*60*60*1000));
    var expires = "; expires="+date.toGMTString();
  } else {
    var expires = "";
  }
  document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  for(var i=0;i < ca.length;i++) {
    var c = ca[i];
    while (c.charAt(0)==' ') {
      c = c.substring(1,c.length);
    }
    if (c.indexOf(nameEQ) == 0) {
      return c.substring(nameEQ.length,c.length);
    }
  }
  return null;
}

function eraseCookie(name) {
  createCookie(name,"",-1);
}
function addLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      if (oldonload) {
        oldonload();
      }
      func();
    }
  }
}
function autoExpandCollapse(collapsClass) {
  alert(collapsClass);
  var cookies = document.cookie.split(';');
  var cookiePattern = new RegExp(collapsClass+'(-[0-9]+|List-[0-9]+-[0-9]+)');
  var classPattern = new RegExp('^' + collapsClass);
  var hide = collapsClass + ' ' + 'hide'
  var show = collapsClass + ' ' + 'show'
  for (var cookieIndex=0; cookieIndex<cookies.length; cookieIndex++) {
    var cookieparts= cookies[cookieIndex].split('=');
    var cookiename=cookieparts[0].trim();
    var cookievalue=cookieparts[1].trim();
    if (cookiename.match(cookiePattern)) {
      var expand= document.getElementById(cookiename);
      if (expand) {
        var thisli = expand.parentNode;
        for (var childI=0; childI< thisli.childNodes.length; childI++) {
          if (thisli.childNodes[childI].nodeName.toLowerCase() == 'span') {
            theSpan=thisli.childNodes[childI];
            if (theSpan.className.match(classPattern)) {
              if ((theSpan.className == show && cookievalue ==1) ||
                  (theSpan.className == hide && cookievalue ==0)) {
                var theOnclick=theSpan.onclick+"";
                var matches=theOnclick.match(/.*\(event, ?([0-9]).*\)/);
                var expand=matches[1];
                expandCollapse(theSpan,expand,0,collapsClass);
              }
            }
          }
        } 
      }
    }
  }
}

function expandCollapse( e, expand,animate, collapsClass ) {
  var classPattern= new RegExp('^' + collapsClass);
  if (expand==1) {
    expand='+';
    collapse='—';
  } else if (expand==2) {
    expand='[+]';
    collapse='[—]';
  } else if (expand==3) {
    expand=expandSym;
    collapse=collapseSym;
  } else {
    expand='►';
    collapse='▼';
  }
  if( e.target ) {
    src = e.target;
  } else if (e.className && e.className.match(classPattern)) {
    src=e;
  } else {
    try {
      src = window.event.srcElement;
    } catch (err) {
    }
  }

  if (src.nodeName.toLowerCase() == 'img') {
    src=src.parentNode;
  }
  srcList = src.parentNode;
  if (srcList.nodeName.toLowerCase() == 'span') {
    srcList= srcList.parentNode;
    src= src.parentNode;
  }
  childList = null;

  for( i = 0; i < srcList.childNodes.length; i++ ) {
    if( srcList.childNodes[i].nodeName.toLowerCase() == 'ul' ) {
      childList = srcList.childNodes[i];
    }
  }
  var hide = collapsClass + ' ' + 'hide'
  var show = collapsClass + ' ' + 'show'
  if( src.getAttribute( 'class' ) == hide ) {
    var theSpan = src.childNodes[0];
    var theId= childList.getAttribute('id');
    createCookie(theId,0,7);
    src.setAttribute('class',show);
    src.setAttribute('title','click to expand');
    theSpan.innerHTML=expand;
    if (animate==1) {
      Effect.BlindUp(childList, {duration: 0.5});
    } else {
      childList.style.display = 'none';
    }
  } else {
    var theSpan = src.childNodes[0];
    var theId= childList.getAttribute('id');
    createCookie(theId,1,7);
    src.setAttribute('class',hide);
    src.setAttribute('title','click to collapse');
    theSpan.innerHTML=collapse;
    if (animate==1) {
      Effect.BlindDown(childList, {duration: 0.5});
    } else {
      childList.style.display = 'block';
    }
  }

  if( e.preventDefault ) {
    e.preventDefault();
  }

  return false;
}
