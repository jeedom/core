function clearPanes() {
	$('innercontent').empty();
}

function toggleMenuClick(e) {
	var event = new Event(e);
	var menuId = event.target.parentNode.parentNode.id;
	toggleMenu(menuId.substring(2));
}

function toggleMenu(b, forceExpand) {
	b = parseInt(b);
	var menuId = "db" + b;
	var menu = $(menuId);
	var sub = "sublist" + b;
	
	if (!menu.hasClass("expanded")) {
		// accordion
		var openMenus = $ES(".expanded");
		for (var m=0; m<openMenus.length; m++) {
			var openSub = openMenus[m].id.replace("db", "sublist");
			openMenus[m].removeClass("expanded");
			addAnimation(openSub, 0);
		}
		
		menu.addClass("expanded");
		$(sub).style.display = 'block';
		addAnimation(sub, sb.submenuHeights[b]);
	} else if (forceExpand != true) {
		menu.removeClass("expanded");
		addAnimation(sub, 0);
	}
}

function sideMainClick(page, top) {
	if (sb.topTabSet != 0)
		clearPanesOnLoad = true;
	sb.resetInternals();
	sb.page = page;
	sb.topTab = top;
	var x = new XHR({url: sb.page, onSuccess: finishTabLoad}).send();
}

function databaseClick(e) {
	var event = new Event(e);
	db = event.target.get('text');
	databaseLoad(db);
	return false;
}

function databaseLoad(db) {
	clearPanesOnLoad = true;
	sb.resetInternals();
	sb.page = "dboverview.php";
	sb.db = db;
	sb.topTabSet = 1;
	sb.topTab = 0;
	var x = new XHR({url: sb.page, onSuccess: finishTabLoad}).send();
}

function subTabClick(e) {
	var event = new Event(e);
	var subTabElem = event.target.parentNode;
	var subtabs = $$('#sidemenu li');
	for (var i=0; i<subtabs.length; i++) {
		subtabs[i].removeClass("loading");
	}
	
	if (!subTabElem.hasClass("selected")) {
		subTabElem.addClass("loading");
	}
}

function subTabLoad(db, table) {
	clearPanesOnLoad = true;
	sb.resetInternals();
	sb.topTabSet = 2;
	sb.db = db;
	sb.table = table;
	
	if (parseInt(sb.tableRowCounts[sb.db + "_" + sb.table]) == 0) {
		sb.page = "structure.php";
		sb.topTab = 1;
	} else {
		sb.page = "browse.php";
		sb.topTab = 0;
	}
	var x = new XHR({url: sb.page, onSuccess: finishTabLoad}).send();
	return false;
}

function topTabClicked(e) {
	var event = new Event(e);
	var tabClicked = event.target.parentNode.id;
	topTabLoad(tabClicked);
	return false;
}

function topTabLoad(tab) {	
	sb.topTab = tab;
	sb.page = sb.getTabUrl(sb.topTab);
	
	var pane = $(tab + 'pane');
	
	if (pane != undefined && !clearPanesOnLoad) {
		finishTabLoad();
	} else {
		var x = new XHR({url: sb.page, onSuccess: finishTabLoad}).send();
	}
}

function browseNav(start, view) {
	sb.s = start;
	sb.view = view;
	var x = new XHR({url: "browse.php", onSuccess: finishTabLoad}).send("s=" + f(sb.s) + "&view=" + f(sb.view) + "&sortKey=" + f(sb.sortKey) + "&sortDir=" + f(sb.sortDir));
}

function finishTabLoad(responseText) {
	if ($('bottom').style.opacity != '1') {
		$('bottom').style.opacity = '1';
		document.body.style.backgroundImage = "none";
	}
	
	if (clearPanesOnLoad) {
		clearPanes();
		clearColumnSizes();
		clearPanesOnLoad = false;	
	}
	
	if ($('pane' + sb.topTab) != undefined)
		showPane('pane' + sb.topTab);
	else
		addPane('pane' + sb.topTab);
	
	if (responseText)
		$('pane' + sb.topTab).innerHTML = responseText;
	
	scrollTo(0, 0);
	
	sb.removeTempTabs();
	sb.refreshTopTabSet();
	
	// update the grid variables
	sb.pane = $('pane' + sb.topTab);
	sb.grid = $E('.gridscroll', sb.pane);
	sb.gridHeader = $E('.gridheader', sb.pane);
	sb.leftChecks = $E('.leftchecks', sb.pane);
	
	runJavascriptContent();
	sizePage();
	
	var sideId = getSubMenuId(sb.db, sb.table);
	if (f(sideId) != "") {
		deselectSideMenu();
		$(sideId).addClass("selected");
		
		//make sure the side menu is expanded
		var targ = $(sideId);
		
		if (f(sb.db) && f(sb.table) && f(targ)) {
			while (f(targ) && !targ.hasClass("sublist")) {
				targ = targ.parentNode;
			}
			if (f(targ)) {
				var toExpand = targ.id;
				var toExpandId = parseInt(toExpand.substring(7));
				toggleMenu(toExpandId, true);
			}
		} else if (f(sb.db) && f(targ)) {
			var toExpand = $E('.sublist', targ).id;
			var toExpandId = parseInt(toExpand.substring(7));
			toggleMenu(toExpandId, true);
		}
	}
	
	sb.setHash();
	
	var pageTitle;
	if (sb.table) {
		pageTitle = sb.getTabTitle(sb.topTab) + " - " + sb.table;
	} else if (sb.db) {
		pageTitle = sb.getTabTitle(sb.topTab) + " - " + sb.db;
	} else {
		pageTitle = sb.getTabTitle(sb.topTab);
	}
	document.title = "SQL Buddy - " + pageTitle;
	
	refreshRowCount();
}

