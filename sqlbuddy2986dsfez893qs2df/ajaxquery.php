<?php
/*

SQL Buddy - Web based MySQL administration
http://www.sqlbuddy.com/

ajaxquery.php
- used for a variety of ajax functionality - runs a background query

MIT license

2008 Calvin Lough <http://calv.in>

*/

include "functions.php";

loginCheck();

if (isset($db))
	$conn->selectDB($db);

if (isset($_POST['query'])) {
	$queryList = splitQueryText($_POST['query']);
	
	foreach ($queryList as $query) {
		$sql = $conn->query($query);
	}
}

//return the first field from the first row
if (!isset($_POST['silent']) && $conn->isResultSet($sql)) {
	$row = $conn->fetchArray($sql);
	echo nl2br(htmlentities($row[0], ENT_QUOTES, 'UTF-8'));
}

?>