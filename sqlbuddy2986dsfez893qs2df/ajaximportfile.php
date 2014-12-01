<?php
/*

SQL Buddy - Web based MySQL administration
http://www.sqlbuddy.com/

ajaximportfile.php
- import from file - called from import.php

MIT license

2008 Calvin Lough <http://calv.in>

*/

include "functions.php";

loginCheck();

if (isset($db))
	$conn->selectDB($db);

function stripCommentLines($in) {
	if (substr($in, 0, 2) == "--")
		$in = '';
	
	return $in;
}

if (isset($_POST) || isset($_FILES)) {
	
	if (isset($_FILES['INPUTFILE']['tmp_name']))
		$file = $_FILES['INPUTFILE']['tmp_name'];
	
	if (isset($_POST['FORMAT']))
		$format = $_POST['FORMAT'];
	
	if (!(isset($format) && $format == "CSV"))
		$format = "SQL";
	
	if (isset($_POST['IGNOREFIRST']))
		$ignoreFirst = $_POST['IGNOREFIRST'];
	
	$first = true;
	
	// for csv
	if (isset($format) && $format == "CSV" && isset($table)) {
		$columnCount = 0;
		
		$structureSQL = $conn->describeTable($table);
		
		if ($conn->isResultSet($structureSQL)) {
			while ($structureRow = $conn->fetchAssoc($structureSQL)) {
				$columnCount++;
			}
		}
	}
	
	$insertCount = 0;
	$skipCount = 0;
	
	if (isset($file) && is_uploaded_file($file)) {
		if (isset($format) && $format == "SQL") {
			$lines = file($file);
			
			// the file() function doesn't handle mac line endings correctly
			if (sizeof($lines) == 1 && strpos($lines[0], "\r") > 0) {
				$lines = explode("\r", $lines[0]);
			}
			
			$commentFree = array_map("stripCommentLines", $lines);
			
			$contents = trim(implode('', $commentFree));
			
			$statements = splitQueryText($contents);
		} else {
			$statements = file($file);
			
			// see previous comment
			if (sizeof($statements) == 1 && strpos($statements[0], "\r") > 0) {
				$statements = explode("\r", $statements[0]);
			}
		}
		
		foreach ($statements as $statement) {
			$statement = trim($statement);
			
			if ($statement) {
				if (isset($format) && $format == "SQL") {
					$importQuery = $conn->query($statement) or ($dbErrors[] = $conn->error());
					
					$affected = (int)($conn->affectedRows($importQuery));
					$insertCount += $affected;
				} else if (isset($format) && $format == "CSV" && isset($table)) {
					if (!(isset($ignoreFirst) && $first)) {
						preg_match_all('/"(([^"]|"")*)"/i', $statement, $matches);
						
						$rawValues = $matches[1];
						
						for ($i=0; $i<sizeof($rawValues); $i++) {
							$rawValues[$i] = str_replace('""', '"', $rawValues[$i]);
							$rawValues[$i] = $conn->escapeString($rawValues[$i]);
						}
						
						$values = implode("','", $rawValues);
						
						// make sure that the counts match up
						if (sizeof($rawValues) == $columnCount) {
							
							if ($conn->getAdapter() == "sqlite")
								$importQuery = $conn->query("INSERT INTO '$table' VALUES ('$values')") or ($dbErrors[] = $conn->error());
							else
								$importQuery = $conn->query("INSERT INTO `$table` VALUES ('$values')") or ($dbErrors[] = $conn->error());
							
							$affected = (int)($conn->affectedRows($importQuery));
							
							$insertCount += $affected;
						} else {
							$skipCount++;
						}
					}
					$first = false;
				}
			}
		}
	}
	
	$message = "";
	
	if (!isset($statements)) {
		$message .= __("Either the file could not be read or it was empty") . "<br />";
	} else if ($format == "SQL") {	
		$message .= sprintf(__p("%d statement was executed from the file", "%d statements were executed from the file", $insertCount), $insertCount) . ".<br />";
	} else if ($format == "CSV") {
		if (isset($insertCount) && $insertCount > 0) {
			$message .= sprintf(__p("%d row was inserted into the database from the file", "%d rows were inserted into the database from the file", $insertCount), $insertCount) . ".<br />";
		}
		if (isset($skipCount) && $skipCount > 0) {
			$message .= sprintf(__p("%d row had to be skipped because the number of values was incorrect", "%d rows had to be skipped because the number of values was incorrect", $skipCount), $skipCount) . ".<br />";
		}
	}
	
	if (isset($dbErrors)) {
		$message .= __("The following errors were reported") . ":<br />";
		foreach ($dbErrors as $merr) {
			$message .= " - " . $merr . "<br />";
		}
	}
	
	?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/REC-html40/strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" version="-//W3C//DTD XHTML 1.1//EN" xml:lang="en">
<head>
</head>
<body>	
	<script type="text/javascript">
	
	parent.updateAfterImport("<?php echo trim(addslashes(nl2br($message))); ?>");
	parent.refreshRowCount();
	
	</script>
</body>
</html>
	
	<?php
	
}

?>