function deselectSideMenu() {
	var subtabs = $$('#sidemenu li');
	for (var i=0; i<subtabs.length; i++) {
		subtabs[i].removeClass("loading").removeClass("selected");
	}
}

function checkAll(context) {
	if (context) {
		var grid = $(context);
		var rows = $E('.gridscroll', grid).childNodes;
		var inputs = grid.getElementsByTagName("input");
		var lc = $E('.leftchecks', grid);
	} else {
		var grid = sb.grid;
		var rows = grid.childNodes;
		var inputs = sb.pane.getElementsByTagName("input");
		var lc = sb.leftChecks;
	}
	
	if (f(grid)) {
		for (var i=0; i<rows.length; i++) {
			if (inputs[i].type == "checkbox") {
				inputs[i].checked = true;
				if (rows[i].className.indexOf("highlighted") == -1) {
					rows[i].className += " highlighted";
					lc.childNodes[i].className += " highlighted";
				}
			}
		}
		lastActiveRow = -1;
	}
}

function checkNone(context) {
	if (context) {
		var grid = $(context);
		var rows = $E('.gridscroll', grid).childNodes;
		var inputs = grid.getElementsByTagName("input");
		var lc = $E('.leftchecks', grid);
	} else {
		var grid = sb.grid;
		var rows = grid.childNodes;
		var inputs = sb.pane.getElementsByTagName("input");
		var lc = sb.leftChecks;
	}
	
	if (f(grid)) {
		for (var i=0; i<inputs.length; i++) {
			if (inputs[i].type == "checkbox") {
				inputs[i].checked = false;
				if (rows[i] && rows[i].className.indexOf("highlighted") != -1) {
					rows[i].className = rows[i].className.replace("highlighted", "");
					lc.childNodes[i].className = lc.childNodes[i].className.replace("highlighted", "");
				}
			}
		}
		lastActiveRow = -1;
	}
}

function rowClicked(rowId, context) {	
	// ie changes checkbox after calling event
	if (!Browser.Engine.trident)
		highlightDataRow(rowId, context);
	else
		(function(){ highlightDataRow(rowId, context) }).delay(25);
	
	if (shiftPressed == true && lastActiveRow >= 0 && lastActiveRow != rowId) {
		if (context) {
			var grid = $E('.gridscroll', $(context));
			var checks = $E('.leftchecks', $(context)).childNodes;
		} else {
			var grid = sb.grid;
			var checks = sb.leftChecks.childNodes;
		}
		
		if (rowId < lastActiveRow) {
			for (var i=rowId+1; i<lastActiveRow; i++) {
				checks[i].firstChild.firstChild.checked = checks[rowId].firstChild.firstChild.checked;
				highlightDataRow(i, context);
			}
		} else {
			for (var i=rowId-1; i>lastActiveRow; i--) {
				checks[i].firstChild.firstChild.checked = checks[rowId].firstChild.firstChild.checked;
				highlightDataRow(i, context);
			}
		}
	}
	lastActiveRow = rowId;
}

function highlightDataRow(i, context) {
	if (context) {
		var grid = $(context);
		var rows = $E('.gridscroll', grid).childNodes;
		var lc = $E('.leftchecks', grid).childNodes;
	} else {
		var rows = sb.grid.childNodes;
		var lc = sb.leftChecks.childNodes;
	}
	
	if (lc[i].firstChild.firstChild.checked == true) {
		if (rows[i].className.indexOf("highlighted") == -1) {
			rows[i].className += " highlighted";
			lc[i].className += " highlighted";
		}
	} else {
		if (rows[i].className.indexOf("highlighted") != -1) {
			rows[i].className = rows[i].className.replace("highlighted", "");
			lc[i].className = lc[i].className.replace("highlighted", "");
		}
	}
}

function editSelectedRows() {
	var grid = sb.grid;
	if (f(grid)) {
		var editParts = "";
		var count = 0;
		var inputs = $ES("input", sb.leftChecks);
		for (var i=0; i<inputs.length; i++) {
			if (inputs[i].type == "checkbox" && inputs[i].checked == true) {
				editParts += (inputs[i]).get("querybuilder") + "; ";
				count++;
			}
		}
		if (count > 0) {
			if (sb.page == "structure.php")
				var loadPage = "editcolumn.php";
			else if (sb.page == "users.php")
				var loadPage = "edituser.php";
			else
				var loadPage = "edit.php";
			
			editParts = editParts.substring(0, editParts.length - 2);
			sb.topTabs[sb.topTabSet].addTab("Edit", loadPage, true);
			sb.page = loadPage;
			var x = new XHR({url: loadPage, onSuccess: finishTabLoad}).send("editParts=" + editParts);
		}
	}
}

