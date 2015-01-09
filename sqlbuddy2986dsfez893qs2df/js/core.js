var sb;

var shiftPressed;
var lastActiveRow = -1;
var clearPanesOnLoad = false;

var activeWindow;
var animationStack = [];

var viewportSize = [0, 0];

function f(g) {
	if (g == undefined || g == "undefined" || g == null)
		return "";
	else
		return g;
}

var $E = function(selector, filter) {
	return ($(filter) || document).getElement(selector);
};

var $ES = function(selector, filter) {
	return ($(filter) || document).getElements(selector);
};

window.addEvent("domready", function() {
	document.addEvent("keydown", function(e) {
		var ev = new Event(e);
		if (ev.shift)
			shiftPressed = true;
	});
	
	document.addEvent("keyup", function() {
		shiftPressed = false;
	});
	
	// to disable keyboard shortcuts, comment out the following line
	window.addEvent("keydown", runKeyboardShortcuts);
	
	sb = new Page();
	
	initializeSidemenu();
	
	sb.loadHash();
	sb.loadPage();
	
	(function(){ sb.preload(); }).delay(500);
	(function(){ sb.checkHashState(); }).periodical(75);
	(function(){
		var winWidth = getWindowWidth();
		var winHeight = getWindowHeight();
		if (viewportSize[0] != winWidth || viewportSize[1] != winHeight) {
			viewportSize = [winWidth, winHeight];
			sizePage();
		}
	}).periodical(175);
	(function(){ autoExpandTextareas(); }).periodical(500);
});

function Page() {
	this.page = "home.php";
	this.db;
	this.table;
	this.topTabSet = 0;
	this.topTab = 0;
	this.s;
	this.view;
	this.sortDir;
	this.sortKey;
	
	this.$GUID = 1;
	
	this.pane;
	this.grid;
	this.gridHeader;
	this.leftChecks;
	
	this.hashMemory = "";
	
	this.submenuHeights = [];
	this.submenuIds = [];
	this.tableRowCounts = [];
	
	this.topTabs = [new TopTabGroup("Main"), new TopTabGroup("Database"), new TopTabGroup("Table")];
	
	if (showUsersMenu) {
		this.topTabs[0].addTab(gettext("Home"), "home.php").addTab(gettext("Users"), "users.php").addTab(gettext("Query"), "query.php").addTab(gettext("Import"), "import.php").addTab(gettext("Export"), "export.php");
	} else {
		this.topTabs[0].addTab(gettext("Home"), "home.php").addTab(gettext("Query"), "query.php").addTab(gettext("Import"), "import.php").addTab(gettext("Export"), "export.php");
	}
	
	this.topTabs[1].addTab(gettext("Overview"), "dboverview.php").addTab(gettext("Query"), "query.php").addTab(gettext("Import"), "import.php").addTab(gettext("Export"), "export.php");
	this.topTabs[2].addTab(gettext("Browse"), "browse.php").addTab(gettext("Structure"), "structure.php").addTab(gettext("Insert"), "insert.php").addTab(gettext("Query"), "query.php").addTab(gettext("Import"), "import.php").addTab(gettext("Export"), "export.php");
	
}

Page.prototype.loadPage = function() {
	if (this.page == "browse.php" && parseInt(this.tableRowCounts[this.db + "_" + this.table]) == 0) {
		this.page = "structure.php";
		this.topTab = 1;
	}
	
	var pageUrl = "";
	if (f(this.s))
		pageUrl += "s=" + this.s + "&";
	if (f(this.sortDir))
		pageUrl += "sortDir=" + this.sortDir + "&";
	if (f(this.sortKey))
		pageUrl += "sortKey=" + this.sortKey + "&";
	pageUrl = pageUrl.substring(0, pageUrl.length - 1);
	
	if (this.page == "editcolumn.php") {
		this.page = "structure.php";
		this.topTab = 1;
	} else if (this.page == "edituser.php") {
		this.page = "users.php";
		this.topTab = 1;
	} else if (this.page == "edit.php") {
		this.page = "browse.php";
		this.topTab = 0;
	}
	
	clearPanesOnLoad = true;
	var x = new XHR({url: this.page, onSuccess: finishTabLoad}).send(pageUrl);
}

