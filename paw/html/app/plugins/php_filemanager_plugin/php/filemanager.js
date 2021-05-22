/*********************************************************************************************************
 This code is part of the FileManager software (www.gerd-tentler.de/tools/filemanager), copyright by
 Gerd Tentler. Obtain permission before selling this code or hosting it on a commercial website or
 redistributing it over the Internet or in any other medium. In all cases copyright must remain intact.
*********************************************************************************************************/

//--------------------------------------------------------------------------------------------------------
// Configuration
//--------------------------------------------------------------------------------------------------------

var fmFadeSpeed = 15;   // fade speed (0 - 30; 0 = no fading)*

// * Fading was successfully tested only on Windows XP with IE 6, NN 7, Opera 9 and Firefox 1 + 2. Other
//   browsers might not support this feature.

//--------------------------------------------------------------------------------------------------------
// Get browser information
//--------------------------------------------------------------------------------------------------------

var OP = (navigator.userAgent.indexOf('Opera') != -1);
var IE = (navigator.userAgent.indexOf('MSIE') != -1 && !OP);
var IE4 = document.all;
var DOM = document.getElementById;
var MAC = (navigator.userAgent.indexOf('Mac') != -1);

//--------------------------------------------------------------------------------------------------------
// Global variables
//--------------------------------------------------------------------------------------------------------

var fmMouseX = fmMouseY = 0;
var fmDragging = false;
var fmTimer = fmOpacity = 0;
var fmObj, fmSObj, fmTObj;

//--------------------------------------------------------------------------------------------------------
// General functions
//--------------------------------------------------------------------------------------------------------

function fmGetObj(id) {
  var obj = null;
  if(DOM) obj = document.getElementById(id);
  else if(IE4) obj = document.all[id];
  return obj;
}

function fmGoTo(url, trgt) {
  if(trgt) parent.frames[trgt].location.href = url;
  else document.location.href = url;
}

function fmGoToOK(msg, url, trgt) {
  var ok = confirm(msg);
  if(ok) fmGoTo(url, trgt);
}

function fmGetWindowWidth() {
  var winX = 0;
  if(window.innerWidth)
    winX = window.innerWidth;
  else if(document.documentElement && document.documentElement.clientWidth)
    winX = document.documentElement.clientWidth;
  else if(document.body && document.body.clientWidth)
    winX = document.body.clientWidth;
  else winX = screen.width;
  return winX;
}

function fmGetWindowHeight() {
  var winY = 0;
  if(window.innerHeight)
    winY = window.innerHeight;
  else if(document.documentElement && document.documentElement.clientHeight)
    winY = document.documentElement.clientHeight;
  else if(document.body && document.body.clientHeight)
    winY = document.body.clientHeight;
  else winY = screen.height;
  return winY;
}

function fmGetScrollLeft() {
  var scrLeft = 0;
  if(document.documentElement && document.documentElement.scrollLeft)
    scrLeft = document.documentElement.scrollLeft;
  else if(document.body && document.body.scrollLeft)
    scrLeft = document.body.scrollLeft;
  else if(window.pageXOffset) scrLeft = window.pageXOffset;
  return scrLeft;
}

function fmGetScrollTop() {
  var scrTop = 0;
  if(document.documentElement && document.documentElement.scrollTop)
    scrTop = document.documentElement.scrollTop;
  else if(document.body && document.body.scrollTop)
    scrTop = document.body.scrollTop;
  else if(window.pageYOffset) scrTop = window.pageYOffset;
  return scrTop;
}

function fmViewError(error) {
  var x = Math.round((fmGetWindowWidth() - 400) / 2);
  var y = Math.round((fmGetWindowHeight() - 50) / 2);
  fmOpenDialog('fmError', error, null, null, null, x, y);
}

//--------------------------------------------------------------------------------------------------------
// Event handlers
//--------------------------------------------------------------------------------------------------------

function fmGetMouse(e) {
  var mouseX = fmMouseX;
  var mouseY = fmMouseY;

  if(e && e.pageX != null) {
    fmMouseX = e.pageX;
    fmMouseY = e.pageY;
  }
  else if(event && event.clientX != null) {
    fmMouseX = event.clientX + fmGetScrollLeft();
    fmMouseY = event.clientY + fmGetScrollTop();
  }
  if(fmMouseX < 0) fmMouseX = 0;
  if(fmMouseY < 0) fmMouseY = 0;

  if(fmDragging && fmSObj) {
    var x = parseInt(fmSObj.left + 0);
    var y = parseInt(fmSObj.top + 0);
    fmSObj.left = x + (fmMouseX - mouseX) + 'px';
    fmSObj.top = y + (fmMouseY - mouseY) + 'px';
  }
}