function saveEdit(formId) {
	var form = $(formId);
	var queryPart = form.get("querypart");
	var x = new XHR({url: "ajaxsaveedit.php?queryPart=" + queryPart + "&form=" + formId, onSuccess: updateAfterEdit}).send(form.toQueryString());
}

function saveColumnEdit(formId) {
	var form = $(formId);
	var changes = getFieldSummary(form);
	var columnQuery = "ALTER TABLE `" + sb.table + "` CHANGE `" + form.get("querypart") + "` " + changes;
	var x = new XHR({url: "ajaxsavecolumnedit.php?form=" + formId, onSuccess: updateAfterEdit}).send("runQuery=" + columnQuery);
}

function saveUserEdit(formId) {
	var form = $(formId);
	var x = new XHR({url: "ajaxsaveuseredit.php?form=" + formId + "&user=" + form.get("querypart"), onSuccess: updateAfterEdit}).send(form.toQueryString());
}

function updateAfterEdit(json) {
	var response = eval('(' + json + ')');
	var formu = $(response.formupdate);
	if (response.errormess == "") {
		showUpdateMessage(formu);
	} else {
		var errHandle = $E('.errormessage', formu);
		errHandle.style.display = '';
		errHandle.set('text', response.errormess);
	}
	sizePage();
	clearPanesOnLoad = true;
}

function showUpdateMessage(formu) {
	// hide other messages
	hideUpdateMessages();
	
	formu.innerHTML = "";
	var updateId = sb.$GUID++;
	var updateDiv = new Element("div", {
		'class': 'insertmessage',
		'id': 'update' + updateId
	}).set('text', gettext("Your changes were saved to the database."));
	formu.appendChild(updateDiv);
	yellowFade($('update' + updateId));
	setTimeout(hideUpdateMessages, 1250);
}

function hideUpdateMessages() {
	var updates = $ES(".insertmessage");
	var edits = $ES(".edit");
	
	if (edits.length == 0) {
		updates[0].set('text', gettext("Redirecting..."));
		
		for (var i=1; i<updates.length; i++) {
			updates[i].dispose();
		}
		
		clearPanesOnLoad = true;
		
		if (sb.page == "edituser.php" || sb.page == "editcolumn.php")
			topTabLoad(1);
		else
			topTabLoad(0);
	} else {
		for (var i=0; i<updates.length; i++) {
			updates[i].dispose();
		}
	}
}

function cancelEdit(formu) {
	hideUpdateMessages();
	
	formu = $(formu);
	formu.set('html', '');
	
	var edits = $ES(".edit");
	
	if (edits.length == 0) {
		formu.set('text', gettext("Redirecting..."));
		formu.className = "insertmessage";
		
		clearPanesOnLoad = true;
		
		if (sb.page == "edituser.php" || sb.page == "editcolumn.php")
			topTabLoad(1);
		else
			topTabLoad(0);
	} else {
		formu.dispose();
	}
	
	sizePage();
}

function deleteSelectedRows() {
	generatePrompt("DELETE FROM " + quoteModifier(sb.table) + " ", "", gettext("delete this row"), gettext("delete these rows"), "runQuery", true);
}

function emptySelectedTables() {
	if (adapter == "sqlite")
		generatePrompt("DELETE FROM '", "'", gettext("empty this table"), gettext("empty these tables"), "runQuery", true);
	else if (adapter == "mysql")
		generatePrompt("TRUNCATE `", "`", gettext("empty this table"), gettext("empty these tables"), "runQuery", true);
}

function dropSelectedTables() {
	generatePrompt("DROP TABLE " + returnQuote(), returnQuote(), gettext("drop this table"), gettext("drop these tables"), "runQuery", true);
}

function deleteSelectedColumns() {
	generatePrompt("ALTER TABLE `" + sb.table + "` DROP `", "`", gettext("delete this column"), gettext("delete these columns"), "runQuery", true);
}

function deleteSelectedIndexes(context) {
	generatePrompt("DROP INDEX `", "` ON `" + sb.table + "`", gettext("delete this index"), gettext("delete these indexes"), "runQuery", true, context);
}

function deleteSelectedUsers() {
	generatePrompt("", "", gettext("delete this user"), gettext("delete these users"), "deleteUsers", false);
}

function optimizeSelectedTables() {
	var lc = sb.leftChecks;
	
	if (f(lc)) {
		var optimizeQuery = "";
		var inputs = $ES("input", lc);
		for (var i=0; i<inputs.length; i++) {
			if (inputs[i].type == "checkbox" && inputs[i].checked == true) {
				optimizeQuery += "OPTIMIZE TABLE `" + inputs[i].get("querybuilder") + "`; ";
			}
		}
		if (optimizeQuery) {	
			var x = new XHR({url: sb.page, onSuccess: finishTabLoad}).send("runQuery=" + optimizeQuery);
		}
	}
}

function executeQuery() {
	var query = encodeURIComponent($('QUERY').value);
	var x = new XHR({url: "query.php", onSuccess: finishTabLoad}).send("query=" + query);
}