Page.prototype.refreshTopTabSet = function(setNum) {
	if (f(setNum))
		this.topTabSet = setNum;
	
	var topTabsElem = $E('#toptabs ul');
	topTabsElem.empty();
	var loopStop = this.topTabs[this.topTabSet].tabCounter;
	var browseIsActive = true;
	var currentTab, rowCount, tabId, tabLiObj, tabAObj, tabACount;
	
	for (var i=0; i<loopStop; i++) {	
		currentTab = this.topTabs[this.topTabSet].tabList[i];
		tabId = i;
		
		rowCount = sb.tableRowCounts[sb.db + "_" + sb.table];
		
		if (isNaN(rowCount))
			rowCount = 0;
		else
			rowCount = approximateNumber(rowCount);
		
		if (rowCount == 0)
			browseIsActive = false;
		
		tabLiObj = new Element('li');
		tabLiObj.id = tabId;
		if (sb.page == currentTab.url)
			tabLiObj.addClass('selected');
		if (currentTab.url == "browse.php" && browseIsActive == false)
			tabLiObj.addClass('deactivated');
		tabAObj = new Element('a');
		tabAObj.appendText(currentTab.title);
		if (currentTab.url == "browse.php" && f(rowCount) != "") {
			tabACount = new Element("span");
			tabACount.addClass("rowcount");
			tabACount.appendText("(" + rowCount + ")");
			tabAObj.appendChild(tabACount);
		}
		if (tabId != this.topTab && !(currentTab.url == "browse.php" && browseIsActive == false) && !currentTab.temp)
			tabAObj.onclick = topTabClicked;
		if (!(currentTab.url == "browse.php" && browseIsActive == false) && !currentTab.temp) {
			var hrefBuild = "#page=" + currentTab.url.substring(0, currentTab.url.length - 4);
			if (f(this.db))
				hrefBuild += "&db=" + this.db;
			if (f(this.table))
				hrefBuild += "&table=" + this.table;
			if (f(this.topTabSet) && this.topTabSet != 0)
				hrefBuild += "&topTabSet=" + this.topTabSet;
			if (tabId != 0)
				hrefBuild += "&topTab=" + tabId;
				
			tabAObj.href = hrefBuild;
		}
		tabLiObj.appendChild(tabAObj);
		topTabsElem.appendChild(tabLiObj);
	}
}

Page.prototype.getTabUrl = function(tabId) {
	tabId = tabId || 0;
	return this.topTabs[this.topTabSet].tabList[tabId].url;
}

Page.prototype.getTabTitle = function(tabId) {
	tabId = tabId || 0;
	return this.topTabs[this.topTabSet].tabList[tabId].title;
}

Page.prototype.removeTempTabs = function() {
	for (var i=0; i<this.topTabs[this.topTabSet].tabCounter; i++) {
		if ((this.topTabs[this.topTabSet].tabList[i].temp) == true && (this.topTabs[this.topTabSet].tabList[i].url != this.page)) {
			this.topTabs[this.topTabSet].removeTab(i);
		}
	}
}

Page.prototype.preload = function() {
	var images = ["closeHover.png", "loading.gif", "openArrow.png", "goto.png", "schemaHeader.png", "info.png", "infoHover.png", 
	"window-button.png", "window-center.png", "window-close.png", "window-header-center.png", "window-header-left.png",
	"window-header-right.png", "window-resize.png", "window-shadow-bottom-left.png", "window-shadow-bottom-right.png",
	"window-shadow-bottom.png", "window-shadow-left.png", "window-shadow-right.png"];
	var pre;
	for (var i=0; i<images.length; i++) {
		pre = new Image();
		pre.src = "images/" + images[i];
	}
}

