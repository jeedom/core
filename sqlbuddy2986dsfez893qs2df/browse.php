<?php
/*

SQL Buddy - Web based MySQL administration
http://www.sqlbuddy.com/

browse.php
- browse table - just grabs variables and passes them to include_browse.php

MIT license

2008 Calvin Lough <http://calv.in>

*/

include "functions.php";

loginCheck();

requireDatabaseAndTableBeDefined();

if (isset($db))
	$conn->selectDB($db);

//run delete queries

if (isset($_POST['runQuery'])) {
	$runQuery = $_POST['runQuery'];
	
	$queryList = splitQueryText($runQuery);
	foreach ($queryList as $query) {
		$conn->query($query);
	}
}

if ($conn->getAdapter() == "sqlite") {
	$query = "SELECT * FROM '$table'";
} else {
	$query = "SELECT * FROM `$table`";
}

$queryTable = $table;

if (isset($_POST['s']))
	$start = (int)($_POST['s']);
else
	$start = 0;

if (isset($_POST['sortKey']))
	$sortKey = $_POST['sortKey'];

if (isset($_POST['sortDir']))
	$sortDir = $_POST['sortDir'];
else if (isset($sortKey))
	$sortDir = "ASC";

if (isset($_POST['view']) && $_POST['view'] == "1")
	$view = 1;
else
	$view = 0;

if (isset($sortKey) && $sortKey != "" && isset($sortDir) && $sortDir != "") {
	$sort = "ORDER BY `" . $sortKey . "` " . $sortDir;
} else {
	$sort = "";
}

require "includes/browse.php";

?>