function loadNewSort(key, direction) {
	sb.sortKey = key;
	sb.sortDir = direction;
	var x = new XHR({url: "browse.php", onSuccess: finishTabLoad}).send("view=" + sb.view + "&s=" + sb.s + "&sortKey=" + sb.sortKey + "&sortDir=" + sb.sortDir);
}

function confirmEmptyTable() {
	if (f(sb.table)) {
		if (adapter == "mysql") {
			var emptyQuery = "TRUNCATE TABLE `" + sb.table + "`";
		} else if (adapter == "sqlite") {
			var emptyQuery = "DELETE FROM '" + sb.table + "'";
		}
		
		showDialog(gettext("Confirm"),
			printf(gettext("Are you sure you want to empty the '%s' table? This will delete all the data inside of it. The following query will be run:"), sb.table) + "<div class=\"querybox\">" + emptyQuery + "</div>",
			"var x = new XHR({url: \"ajaxquery.php\", onSuccess: emptyTableCallback}).send(\"query=" + encodeURIComponent(emptyQuery) + "&silent=1\")"
		);
	}
}

function emptyTableCallback() {
	sb.tableRowCounts[sb.db + "_" + sb.table] = "0";
	subTabLoad(sb.db, sb.table);
}

function confirmDropTable() {
	var table = sb.table;
	if (f(table)) {
		var dropQuery = "DROP TABLE " + returnQuote() + table + returnQuote();
		var targ = $(getSubMenuId(sb.db, sb.table));
		while (!targ.hasClass("sublist")) {
			targ = targ.parentNode;
		}
		var toRecalculate = targ.id;
		showDialog(gettext("Confirm"),
			printf(gettext("Are you sure you want to drop the '%s' table? This will delete the table and all data inside of it. The following query will be run:"), table) + "<div class=\"querybox\">" + dropQuery + "</div>",
			"var x = new XHR({url: \"ajaxquery.php\"}).send(\"query=" + dropQuery + "&silent=1\"); $(getSubMenuId(sb.db, sb.table)).dispose(); databaseLoad(sb.db); recalculateSubmenuHeight(\"" + toRecalculate + "\");"
		);
	}
}

function optimizeTable() {
	if (sb.table) {
		var optimizeQuery = "OPTIMIZE TABLE `" + sb.table + "`;";
		var x = new XHR({url: "ajaxquery.php", onSuccess: function(){ sb.loadPage() } }).send("query=" + optimizeQuery + "&silent=1");
	}
}

function confirmDropDatabase() {
	var db = sb.db;
	if (f(db)) {
		var dropQuery = "DROP DATABASE `" + db + "`";
		showDialog(gettext("Confirm"),
			printf(gettext("Are you sure you want to drop the database '%s'? This will delete the database, the tables inside the database, and all data inside of the tables. The following query will be run:"), db) + "<div class=\"querybox\">" + dropQuery + "</div>",
			"var x = new XHR({url: \"ajaxquery.php\"}).send(\"query=" + dropQuery + "&silent=1\"); $(getSubMenuId(sb.db, sb.table)).dispose(); sideMainClick(\"home.php\",\"hometab\");"
		);
	}
}

function editTable() {
	var newName = $('RENAME').value;
	var charSelect = $('RECHARSET');
	if (charSelect)
		var newCharset = charSelect.options[charSelect.selectedIndex].value;
	
	var runQuery = "";
	
	if (newName != sb.table && adapter == "mysql") {
		runQuery += "RENAME TABLE `" + sb.table + "` TO `" + newName + "`;";
	} else if (newName != sb.table && adapter == "sqlite") {
		runQuery += "ALTER TABLE '" + sb.table + "' RENAME TO '" + newName + "';";
	}
	
	if (f(newCharset) != "") {
		runQuery += "ALTER TABLE `" + sb.table + "` CHARSET " + newCharset + ";";
	}
	
	if (f(runQuery) != "") {
		$('RENAME').blur();
		var x = new XHR({url: "ajaxquery.php", onSuccess: editTableCallback}).send("query=" + runQuery + "&silent=1");
	}
	
	// defined interally on purpose
	function editTableCallback() {
		if (f(newName) != "" && newName != sb.table) {
			var submenuItem = $(sb.submenuIds[sb.db + '_' + sb.table]);
			submenuItem.firstChild.set('text', newName);
			submenuItem.firstChild.href = "#page=browse&db=" + sb.db + "&table=" + newName + "&topTabSet=2";
			var subacount = new Element('span');
			subacount.className = "subcount";
			subacount.appendText("(" + approximateNumber(sb.tableRowCounts[sb.db + '_' + sb.table]) + ")");
			submenuItem.firstChild.appendChild(subacount);
			
			sb.submenuIds[sb.db + '_' + newName] = sb.submenuIds[sb.db + '_' + sb.table];
			sb.submenuIds[sb.db + '_' + sb.table] = '';
			sb.tableRowCounts[sb.db + '_' + newName] = sb.tableRowCounts[sb.db + '_' + sb.table];
			sb.tableRowCounts[sb.db + '_' + sb.table] = '';
			sb.table = newName;
			sb.setHash();
		}
		
		$('editTableMessage').set('text', gettext("Successfully saved changes."));
		yellowFade($('editTableMessage'));
		var clearTable = function() {
			$('editTableMessage').empty();
		};
		
		clearTable.delay(2000);
		
		clearPanesOnLoad = true;
	}
}