Page.prototype.setHash = function() {
	var newHash = "";
	if (f(this.page))
		newHash += "page=" + this.page.substr(0, this.page.length - 4) + "&";
	if (f(this.db))
		newHash += "db=" + this.db + "&";
	if (f(this.table))
		newHash += "table=" + this.table + "&";
	if (f(this.topTabSet) && this.topTabSet != 0)
		newHash += "topTabSet=" + this.topTabSet + "&";
	if (f(this.topTab) && this.topTab != 0)
		newHash += "topTab=" + this.topTab + "&";
	if (f(this.s))
		newHash += "s=" + this.s + "&";
	if (f(this.view))
		newHash += "view=" + this.view + "&";
	if (f(this.sortDir))
		newHash += "sortDir=" + this.sortDir + "&";
	if (f(this.sortKey))
		newHash += "sortKey=" + this.sortKey + "&";
	
	newHash = "#" + newHash.substring(0, newHash.length-1);
	if (window.location.hash != newHash) {
		window.location.hash = newHash;
		this.hashMemory = newHash;
	}
}

Page.prototype.loadHash = function() {
	var hash = window.location.hash;
	var part = hash.substring(1);
	var pairs = part.split("&");
	
	this.hashMemory = window.location.hash;
	
	for (var i=0; i<pairs.length; i++) {
		var pairsplit = pairs[i].split("=");
		var key = pairsplit[0];
		var value = pairsplit[1];
		if (key == "page"){ this.page = value + ".php" }
		if (key == "db"){ this.db = value }
		if (key == "table"){ this.table = value }
		if (key == "topTabSet"){ this.topTabSet = value }
		if (key == "topTab"){ this.topTab = value }
		if (key == "s"){ this.s = value }
		if (key == "view"){ this.view = value }
		if (key == "sortDir"){ this.sortDir = value }
		if (key == "sortKey"){ this.sortKey = value }
	}
}

Page.prototype.checkHashState = function() {
	if (window.location.hash != this.hashMemory) {
		this.resetInternals();
		this.loadHash();
		this.loadPage();
	}
}

Page.prototype.resetInternals = function() {
	this.page = "home.php";
	this.db = "";
	this.table = "";
	this.topTabSet = 0;
	this.topTab = 0;
	this.s = "";
	this.view = "";
	this.sortDir = "";
	this.sortKey = "";
}

function TopTabGroup(name) {
	this.name = name;
	this.tabList = [];
	this.tabCounter = 0;
}

TopTabGroup.prototype.addTab = function(title, url, temp) {
	this.tabList[this.tabCounter++] = new TopTab(title, url, temp);
	return this; // for chaining
}

TopTabGroup.prototype.removeTab = function(id) {
	this.tabList[id] = null;
	this.tabCounter--;
}

function TopTab(title, url, temp) {
	this.title = title;
	this.url = url;
	this.temp = temp;
}

function initializeSidemenu() {
	var menudata = eval(menujson);
	menujson = null;
	var ulmenu = $('databaselist').firstChild;
	var currentItem, newli, newa, togglediv, textdiv;
	var subul, subli, suba, subatext;
	var counter = 0;
	
	for (var i=0; i<menudata['menu'].length; i++) {
		currentItem = menudata['menu'][i];
		newli = returnMenuItem(currentItem['name'], i);
		
		subul = new Element('ul');
		subul.addClass("sublist");
		subul.id = "sublist" + i;
		if (currentItem['items']) {
			for (var j=0; j<currentItem['items'].length; j++) {
				subli = returnSubMenuItem(currentItem.name, currentItem['items'][j].name, currentItem['items'][j].rowcount);
				subul.appendChild(subli);
				
				sb.tableRowCounts[currentItem.name + '_' + currentItem['items'][j].name] = currentItem['items'][j].rowcount;
			}
		}
		newli.appendChild(subul);
		ulmenu.appendChild(newli);
		
		sb.submenuHeights[i] = subul.clientHeight;
		// these properties have to be set after the height is measured
		subul.style.height = "0px";
		subul.style.display = "none";
	}
	
	menudata = null;
}

