<?php
/*

SQL Buddy - Web based MySQL administration
http://www.sqlbuddy.com/

include_browse.php
- not called directly, but from browse.php and query.php

MIT license

2008 Calvin Lough <http://calv.in>

*/

$totalRows = 0;
$insertCount = 0;
$queryTime = 0;

$perPage = (isset($sbconfig) && array_key_exists("RowsPerPage", $sbconfig)) ? $sbconfig['RowsPerPage'] : 100;

$displayLimit = 1000;

$query = trim($query);

if ($query) {

	if (!isset($queryTable)) {
		$querySplit = splitQueryText($query);
	} else {
		$querySplit[] = $query;
	}
	
	foreach ($querySplit as $q) {
		$q = trim($q, "\n");
		if ($q != "") {
			if (isset($queryTable)) {
				$totalRows = $conn->tableRowCount($queryTable);
	
				if ($start > $totalRows) {
					$start = 0;
				}
				
				$q = "$q $sort LIMIT $start, $perPage";
			}
			
			$queryStartTime = microtime_float();
			$dataSql = $conn->query($q) or ($dbError[] = $conn->error());
			$queryFinishTime = microtime_float();
			$queryTime += round($queryFinishTime - $queryStartTime, 4);
			
			if ($conn->affectedRows($dataSql)) {
				$insertCount += (int)($conn->affectedRows($dataSql));
			}
		}
	}
		
	if (!isset($queryTable)) {
		$totalRows = (int)($conn->rowCount($dataSql));
		
		// running rowCount on PDO resets the result set
		// so we need to run the query again
		if ($conn->getMethod() == "pdo") {
			$dataSql = $conn->query($q);
		}
	}
	
}

//for the browse tab
if (isset($queryTable) && $conn->getAdapter() == "sqlite") {
	$structure = $conn->describeTable($queryTable);
	
	if (sizeof($structure) > 0) {
		foreach ($structure as $column) {	
			if (strpos($column[1], "primary key") > 0) {
				$primaryKeys[] = $column[0];
			}
		}
	}
} else if (isset($queryTable) && $conn->getAdapter() == "mysql") {
	$structureSql = $conn->describeTable($queryTable);
	
	if ($conn->isResultSet($structureSql)) {
		while ($structureRow = $conn->fetchAssoc($structureSql)) {	
			$explosion = explode("(", $structureRow['Type'], 2);
			
			$tableTypes[] = $explosion[0];
			
			if ($structureRow['Key'] == "PRI") {
				$primaryKeys[] = $structureRow['Field'];
			}
		}
	}
}

echo '<div class="browsetab">';