function editDatabase() {
	var charSelect = $('DBRECHARSET');
	var newCharset = charSelect.options[charSelect.selectedIndex].value;
	
	if (f(newCharset) != "") {
		var runQuery = "ALTER DATABASE `" + sb.db + "` CHARSET " + newCharset + ";";
		var x = new XHR({url: "ajaxquery.php", onSuccess: editDatabaseCallback}).send("query=" + runQuery + "&silent=1");
	}
	
	function editDatabaseCallback() {
		$('editDatabaseMessage').set('text', gettext("Successfully saved changes."));
		yellowFade($('editDatabaseMessage'));
		
		var clearDatabase = function() {
			$('editDatabaseMessage').empty();
		};
		
		clearDatabase.delay(2000);
		
		clearPanesOnLoad = true;
	}
}

function runJavascriptContent() {
	var scripts = $ES("script", sb.pane);
	for (var i=0; i<scripts.length; i++) {
		// basic xss prevention
		if (scripts[i].get("authkey") == requestKey) {
			var toRun = scripts[i].get('html');
			var newScript = new Element("script");
			newScript.set("type", "text/javascript");
			if (!Browser.Engine.trident) {
				newScript.innerHTML = toRun;
			} else {
				newScript.text = toRun;
			}
			document.body.appendChild(newScript);
			document.body.removeChild(newScript);
		}
	}
}

function recalculateSubmenuHeight(theMenu) {
	var idForMenu = parseInt(theMenu.substring(7));
	sb.submenuHeights[idForMenu] = $(theMenu).clientHeight;
}

function sizePage() {
	var windowInnerWidth = getWindowWidth();
	var windowInnerHeight = getWindowHeight();
	
	if (f(sb.grid) && (sb.page == "browse.php" || sb.page == "query.php")) {
		if (sb.page == "browse.php")
			var gridHeight = windowInnerHeight - 111;
		else if (sb.page == "query.php")
			var gridHeight = windowInnerHeight - 225;
		
		if (Browser.Engine.trident)
			gridHeight = gridHeight - 9;
		
		sb.grid.style.maxHeight = gridHeight + 'px';
		
		if (sb.leftChecks) {
			
			var scrollbarWidth = getScrollbarWidth();
			
			var otherWidth = sb.grid.scrollWidth + scrollbarWidth;
			
			// check for horizontal scrollbar
			if ((sb.grid.offsetHeight == sb.grid.scrollHeight && sb.grid.offsetWidth != sb.grid.scrollWidth) || (sb.grid.offsetHeight != sb.grid.scrollHeight && sb.grid.offsetWidth != otherWidth)) {
				sb.leftChecks.style.maxHeight = (gridHeight - scrollbarWidth) + 'px';
				sb.leftChecks.style.borderBottom = "1px solid rgb(200, 200, 200)";
			} else {
				sb.leftChecks.style.maxHeight = gridHeight + 'px';
			}
		}
		
		var gridWidth = windowInnerWidth - 19;
		sb.grid.style.maxWidth = gridWidth + 'px';
		
		sb.gridHeader.style.maxWidth = gridWidth + 'px';
		
	}
	if (f($('sidemenu'))) {
		var headerOffset = $('header').offsetHeight;
		var rightOffset = $('rightside').offsetHeight;
		
		// check to see if the right content is long enough to cause a scrollbar
		if ((headerOffset + rightOffset) < windowInnerHeight) {
			var sideHeight = windowInnerHeight - headerOffset - 16;
		} else {
			var sideHeight = rightOffset - 16;
		}
		
		if (Browser.Engine.trident)
			sideHeight -= 2;
		
		$('sidemenu').style.height = sideHeight + 'px';
	}
	if (f($('innercontent'))) {
		var inHeight = (windowInnerHeight - headerOffset - 33);
		
		if (Browser.Engine.trident)
			inHeight -= 4;
		
		$('innercontent').style.minHeight = inHeight + 'px';
	}
	
	// redraw page - for safari
	if (Browser.Engine.webkit) {
		var contentBox = $('content');
		var contentHeight = contentBox.offsetHeight;
		contentBox.style.height = (contentHeight - 1) + "px";
		contentBox.style.height = "";
	}
}

function startGrid() {
	if (f(sb.grid)) {
		sb.grid.addEvent("scroll", maintainScrollPos);
		var columns = $ES(".columnresizer", sb.gridHeader);
		var impotent = sb.gridHeader.className.indexOf("impotent");
		
		//setup the js event handlers
		if (impotent == -1 && columns.length > 0) {
			for (var i=0; i<columns.length; i++) {
				columns[i].addEvent("mousedown", startColumnResize);
			}
		}
	}
}

function maintainScrollPos() {
	sb.gridHeader.scrollLeft = sb.grid.scrollLeft;
	if (sb.leftChecks)
		sb.leftChecks.scrollTop = sb.grid.scrollTop;
}

function getSubMenuId(db, table) {	
	if (f(db) && f(table))
		return sb.submenuIds[db + "_" + table];
	else if (f(db))
		return sb.submenuIds[db];
	else if (sb.page == "query.php")
		return "sidequery";
	else if (sb.page == "users.php")
		return "sideusers";
	else if (sb.page == "import.php")
		return "sideimport";
	else if (sb.page == "export.php")
		return "sideexport";
	else
		return "sidehome";
}