function addSubMenuItem(sublist, db, table) {
	var subul = $(sublist);
	
	var newItem = returnSubMenuItem(db, table, 0);
	
	subul.appendChild(newItem);
	
	subul.style.height = '';
	subul.style.display = 'block';
	
	recalculateSubmenuHeight(sublist);
}

function returnSubMenuItem(db, table, count) {
	var subli, suba, subacount;
	var subId = sb.$GUID++;
	subli = new Element('li');
	subli.id = "sub" + subId;
	suba = new Element('a');
	suba.href = "#page=browse&db=" + db + "&table=" + table + "&topTabSet=2&topTab=0";
	suba.onclick = subTabClick;
	suba.appendText(table);
	subacount = new Element('span');
	subacount.className = "subcount";
	subacount.appendText("(" + approximateNumber(count) + ")");
	suba.appendChild(subacount);
	subli.appendChild(suba);
	sb.submenuIds[db + '_' + table] = "sub" + subId;
	
	return subli;
}

function returnMenuItem(db, i) {
	var menuli, menua, togglea, texta
	menuli = new Element('li');
	menuli.id = 'db' + i;
	menua = new Element('a');
	menua.onclick = $lambda(false);
	togglea = new Element('a');
	togglea.className = "menutoggler";
	togglea.onclick = toggleMenuClick;
	menua.appendChild(togglea);
	texta = new Element('a');
	texta.className = "menutext";
	texta.href = "#page=dboverview&db=" + db + "&topTabSet=1&topTab=0";
	texta.onclick = databaseClick;
	texta.innerHTML = db;
	menua.appendChild(texta);
	menuli.appendChild(menua);
	sb.submenuIds[db] = "db" + i;
	
	return menuli;
}

function addMenuItem(db) {
	var ulmenu = $E('#databaselist ul');
	var i = ulmenu.childNodes.length;
	var newli = returnMenuItem(db, i);
	
	var subul = new Element('ul');
	subul.className = "sublist";
	subul.id = "sublist" + i;
	subul.style.height = "0px";
	subul.style.display = "none";
	
	newli.appendChild(subul);
	ulmenu.appendChild(newli);
	
	sb.submenuHeights[i] = 0;
}

function showPane(paneId) {
	var panes = $$('#innercontent div[id^=pane]');
	for (var i=0; i<panes.length; i++) {
		panes[i].style.display = 'none';
	}
	$(paneId).style.display = '';
}

function addPane(paneId) {
	if (Browser.Engine.trident)
		clearPanes();
	var pane = new Element('div');
	pane.className = 'pane';
	pane.id = paneId;
	$('innercontent').appendChild(pane);
	showPane(paneId);
}

