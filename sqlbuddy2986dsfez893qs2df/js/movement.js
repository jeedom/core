var mouseX = -1;
var mouseY = -1;
var lastWidth = -1;
var lastHeight = -1;
var lastLeft = -1;
var lastTop = -1;
var activeContent;
var compX = 0;
var compY = 0;
var activeColumnId = -1;
var activeColumn;
var styleNodeKeys = [];
var styleNodes = [];

function startResize(e) {
	var event = new Event(e);
	
	activeWindow = event.target;
	while (activeWindow != null && activeWindow.className.indexOf("fulltextwin") == -1) {
		activeWindow = activeWindow.parentNode;
	}
	
	activeContent = $E(".fulltextcontent", activeWindow);
	
	lastWidth = parseInt(activeWindow.offsetWidth);
	lastHeight = parseInt(activeContent.offsetHeight);
	mouseX = event.page.x;
	mouseY = event.page.y;
	
	activeContent.style.height = lastHeight + "px";
	activeContent.style.maxHeight = '';
	
	window.addEvent("mousemove", doResize);
	window.addEvent("mouseup", endResize);
	
	return false;
}

function doResize(e) {
	if (activeWindow) {
		var event = new Event(e);
		
		var diffX = event.page.x - mouseX;
		var diffY = event.page.y - mouseY;
		
		if (compX > 0 && compX > diffX) {
			compX -= diffX;
			diffX = 0;
		} else if (compX > 0) {
			diffX -= compX;
			compX = 0;
		}
		
		if (compY > 0 && compY > diffY) {
			compY -= diffY;
			diffY = 0;
		} else if (compY > 0) {
			diffY -= compY;
			compY = 0;
		}
		
		lastWidth = lastWidth + diffX;
		lastHeight = lastHeight + diffY;
		
		if (lastWidth < 175) {
			compX += 175 - lastWidth;
			lastWidth = 175;
		}
		
		if (lastHeight < 100) {
			compY += 100 - lastHeight;
			lastHeight = 100;
		}
		
		mouseX = event.page.x;
		mouseY = event.page.y;
		
		activeWindow.style.width = lastWidth + "px";
		activeContent.style.height = lastHeight + "px";
	}
}

function endResize() {
	activeWindow = null;
	activeContent = null;
	compX = 0;
	compY = 0;
	window.removeEvent("mousemove", doResize);
	window.removeEvent("mouseup", endResize);
}

function startDrag(e) {
	var event = new Event(e);
	
	activeWindow = event.target;
	while (activeWindow != null && activeWindow.className.indexOf("fulltextwin") == -1) {
		activeWindow = activeWindow.parentNode;
	}
	
	lastLeft = activeWindow.style.left;
	lastLeft = parseInt(lastLeft.substring(0, lastLeft.length - 2));
	lastTop = activeWindow.style.top;
	lastTop = parseInt(lastTop.substring(0, lastTop.length - 2));
	mouseX = event.page.x;
	mouseY = event.page.y;
	
	window.addEvent("mousemove", doDrag);
	window.addEvent("mouseup", endDrag);
	
	return false;
}

function doDrag(e) {
	if (activeWindow) {
		var event = new Event(e);
		
		var diffX = event.page.x - mouseX;
		var diffY = event.page.y - mouseY;
		
		lastLeft = lastLeft + diffX;
		lastTop = lastTop + diffY;
		mouseX = event.page.x;
		mouseY = event.page.y;
		
		activeWindow.style.left = lastLeft + "px";
		activeWindow.style.top = lastTop + "px";
	}
}

function endDrag() {
	activeWindow = null;
	window.removeEvent("mousemove", doDrag);
	window.removeEvent("mouseup", endDrag);
}

function startColumnResize(e) {
	var event = new Event(e);
	
	activeColumn = $(event.target.offsetParent.previousSibling.firstChild);
	
	activeColumnId = parseInt(activeColumn.getProperty("column"));
	
	lastWidth = parseInt(activeColumn.clientWidth) - 11; // -11 to account for padding
	mouseX = event.page.x;
	
	document.body.style.cursor = "ew-resize";
	
	window.addEvent("mousemove", columnResize);
	window.addEvent("mouseup", endColumnResize);
	
	return false;
}

function columnResize(e) {
	if (activeColumn) {
		var event = new Event(e);
		
		var diff = (event.page.x - mouseX);
		
		lastWidth = (lastWidth + diff);
		mouseX = event.page.x;
		
		var removeLater = -1;
		var keyName = 'pane' + sb.topTab + '_' + activeColumnId;
		
		for (var i=0; i<styleNodeKeys.length; i++) {
			if (styleNodeKeys[i] == keyName) {
				document.getElementsByTagName("head")[0].removeChild(styleNodes[i]);
				removeLater = i;
			}
		}
		
		if (removeLater >= 0) {
			styleNodes.splice(removeLater, 1);
			styleNodeKeys.splice(removeLater, 1);
		}
		
		var newNode = new Element("style");
		newNode.setAttribute("type", "text/css");
		
		newNode.appendText("#pane" + sb.topTab + " .column" + activeColumnId + " { width: " + lastWidth + "px !important }");
		document.getElementsByTagName("head")[0].appendChild(newNode);
		
		styleNodes.push(newNode);
		styleNodeKeys.push(keyName);
	}
}

function endColumnResize() {
	document.body.style.cursor = "";
	activeColumn = null;
	window.removeEvent("mousemove", columnResize);
	window.removeEvent("mouseup", endColumnResize);
}

function clearColumnSizes() {
	if (styleNodes.length > 0) {
		for (var i=0; i<styleNodes.length; i++) {
			if (f(styleNodes[i]) != "") {
				document.getElementsByTagName("head")[0].removeChild(styleNodes[i]);
			}
		}
		styleNodes = [];
		styleNodeKeys = [];
	}
}