function updateFieldName(inputElem) {
	var fancy = inputElem;
	while (fancy.className.indexOf("fieldbox") == -1) {
		fancy = fancy.parentNode;
	}
	
	var fieldSummary = getFieldSummary(fancy, true);
	
	if (f(fieldSummary) == "")
		fieldSummary = "&lt;" + gettext("New field") + "&gt;";
	
	$E(".fieldheader span", fancy).set('html', fieldSummary);
}

function getFieldSummary(elem, withFormatting) {
	var name, type, values, size, key, defaultval, charset, auto, unsign, binary, notnull, unique, headerBuild;
	var fieldBuild;
	if (f(elem)) {
		var inputs = elem.getElementsByTagName("input");
		for (var inp = 0; inp < inputs.length; inp++) {
			if (inputs[inp].name == "NAME")
				name = inputs[inp].value;
			if (inputs[inp].name == "VALUES")
				values = inputs[inp].value;
			if (inputs[inp].name == "SIZE")
				size = inputs[inp].value;
			if (inputs[inp].name == "DEFAULT")
				defaultval = inputs[inp].value;
			if (inputs[inp].name == "UNSIGN")
				unsign = inputs[inp].checked;
			if (inputs[inp].name == "BINARY")
				binary = inputs[inp].checked;
			if (inputs[inp].name == "AUTO")
				auto = inputs[inp].checked;
			if (inputs[inp].name == "NOTNULL")
				notnull = inputs[inp].checked;
			if (inputs[inp].name == "UNIQUE")
				unique = inputs[inp].checked;
		}
		
		var selects = elem.getElementsByTagName("select");
		for (sel = 0; sel < selects.length; sel++) {
			if (selects[sel].name == "TYPE")
				type = selects[sel].options[selects[sel].selectedIndex].value;
			if (selects[sel].name == "KEY")
				key = selects[sel].options[selects[sel].selectedIndex].value;
			if (selects[sel].name == "CHARSET")
				charset = selects[sel].options[selects[sel].selectedIndex].value;
		}
		
		if (f(name) != "") {
			if (withFormatting)
				fieldBuild = "<span style=\"color: steelblue\">" + name + "</span>";
			else if (adapter == "sqlite")
				fieldBuild = name;
			else
				fieldBuild = "`" + name + "`";
			
			if (adapter == "sqlite") {
				if (f(type))
					fieldBuild += " " + type;
				if (f(size) && f(type))
					fieldBuild += "(" + size + ")";
				if (f(notnull))
					fieldBuild += " not null";
				if (f(key))
					fieldBuild += " " + key + " key";
				if (f(auto))
					fieldBuild += " autoincrement";
				if (f(unique))
					fieldBuild += " unique";
				if (f(defaultval))
					fieldBuild += " default '" + defaultval + "'";
			} else {
				if (f(type))
					fieldBuild += " " + type;
				if (f(values) && (type == "set" || type == "enum"))
					fieldBuild += values + "";
				if (f(size) && f(type))
					fieldBuild += "(" + size + ")";
				if (f(unsign))
					fieldBuild += " unsigned";
				if (f(binary))
					fieldBuild += " binary";
				if (f(charset))
					fieldBuild += " charset " + charset;
				if (f(notnull))
					fieldBuild += " not null";
				if (f(defaultval))
					fieldBuild += " default '" + defaultval + "'";
				if (f(auto))
					fieldBuild += " auto_increment";
				if (f(key))
					fieldBuild += " " + key + " key";
			}
			
		}
	}
	return fieldBuild;
}

function addTableField() {
	var fieldList = $('fieldlist');
	var toCopy = $E('.fieldbox', fieldList).innerHTML;
	
	if (f(toCopy)) {
		var newField =  new Element('div');
		newField.set('html', toCopy);
		newField.className = "fieldbox";
		fieldList.appendChild(newField);
		
		clearForm(newField);
		
		var valueLine = $E(".valueline", newField);
		if (f(valueLine))
			valueLine.style.display = 'none';
		
		if (!Browser.Engine.trident) {
			var newHeader = $E(".fieldheader", newField).childNodes[1];
			newHeader.set('html', '&lt;' + gettext("New field") + '&gt;');
		}
		
	}
	sizePage();
}

function clearForm(elem) {
	var inputs = elem.getElementsByTagName("input");
	for (var i=0; i<inputs.length; i++) {
		if (inputs[i].type == "text")
			inputs[i].value = '';
		else if (inputs[i].type == "checkbox")
			inputs[i].checked = false;
	}
	var selects = elem.getElementsByTagName("select");
	for (var i=0; i<selects.length; i++) {
		selects[i].selectedIndex = 0;
	}
}

function createDatabase() {
	var elem = $('DBNAME');
	var dbName = elem.value;
	if (f(dbName)) {
		var createQuery = "CREATE DATABASE `" + dbName + "`";
		
		if ($('DBCHARSET')) {
			var charset = $('DBCHARSET').value;
			if (charset != "")
				createQuery += " CHARSET " + charset;
		}
		var x = new XHR({url: "ajaxquery.php", onSuccess: createDatabaseCallback}).send("query=" + createQuery + "&silent=1");
	}
	
	function createDatabaseCallback() {
		addMenuItem(dbName);
		databaseLoad(dbName);
	}
}