function generatePrompt(prepend, postpend, single, multiple, parameter, showQuery, context) {
	if (context) {
		var grid = $(context);
		var inputs = $ES("input", grid);
	} else {
		var grid = sb.grid;
		var inputs = $ES("input", sb.leftChecks);
	}
	
	if (f(grid)) {
		var buildList = "";
		var m = 0;
		for (var i=0; i<inputs.length; i++) {
			if (inputs[i].type == "checkbox" && inputs[i].checked == true) {
				buildList += prepend + inputs[i].get("querybuilder") + postpend + ";\n ";
				m++;
			}
		}
		if (buildList) {
			var prompter = gettext("Are you sure you want to") + " ";
			if (m == 1)
				prompter += single + "? ";
			else
				prompter += multiple + "? ";
			
			if (showQuery) {
				var formattedQuery = buildList.replace(/\n/g, "<br />");
				
				if (m == 1)
					prompter += gettext("The following query will be run:");
				else
					prompter += gettext("The following queries will be run:");
					
				prompter += " <div class=\"querybox\">" + formattedQuery + "</div>";
			}
			
			buildList = encodeURIComponent(buildList.replace(/\n/g, ""));
			buildList = buildList.replace(/'/g, "\\'");
			
			buildUrl = parameter + "=" + buildList;
			
			if (sb.page == "browse.php") {
				if (f(sb.view))
					buildUrl += "&view=" + sb.view;
					
				if (f(sb.s))
					buildUrl += "&s=" + sb.s;
					
				if (f(sb.sortKey))
					buildUrl += "&sortKey=" + sb.sortKey
					
				if (f(sb.sortDir))
					buildUrl += "&sortDir=" + sb.sortDir;
			}
			
			showDialog(gettext("Confirm"),
				prompter,
				"var x = new XHR({url: \"" + sb.page + "\", onSuccess: finishTabLoad}).send(\"" + buildUrl + "\");"
			);
		}
	}
}

function showDialog(title, content, action) {	
	createWindow(title, content, {isDialog: true, dialogAction: action});
}

function submitForm(formId) {
	var theForm = $(formId);
	var action = theForm.get("action");
	
	if (!action)
		action = sb.page;
	
	var x = new XHR({url: action, onSuccess: finishTabLoad}).send(theForm.toQueryString());
}

var XHR = new Class({

	Extends: Request,
	
	initialize: function(options) {
		
		if (!options.url)
			options.url = sb.page;
		
		if (options.url.indexOf("?") == -1)
			options.url += "?ajaxRequest=" + sb.$GUID++;
		else
			options.url += "&ajaxRequest=" + sb.$GUID++;
		
		options.url += "&requestKey=" + requestKey;
		
		if (f(sb.db))
			options.url += "&db=" + sb.db;
		if (f(sb.table))
			options.url += "&table=" + sb.table;
		
		this.parent(options);
		
		if (options && options.showLoader != false) {
			show('load');
			
			this.addEvent("onSuccess", function() {
				hide('load');
			});
			
			this.addEvent("onFailure", function() {
				hide('load');
				createWindow(gettext("Error"), gettext("There was an error receiving data from the server."), {isDismissible: true});
			});
		}
	},
	
	// redefined to avoid auto script execution
	success: function(text, xml) {
		this.onSuccess(text, xml);
	}
	
});

function gettext(str) {
	if (f(getTextArr[str]) != "")
		return getTextArr[str];
	else
		return str;
}

function printf() {
	var argv = printf.arguments;
	var argc = parseInt(argv.length);
	
	var inputString = argv[0];
	
	for (var i=1; i<argc; i++) {
		var position = inputString.indexOf("%s");
		var firstPart = inputString.substring(0, position + 2);
		var lastPart = inputString.substring(position + 2);
		firstPart = firstPart.replace("%s", argv[i]);
		inputString = firstPart + lastPart;
	}
	
	return inputString;
}

function fullTextWindow(rowId) {
	var rowQuery = $E(".check" + rowId, sb.pane).get("queryBuilder");
	var fullQuery = "SELECT * FROM " + returnQuote() + sb.table + returnQuote() + " " + rowQuery;
	var loadWin = createWindow(gettext("Loading..."), gettext("Loading..."));
	var x = new XHR({
		url: "ajaxfulltext.php", 
		onSuccess: function(responseText) {
			$(loadWin).dispose();
			createWindow(gettext("Full Text"), responseText)
		},
		onFailure: function() {
			$(loadWin).dispose();
		}
	}).send("query=" + fullQuery);
}

function createWindow(title, content, options) {
	options = options || {};
	
	var windowInnerWidth = getWindowWidth();
	
	var textWindow = new Element('div');
	textWindow.className = 'fulltextwin';
	if (options.isDialog || options.isDismissible) {
		textWindow.className += " dialog";
	}
	
	var windowId = "window" + sb.$GUID++;
	textWindow.id = windowId;
	
	var leftValue = Math.round((windowInnerWidth - 475) / 2);
	
	textWindow.style.left = leftValue + "px";
	
	var topValue = 120;
	
	if (window.scrollY)
		topValue += window.scrollY;
	
	textWindow.style.top = topValue + "px";
	textWindow.style.zIndex = sb.$GUID;
	var windowMain = new Element('div');
	windowMain.className = 'fulltextmain';
	var windowHeader = new Element('div');
	windowHeader.className = 'fulltextheader';
	windowHeader.addEvent("mousedown", focusWindow);
	windowHeader.addEvent("mousedown", startDrag);
	var windowHeaderContent = '<table cellspacing="0" width="100%"><tr><td class="headertl"></td><td class="headercenter"><p><img class="fulltextimage" src="images/window-close.png" align="right" onclick="closeWindow(\'' + windowId + '\')" />' + title + '</p></td><td class="headertr"></td></tr></table>';
	windowHeader.set('html', windowHeaderContent);
	windowMain.appendChild(windowHeader);
	
	if (options.isDialog && f(options.dialogAction) != "") {
		content += '<div class="buttons"><table cellspacing="0" width="100%"><tr><td>&nbsp;</td><td align="right" width="20" style="padding-right: 8px"><input type="submit" id="' + windowId + 'Click" class="windowbutton" value="' + gettext("Okay") + '" /></td><td align="right" width="20"><input type="button" onclick="closeWindow(\'' + windowId + '\')" class="windowbutton" value="' + gettext("Cancel") + '" /></td></tr></table></div>';
	} else if (options.isDismissible) {
		content += '<div class="buttons"><table cellspacing="0" width="100%"><tr><td>&nbsp;</td><td align="right"><input type="submit" id="' + windowId + 'Click" onclick="closeWindow(\'' + windowId + '\')" class="windowbutton" value="' + gettext("Okay") + '" /></td></tr></table></div>';
	}
	
	var windowInner = new Element('div');
	windowInner.className = 'fulltextinner';
	var innerContent = '<table cellspacing="0" width="100%"><tr><td class="mainl"></td><td class="maincenter"><div class="fulltextcontent" style="max-height: 400px">' + content + '</div>';
	
	if (!(options.isDialog || options.isDismissible)) {
		innerContent += '<div class="resizeHandle"><img src="images/window-resize.png" id="resize' + windowId + '"></div>';
	}
	
	innerContent += '</td><td class="mainr"></td></tr></table>';
	
	windowInner.set('html', innerContent);
	windowMain.appendChild(windowInner);
	textWindow.appendChild(windowMain);
	
	var windowFooter = new Element('div');
	windowFooter.className = 'fulltextfooter';
	var footerCode = '<table cellspacing="0" width="100%"><tr><td class="footerbl"></td><td class="footermiddle">&nbsp;</td><td class="footerbr"></td></tr></table>';
	windowFooter.set('html', footerCode);
	
	textWindow.appendChild(windowFooter);
	document.body.appendChild(textWindow);
	
	if (options.isDialog == true && f(options.dialogAction) != "") {
		var okayClick = $(windowId + 'Click');
		okayClick.addEvent("click", function() {
			closeWindow(windowId);
			eval(options.dialogAction);
		});
		okayClick.focus();
	} else if (options.isDismissible == true) {
		var okayClick = $(windowId + 'Click');
		okayClick.focus();
	} else if (!(options.isDialog || options.isDismissible)) {
		var resizeHandle = $('resize' + windowId);
		resizeHandle.addEvent("mousedown", startResize);
	}
	
	return windowId;
}

function show(a) {
	$(a).style.display = '';
}

function hide(a) {
	$(a).style.display = 'none';
}

function runKeyboardShortcuts(e) {
	var event = new Event(e);
	if (!((event.target.nodeName == "INPUT" && (event.target.type == "text" || event.target.type == "password")) || (event.target.nodeName == "TEXTAREA") || event.meta || event.control)) {
		if (event.key == "a") {
			checkAll();
		} else if (event.key == "n") {
			checkNone();
		} else if (event.key == "e") {
			if (sb.page == "browse.php" || sb.page == "structure.php" || sb.page == "users.php")
				editSelectedRows();
		} else if (event.key == "d") {
			if (sb.page == "structure.php")
				deleteSelectedColumns();
			else if (sb.page == "users.php")
				deleteSelectedUsers();
			else if (sb.page == "browse.php")
				deleteSelectedRows();
			else if (sb.page == "dboverview.php")
				dropSelectedTables();
		} else if (event.key == "r") {
			sb.loadPage();
		} else if (event.key == "f" && sb.page == "browse.php") {
			if ($('firstNav'))
				eval($('firstNav').get("onclick"));
		} else if (event.key == "g" && sb.page == "browse.php") {
			if ($('prevNav'))
				eval($('prevNav').get("onclick"));
		} else if (event.key == "h" && sb.page == "browse.php") {
			if ($('nextNav'))
				eval($('nextNav').get("onclick"));
		} else if (event.key == "l" && sb.page == "browse.php") {
			if ($('lastNav'))
				eval($('lastNav').get("onclick"));
		} else if (event.key == "q") {
			var tabId = 0;
			while (sb.getTabUrl(tabId) != "query.php") {
				tabId++;
			}
			topTabLoad(tabId);
			
			event.stop();
			event.stopPropagation();
		} else if (event.key == "o" && sb.page == "dboverview.php") {
			optimizeSelectedTables();
		}
		
	} else if (event.target.nodeName == "TEXTAREA" && event.control && event.key == "enter") {
		var curr = $(event.target);
		while (curr && curr.get('tag') != "form") {
			curr = $(curr.parentNode);
		}
		
		if (curr) {
			currButton = $E("input[type=submit]", curr);
			if (currButton) {
				currButton.click();
			}
		}
	}
}

function getWindowWidth() {
	if (window.innerWidth)
		return window.innerWidth;
	else
		return document.documentElement.clientWidth;
}

function getWindowHeight() {
	if (window.innerHeight)
		return window.innerHeight;
	else
		return document.documentElement.clientHeight;
}

function getScrollbarWidth() {
	
	var outer = new Element('div');
	outer.style.position = 'absolute';
	outer.style.top = '-1000px';
	outer.style.left = '-1000px';
	outer.style.width = '100px';
	outer.style.height = '50px';
	outer.style.overflow = 'hidden';
	
	var inner = new Element('div');
	inner.style.width = '100%';
	inner.style.height = '200px';
	
	outer.appendChild(inner);
	document.body.appendChild(outer);
	
	var w1 = inner.offsetWidth;
	outer.style.overflow = "auto";
	var w2 = inner.offsetWidth;
	
	document.body.removeChild(outer);
	
	return (w1 - w2);
};

function addAnimation(id, finish) {
	var elem = $(id);
	
	//remove duplicates
	for (var i in animationStack) {
		if (animationStack[i][0] == elem)
			animationStack.splice(i, 1);
	}
	
	var start = elem.offsetHeight;
	
	var change = finish - start;
	
	var totalFrames = 15;
	
	if (window.gecko)
		totalFrames -= 5;
	
	animationStack.push([elem, start, change, 0, totalFrames]);
	if (animationStack.length == 1)
		animate();
}

function animate() {
	var j, elem, start, change, currentFrame, totalFrames;
	for (var i = 0; i < animationStack.length; i++) {
		
		j = parseInt(i);
		
		elem = animationStack[j][0];
		start = animationStack[j][1];
		change = animationStack[j][2];
		animationStack[j][3] += 1;
		currentFrame = animationStack[j][3];
		totalFrames = animationStack[j][4];
		
		var newHeight = sineInOut(currentFrame, start, change, totalFrames);
		
		elem.style.height = newHeight + "px";
		
		if (currentFrame >= totalFrames) {
			animationStack.splice(j, 1);
			
			//if the menu is expanded, take off the explicit height attribute
			if (elem.style.height != "0px") {
				elem.style.height = '';
			}
		}
	}
	if (animationStack.length > 0)
		setTimeout('animate()', 25);
}

function sineInOut(t, b, c, d) {
	return -c/2 * (Math.cos(Math.PI*t/d) - 1) + b;
}