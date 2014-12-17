<?php
/*

SQL Buddy - Web based MySQL administration
http://www.sqlbuddy.com/

ajaxfulltext.php
- fetches full text for browse tab

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

if ($conn->getAdapter() == "mysql") {
	$structureSql = $conn->describeTable($table);
	
	while ($structureRow = $conn->fetchAssoc($structureSql)) {
		$types[$structureRow['Field']] = $structureRow['Type'];
	}
}

if ($conn->isResultSet($sql)) {
	
	$row = $conn->fetchAssoc($sql);
	
	foreach ($row as $key => $value) {
		echo "<div class=\"fulltexttitle\">" . $key . "</div>";
		echo "<div class=\"fulltextbody\">";
		
		$curtype = $types[$key];
		
		if (strpos(" ", $curtype) > 0) {
			$curtype = substr($curtype, 0, strpos(" ", $curtype));
		}
		
		if ($value && isset($binaryDTs) && in_array($curtype, $binaryDTs)) {
			echo '<span class="binary">(' . __("binary data") . ')</span>';
		} else {
			echo nl2br(htmlentities($value, ENT_QUOTES, 'UTF-8'));
		}
		
		echo "</div>";
	}
}

?>