function createTable() {
	var tableName = $('TABLENAME').value;
	var fields = $ES(".fieldbox", $('fieldlist'));
	if (f(tableName) && fields.length > 0) {
		
		$('TABLENAME').style.border = "";
		
		if (adapter == "sqlite") {
			var createQuery = "CREATE TABLE " + tableName + " (";
		} else {
			var createQuery = "CREATE TABLE `" + tableName + "` (";
		}
		
		for (var i=0; i<fields.length; i++) {
			createQuery += getFieldSummary(fields[i]) + ", ";
		}
		createQuery = createQuery.substring(0, createQuery.length-2);
		createQuery += ")";
		
		if ($('TABLECHARSET')) {
			var charset = $('TABLECHARSET').value;
			if (charset != "")
				createQuery += " CHARSET " + charset;
		}
		
		var x = new XHR({url: "ajaxcreatetable.php", onSuccess: createTableCallback}).send("table=" + tableName + "&query=" + createQuery);
	}
	else if (!(f(tableName))) {
		$('TABLENAME').style.border = "1px solid rgb(200, 125, 125)";
	}
	
	function createTableCallback(response) {
		if (response != "") {
			$('reporterror').style.display = '';
			$('reporterror').innerHTML = response;
		} else {
			var submenu = getSubMenuId(sb.db).substring(2);
			submenu = "sublist" + submenu;
			sb.tableRowCounts[sb.db + "_" + tableName] = 0;
			addSubMenuItem(submenu, sb.db, tableName);
			subTabLoad(sb.db, tableName);
			window.scrollTo(0, 0);
		}
	}
}

function removeField(elem) {
	while (elem.className != "fieldbox") {
		elem = elem.parentNode;
	}
	if (f(elem))
		elem.dispose();
	sizePage();
}

function submitAddColumn() {
	var newColumn = getFieldSummary($('newfield'));
	
	if (adapter == "mysql") {
		var position = $('INSERTPOS').options[$('INSERTPOS').selectedIndex].value;
		var columnQuery = "ALTER TABLE `" + sb.table + "` ADD " + newColumn + position;
	} else if (adapter == "sqlite") {
		var columnQuery = "ALTER TABLE '" + sb.table + "' ADD " + newColumn;
	}
	
	var x = new XHR({url: sb.page, onSuccess: finishTabLoad}).send("runQuery=" + columnQuery);
}

function toggleVisibility(id) {
	id = $(id);
	if (id.style.display == "none")
		id.style.display = "block";
	else
		id.style.display = "none";
	sizePage();
}

function approximateNumber(num) {
	if (isNaN(num) || num == "NaN")
		num = 0;
	
	if (num < 10000)
		return num;
	else if (num < 1000000)
		return Math.floor(num / 1000) + "K";
	else if (num < 100000000)
		return Math.floor((num / 1000000) * 10) / 10 + "M";
	else
		return Math.floor(num / 1000000) + "M";
}

function refreshRowCount() {
	if (f(sb.db) && f(sb.table)) {
		if (adapter == "sqlite")
			var countQuery = "SELECT COUNT(*) AS 'RowCount' FROM '" + sb.table + "'";
		else
			var countQuery = "SELECT COUNT(*) AS `RowCount` FROM `" + sb.table + "`";
		
		var x = new XHR({url: "ajaxquery.php", onSuccess: updateRowCount, showLoader: false}).send("query=" + countQuery);
	}
}

function updateRowCount(responseText) {
	var updatedCount = parseInt(responseText);
	
	sb.tableRowCounts[sb.db + "_" + sb.table] = updatedCount;
	sb.refreshTopTabSet();
	
	var sideA = $(getSubMenuId(sb.db, sb.table));
	var counter = $E(".subcount", sideA);
	counter.set('text', "(" + approximateNumber(updatedCount) + ")");
}

function updatePane(toCheck, pane1, pane2, fromTimeout) {
	if ($(toCheck).checked) {
		$(pane1).style.display = '';
		if ($(pane2))
			$(pane2).style.display = 'none';
	} else {
		$(pane1).style.display = 'none';
		if ($(pane2))
			$(pane2).style.display = '';
	}
	sizePage();
		
	//ie is retarded, duh
	if (Browser.Engine.trident && !fromTimeout)
		setTimeout("updatePane('" + toCheck + "','" + pane1 + "','" + pane2 + "', true)", 100);
}

function toggleValuesLine(obj, box) {
	if (box) {
		box = $(box);
	} else {
		box = obj;
		while (!box.hasClass("overview") && box.parentNode) {
			box = box.parentNode;
		}
	}
	
	var valueLine = $E(".valueline", box);
	
	if (obj.value == "enum" || obj.value == "set")
		valueLine.style.display = '';
	else
		valueLine.style.display = 'none';
	
	var charsetToggle = $ES(".charsetToggle", box);
	
	if (charsetToggle) {
		if (obj.value.indexOf("char") >= 0 || obj.value.indexOf("text") >= 0 || obj.value == "enum" || obj.value == "set") {
			charsetToggle[0].style.display = '';
			charsetToggle[1].style.display = '';
		} else {
			charsetToggle[0].style.display = 'none';
			charsetToggle[1].style.display = 'none';
		}
	}
	
	sizePage();
}