function fmStartDrag(e) {
  if(!DOM && !IE4) return;
  var firedobj = (e && e.target) ? e.target : event.srcElement;
  var topelement = DOM ? "HTML" : "BODY";

  if(DOM && firedobj.nodeType == 3) firedobj = firedobj.parentNode;

  if(firedobj.className == 'fmTH1') {
    firedobj.unselectable = true;

    while(firedobj.tagName != topelement && firedobj.className != "fmDialog")
      firedobj = DOM ? firedobj.parentNode : firedobj.parentElement;

    if(firedobj.className == "fmDialog") {
      fmSObj = firedobj.style;
      fmDragging = true;
    }
  }
}

document.onmousemove = fmGetMouse;
document.onmousedown = fmStartDrag;
document.onmouseup = function() { fmDragging = false; }

//--------------------------------------------------------------------------------------------------------
// Set opacity, fade-in/out
//--------------------------------------------------------------------------------------------------------

function fmSetOpacity(opacity) {
  if(fmObj) {
    fmObj.style.opacity = opacity / 100;
    fmObj.style.MozOpacity = opacity / 100;
    fmObj.style.KhtmlOpacity = opacity / 100;
    fmObj.style.filter = 'alpha(opacity=' + opacity + ')';
  }
}

function fmFadeIn() {
  if(fmSObj) {
    if(fmTimer) clearTimeout(fmTimer);
    fmSObj.visibility = 'visible';
    if(fmFadeSpeed && fmOpacity < 100) {
      fmOpacity += fmFadeSpeed;
      if(fmOpacity > 100) fmOpacity = 100;
      fmSetOpacity(fmOpacity);
      fmTimer = setTimeout('fmFadeIn()', 1);
    }
    else {
      fmOpacity = 100;
      fmSetOpacity(100);
    }
  }
}

function fmFadeOut() {
  if(fmSObj) {
    if(fmTimer) clearTimeout(fmTimer);
    if(fmFadeSpeed && fmOpacity > 0) {
      fmOpacity -= fmFadeSpeed;
      if(fmOpacity < 0) fmOpacity = 0;
      fmSetOpacity(fmOpacity);
      fmTimer = setTimeout('fmFadeOut()', 1);
    }
    else {
      fmOpacity = 0;
      fmSetOpacity(0);
      fmSObj.visibility = 'hidden';
    }
  }
}

//--------------------------------------------------------------------------------------------------------
// View dialog box
//--------------------------------------------------------------------------------------------------------

function fmSetDialogLeft(x) {
  var width = 0;
  if(MAC && IE) fmSObj.width = '100px';
  if(DOM) width = fmObj.offsetWidth;
  else if(IE4) width = fmSObj.pixelWidth;

  var left = x ? x : fmMouseX - width;
  if(left < 0) left = 0;

  fmSObj.left = left + 'px';
}

function fmSetDialogTop(y) {
  var hght = 0;
  var top = y ? y : fmMouseY;

  if(DOM) hght = fmObj.offsetHeight;
  else if(IE4) hght = fmSObj.pixelHeight;

  var winY = fmGetWindowHeight();
  var scrTop = fmGetScrollTop();
  if(top + hght - scrTop > winY) {
    if(hght > top) top = 0;
    else top -= hght;
  }

  fmSObj.top = top + 'px';
}

function fmOpenDialog(id, text, fileId, object, perms, x, y) {
  var f, e, i, start;

  if(fmSObj && fmSObj.visibility == 'visible') fmSObj.visibility = 'hidden';

  fmObj = fmGetObj(id);
  fmSObj = fmObj.style;
  if(text) fmTObj = fmGetObj(id+'Text');

  if(f = document.forms[id]) {
    f.reset();

    if(fileId && f.fmObject) f.fmObject.value = fileId;
    if(object && f.fmName) f.fmName.value = object;

    if(id == 'fmNewFile') {
      for(i = 1; i < 10; i++) {
        if(f['fmFile['+i+']']) {
          f['fmFile['+i+']'].style.display = 'none';
        }
      }
    }
  }

  if(perms) {
    e = f.elements;
    for(i = start = 0; i < e.length && !start; i++) {
      if(e[i].type.toLowerCase() == 'checkbox') start = i;
    }
    for(i = 0; i < 9; i += 3) {
      e[start+i].checked = (perms.substr(i+1, 1) == 'r') ? true : false;
      e[start+i+1].checked = (perms.substr(i+2, 1) == 'w') ? true : false;
      e[start+i+2].checked = (perms.substr(i+3, 1) == 'x') ? true : false;
    }
  }

  if(text && (DOM || IE4)) fmTObj.innerHTML = text;

  fmSetDialogLeft(x);
  fmSetDialogTop(y);
  fmFadeIn();
}

//--------------------------------------------------------------------------------------------------------
// Add file selector
//--------------------------------------------------------------------------------------------------------

function fmNewFileSelector(cnt) {
  var f = document.forms.fmNewFile;
  if(f && f['fmFile['+cnt+']']) f['fmFile['+cnt+']'].style.display = 'block';
  fmSetDialogTop(fmObj.offsetTop);
}