if (isset($dbError)) {
	echo '<div class="errormessage" style="margin-left: 5px; width: 536px"><strong>' . __("The following errors were reported") . ':</strong>';
	foreach ($dbError as $error) {
		echo $error . "<br />";
	}
	echo '</div>';
} else {
	
	if (isset($totalRows) && $totalRows > 0) {
		
		if (isset($queryTable)) {
			
			echo '<table class="browsenav">';
			echo '<tr>';
			echo '<td class="options">';
			
			if (isset($primaryKeys) && count($primaryKeys)) {
				
				echo __("Select") . ':&nbsp;&nbsp;<a onclick="checkAll()">' . __("All") . '</a>&nbsp;&nbsp;<a onclick="checkNone()">' . __("None") . '</a>';
				echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . __("With selected") . ':&nbsp;&nbsp;<a onclick="editSelectedRows()">' . __("Edit") . '</a>&nbsp;&nbsp;<a onclick="deleteSelectedRows()">' . __("Delete") . '</a>';
				
				echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a onclick="sb.loadPage()">' . __("Refresh") . '</a>';
				
			} else {
				echo '<span style="color: rgb(150, 150, 150)">[' . __("No primary key defined") . ']</span>';
			}
			
			echo '</td>';
			
			echo '<td class="right">';
			
			$totalPages = ceil($totalRows / $perPage);
			$currentPage = floor($start / $perPage) + 1;
			
			if ($currentPage > 1) {
				echo '<a id="firstNav" onclick="browseNav(0,' . $view . ')">' . __("First") . '</a>';
				echo '<a id="prevNav" onclick="browseNav(' . (($currentPage - 2) * $perPage) . ',' . $view . ')">' . __("Prev") . '</a>';
			}
			
			echo '<span class="paginator">';
			
			if ($currentPage == 1) {
				$startPage = 1;
				$finishPage = 3;
				
				if ($finishPage > $totalPages)
					$finishPage = $totalPages;
				
			} else if ($currentPage == $totalPages) {
				$startPage = $totalPages - 2;
				$finishPage = $totalPages;
				
				if ($startPage < 1)
					$startPage = 1;
			} else {
				$startPage = $currentPage - 1;
				$finishPage = $currentPage + 1;
			}
			
			if ($startPage != $finishPage) {
				for ($bnav=$startPage; $bnav<=$finishPage; $bnav++) {
					echo '<a';
					
					if ($bnav == $currentPage)
						echo ' class="selected"';
					
					echo ' onclick="browseNav(' . (($bnav - 1) * $perPage) . ',' . $view . ')">' . number_format($bnav) . '</a>';
				}
			}
			
			echo '</span>';
			
			if ($currentPage < $totalPages) {
				echo '<a id="nextNav" onclick="browseNav(' . ($currentPage * $perPage) . ',' . $view . ')">' . __("Next") . '</a>';
				echo '<a id="lastNav" onclick="browseNav(' . (($totalPages - 1) * $perPage) . ',' . $view . ')">' . __("Last") . '</a>';
			}
			
			echo '</td>';
			echo '</tr>';
			echo '</table>';
			
		} else {
			echo '<table class="browsenav">';
			echo '<tr>';
			echo '<td class="options">';
			
			printf(__p("Your query returned %d result.", "Your query returned %d results.", $totalRows), $totalRows);
			echo " " . sprintf(__("(%.4f seconds)"), $queryTime);
			
			if ($totalRows > $displayLimit)
				echo ' (' . sprintf(__("Note: To avoid crashing your browser, only the first %d results have been displayed"), $displayLimit) . '.)';
			echo '</td>';
			echo '</tr>';
			echo '</table>';
		}
		
		echo '<div class="grid">';
		
		if (isset($primaryKeys) && count($primaryKeys)) {
			echo '<div class="emptyvoid" style="width: 30px">&nbsp;</div>';
		}
		
		echo '<div class="gridheader';
		
		if (!isset($queryTable))
			echo ' nosort';
		
		echo '">';
		
		echo '<div class="gridheaderinner">';
		echo '<table cellpadding="0" cellspacing="0">';
		echo '<tr>';
			
		if ($conn->isResultSet($dataSql)) {
			$dataRow = $conn->fetchAssoc($dataSql);
			$g = 0;
			$numFields = 0;
			
			foreach ($dataRow as $key=>$value) {
				
				if ((isset($sortKey) && $sortKey == $key) && (isset($sortDir) && $sortDir == "ASC")) {
					$outputDir = "DESC";
				} elseif (isset($sortKey) && $sortKey == $key) {
					$outputDir = "ASC";
				} elseif (isset($sortDir) && $sortDir) {
					$outputDir = $sortDir;
				} else {
					$outputDir = "ASC";
				}
				echo '<td><div column="' . ++$g . '" class="headertitle column' . $g;
				if (isset($sortKey) && $sortKey == $key) {
					echo ' sort';
				}
				
				if (isset($tableTypes) && in_array($tableTypes[$g - 1], $textDTs)) {
					echo ' longtext';
				}
				
				if (isset($tableTypes) && in_array($tableTypes[$g - 1], $numericDTs)) {
					echo ' numeric';
				}
				
				echo '"';
				
				if (isset($queryTable))
					echo ' onclick="loadNewSort(\'' . $key . '\', \'' . $outputDir . '\')"';
				
				echo '>';
				
				if ((isset($sortKey) && $sortKey == $key) && (isset($sortDir) && $sortDir == "DESC")) {
					echo '<div class="sortdesc">' . $key . '</div>';
				} elseif ((isset($sortKey) && $sortKey == $key) && (isset($sortDir) && $sortDir == "ASC")) {
					echo '<div class="sortasc">' . $key . '</div>';
				} else {
					echo $key;
				}
				
				$fieldList[] = $key;
				
				echo '</div>';
				echo '</td>';
				echo '<td><div class="columnresizer"></div></td>';
				$numFields++;
			}
			echo '<td><div class="emptyvoid" style="width: 30px; border-right: 0">&nbsp;</div></td>';
			echo '</tr>';
			echo '</table>';
						
		}
		
		echo '</div>';
		echo '</div>';
		
		$dataSql = $conn->query($q);
		
		$queryBuilder = "";
		
		if (isset($primaryKeys) && count($primaryKeys) > 0) {
			
			echo '<div class="leftchecks">';
			
			$m = 0;
			
			while (($dataRow = $conn->fetchAssoc($dataSql)) && ($m < $displayLimit)) {
				
				$queryBuilder = "WHERE ";
				foreach ($primaryKeys as $primary) {
					if ($conn->getAdapter() == "sqlite") {
						$queryBuilder .= "" . $primary . "='" . $dataRow[$primary] . "' AND ";
					} else {
						$queryBuilder .= "`" . $primary . "`='" . $dataRow[$primary] . "' AND ";
					}
				}
				$queryBuilder = substr($queryBuilder, 0, -5);
				
				if ($conn->getAdapter() == "mysql") {
					$queryBuilder .= " LIMIT 1";
				}
				
				echo '<dl class="manip';
				
				if ($m % 2 == 1)
					echo ' alternator';
				else 
					echo ' alternator2';
				
				echo '">';
				echo '<dt><input type="checkbox" class="check' . $m . '" onclick="rowClicked(' . $m . ')" querybuilder="' . $queryBuilder . '" /></dt>';
				echo '<dd><a onclick="fullTextWindow(' . $m . ')"></a></dd>';
				echo '</dl>';
				
				$m++;
			}
			
			echo '</div>';
			
			$dataSql = $conn->query($q);
			
		}
		
		if (isset($primaryKeys) && count($primaryKeys))
			echo '<div class="gridscroll withinfo">';
		else
			echo '<div class="gridscroll">';
		
		$m = 0;
		
		while (($dataRow = $conn->fetchArray($dataSql)) && ($m < $displayLimit)) {
			
			echo '<table cellpadding="0" cellspacing="0" class="row' .($m). ' browse';
			
			if ($m % 2 == 1)
				echo ' alternator';
			else 
				echo ' alternator2';
			
			echo '">';
			echo '<tr>';
			echo '<td>';
			
			echo '<table cellpadding="0" cellspacing="0">';
			echo '<tr>';
			
			for ($i=0; $i<$numFields; $i++) {
				echo '<td><div class="item column' . ($i + 1);
				if (isset($tableTypes) && in_array($tableTypes[$i], $textDTs)) {
					echo ' longtext';
				}
				if (isset($tableTypes) && in_array($tableTypes[$i], $numericDTs)) {
					echo ' numeric';
				}
				echo '" fieldname="' . $fieldList[$i] . '">';
				
				if (isset($tableTypes) && in_array($tableTypes[$i], $binaryDTs)) {
					echo '<span class="binary">(' . __("binary data") . ')</span>';
				} else if (is_numeric($dataRow[$i]) && stristr($fieldList[$i], "Date") !== false && strlen($dataRow[$i]) > 7 && strlen($dataRow[$i]) < 14) {
					echo '<span title="' . date("F j, Y g:ia", $dataRow[$i]) . '">' . formatForOutput($dataRow[$i]) . '</span>';
				} else {
					echo formatForOutput($dataRow[$i]);
				}
				
				echo '</div></td>';
			}
			echo '</tr>';
			echo '</table>';
			echo '</td>';
			echo '</tr>';
			echo '</table>';
			
			$m++;
		}
		echo '</div>';
		echo '</div>';
		
		?>
		
		<script type="text/javascript" authkey="<?php echo $requestKey; ?>">
		setTimeout(function(){ startGrid(); }, 1);
		</script>
		
		<?php
	} else {
		if ($insertCount)
			echo '<div class="statusmessage" style="margin: 0 5px 10px">' . sprintf(__("Your query affected %d rows."), $insertCount) . '</div>';
		
		if (isset($queryTable) && $queryTable) {
			?>
			
			<script type="text/javascript" authkey="<?php echo $requestKey; ?>">
			
			topTabLoad(1);
			
			</script>
			
			<?php
		} else {
			echo '<div class="statusmessage" style="margin-left: 5px">' . __("Your query did not return any results.") . " " . sprintf(__("(%.4f seconds)"), $queryTime) . '</div>';
		}
	}
}

echo '</div>';

?>