function exportFilePrep() {
	var oft = $('OUTPUTFILETEXT');
	if ($('OUTPUTFILE').checked) {
		
		defaultFilename = gettext("Export").toLowerCase();
		
		if ($('SQLTOGGLE').checked) {
			if (oft.value == "" || oft.value == defaultFilename + ".csv")
				oft.value =  defaultFilename + ".sql";
			oft.focus();
		} else {
			if (oft.value == "" || oft.value == defaultFilename + ".sql")
				oft.value = defaultFilename + ".csv";
			oft.focus();
		}	
	}
}

function startImport() {
	$('importLoad').style.display = '';
	$('importForm').setAttribute("target", "importFrame");
}

function updateAfterImport(message) {
	$('importLoad').style.display = 'none';
	$('importMessage').style.display = '';
	$('importMessage').innerHTML = message;
	clearPanesOnLoad = true;
}

function paneCheckAll(elemId) {
	var elem = $(elemId);
	var inputs = elem.getElementsByTagName("input");
	for (var i=0; i<inputs.length; i++) {
		if (inputs[i].type == "checkbox")
			inputs[i].checked = true;
	}
}

function paneCheckNone(elemId) {
	var elem = $(elemId);
	var inputs = elem.getElementsByTagName("input");
	for (var i=0; i<inputs.length; i++) {
		if (inputs[i].type == "checkbox")
			inputs[i].checked = false;
	}
}

function selectAll(elemId) {
	var elem = $(elemId);
	for (var i=0; i<elem.options.length; i++) {
		elem.options[i].selected = "selected";
	}
	elem.focus();
}

function selectNone(elemId) {
	var elem = $(elemId);
	for (var i=0; i<elem.options.length; i++) {
		elem.options[i].selected = false;
	}
}

function focusWindow(e) {
	var event = new Event(e);
	var targ = event.target;
	
	while (targ && targ.className.indexOf("fulltextwin") == -1) {
		targ = targ.parentNode;
	}
	
	if (targ) {
		targ.style.zIndex = sb.$GUID++;
	}
}

function closeWindow(winId) {
	var opacities = [0.1, 0.4, 0.7];
	closeWindowCallback(winId, 20, opacities);
}

function closeWindowCallback(winId, speed, opacities) {
	var win = $(winId);
	if (opacities.length > 0) {
		win.style.opacity = opacities.pop();
		var nextWin = function(){ closeWindowCallback(winId, speed, opacities); };
		nextWin.delay(speed);
	} else {
		win.dispose();
	}
}

function switchLanguage() {
	var langSelect = $('langSwitcher');
	var lang = langSelect.options[langSelect.selectedIndex].value;
	var defaultLang = "en_US";
	
	if (lang != defaultLang) {
		var co = Cookie.write("sb_lang", lang, {duration: 60});
	} else if (Cookie.read("sb_lang")) {
		Cookie.dispose("sb_lang");
	}
	location.reload(true);
}

function switchTheme() {
	var themeSelect = $('themeSwitcher');
	var theme = themeSelect.options[themeSelect.selectedIndex].value;
	var defaultTheme = "bittersweet";
	
	if (theme != defaultTheme) {
		var co = Cookie.write("sb_theme", theme, {duration: 60});
	} else if (Cookie.read("sb_theme")) {
		Cookie.dispose("sb_theme");
	}
	location.reload(true);
}

function quoteModifier(mod) {
	return returnQuote() + mod + returnQuote();
}

function returnQuote() {
	if (adapter == "sqlite") {
		return "'";
	} else if (adapter == "mysql") {
		return "`";
	}
}

function autoExpandTextareas() {
	var taList = document.getElementsByTagName("textarea");
	if (taList.length > 0 && sb.page != "export.php") {
		var sizeDiv = new Element('div');
		sizeDiv.id = "sizeDiv";
		sizeDiv.style.visibility = "hidden";
		sizeDiv.style.position = "absolute";
		sizeDiv.style.lineHeight = "15px";
		sizeDiv.style.fontSize = "13px";
		sizeDiv.style.padding = "2px";
		document.body.appendChild(sizeDiv);
		
		for (var i=0; i<taList.length; i++) {
			var theDiv = $("sizeDiv");
			theDiv.style.width = taList[i].clientWidth + "px";
			theDiv.set('html', taList[i].value.replace(/\n/g,'<br />') + '&nbsp;');
			
			var newHeight = theDiv.clientHeight + 5;
			
			if (newHeight < 80) {
				newHeight = 80;
			} else if (newHeight > 300) {
				newHeight = 300;
			}
			
			taList[i].style.height = newHeight + "px";
		}
		
		document.body.removeChild(sizeDiv);
	}
}

function yellowFade(el, curr) {	
	if (!curr)
		curr = 175;
	
	el.style.background = 'rgb(255, 255, '+ (curr+=3) +')';
	
	if (curr < 255)
			setTimeout(function(){ yellowFade(el, curr) }, 25);
}