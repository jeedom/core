<?php
/*

SQL Buddy - Web based MySQL administration
http://www.sqlbuddy.com/

structure.php
- view/edit the structure of a table

MIT license

2008 Calvin Lough <http://calv.in>

*/

include "functions.php";

loginCheck();

requireDatabaseAndTableBeDefined();

if (isset($db))
	$conn->selectDB($db);

if (isset($_POST)) {
	
	// process form - add index
	if (isset($_POST['INDEXTYPE']))
		$indexType = $_POST['INDEXTYPE'];
	
	if (isset($_POST['INDEXCOLUMNLIST']))
		$indexColumnList = $_POST['INDEXCOLUMNLIST'];
	
	if (isset($indexType) && isset($indexColumnList) && $indexType && $indexColumnList) {
		$indexColumnList = implode("`, `", $indexColumnList);
		
		$indexQuery = "ALTER TABLE `$table` ADD ";
		
		 if ($indexType == "INDEX")
			$indexQuery .= "INDEX";
		else if ($indexType == "UNIQUE")
			$indexQuery .= "UNIQUE";
		
		$indexQuery .= " (`" . $indexColumnList . "`)";
		
		$conn->query($indexQuery) or ($dbError = $conn->error());
	}
	
	?>
	
	<script type="text/javascript" authkey="<?php echo $requestKey; ?>"	>
	clearPanesOnLoad = true;
	</script>
	
	<?php
	
}

//run delete queries
if (isset($_POST['runQuery'])) {
	$runQuery = $_POST['runQuery'];
	
	$queryList = splitQueryText($runQuery);
	foreach ($queryList as $query) {
		if (trim($query) != "")
			$conn->query($query) or ($dbError = $conn->error());
	}
}

if (isset($dbError)) {
	echo '<div class="errormessage" style="margin: 6px 12px 10px; width: 602px"><strong>' . __("Error performing operation") . '</strong><p>' . $dbError . '</p></div>';
}

$structureSql = $conn->describeTable($table);

if ($conn->getAdapter() == "mysql" && $conn->isResultSet($structureSql)) {

?>

<table cellpadding="0" width="100%" class="structure" style="margin: 2px 7px 7px">
<tr>
<td valign="top" width="575">
	
	<table class="browsenav">
	<tr>
	<td class="options">
	
	<?php
	
	echo '<strong>' . __("Columns") . '</strong>&nbsp;&nbsp;&nbsp;&nbsp;';
	
	echo __("Select") . ':&nbsp;&nbsp;<a onclick="checkAll()">' . __("All") . '</a>&nbsp;&nbsp;<a onclick="checkNone()">' . __("None") . '</a>';
	echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . __("With selected") . ':&nbsp;&nbsp;<a onclick="editSelectedRows()">' . __("Edit") . '</a>&nbsp;&nbsp;<a onclick="deleteSelectedColumns()">' . __("Delete") . '</a>';
	
	?>
	
	</td>
	</tr>
	</table>
	
	<?php
	
	echo '<div class="grid">';
	
	echo '<div class="emptyvoid">&nbsp;</div>';
	
	echo '<div class="gridheader impotent">';
	echo '<div class="gridheaderinner">';
	echo '<table cellpadding="0" cellspacing="0">';
	echo '<tr>';
	echo '<td><div column="1" class="headertitle column1">' . __("Name") . '</div></td>';
	echo '<td><div class="columnresizer"></div></td>';
	echo '<td><div column="2" class="headertitle column2">' . __("Type") . '</div></td>';
	echo '<td><div class="columnresizer"></div></td>';
	echo '<td><div column="3" class="headertitle column3">' . __("Default") . '</div></td>';
	echo '<td><div class="columnresizer"></div></td>';
	echo '</tr>';
	echo '</table>';
	echo '</div>';
	echo '</div>';
	
	echo '<div class="leftchecks" style="max-height: 300px">';
	
	$m = 0;
	
	while ($structureRow = $conn->fetchAssoc($structureSql)) {
		echo '<dl class="manip';
		
		if ($m % 2 == 1)
			echo ' alternator';
		else 
			echo ' alternator2';
		
		echo '"><dt><input type="checkbox" class="check' . $m . '" onclick="rowClicked(' . $m++ . ')" querybuilder="' . $structureRow['Field'] . '" /></dt></dl>';
	}
	
	echo '</div>';
	
	$structureSql = $conn->describeTable($table);
	
	echo '<div class="gridscroll withchecks" style="overflow-x: hidden; max-height: 300px">';
	
	$m = 0;
	
	while ($structureRow = $conn->fetchAssoc($structureSql)) {
		
		echo '<div class="row' . $m . ' browse';
		
		if ($m % 2 == 1) { echo ' alternator'; }
		else 
		{ echo ' alternator2'; }
		
		echo '">';
		echo '<table cellpadding="0" cellspacing="0">';
		echo '<tr>';
		echo '<td><div class="item column1">' . $structureRow['Field'] . '</div></td>';
		echo '<td><div class="item column2">' . $structureRow['Type'] . '</div></td>';
		echo '<td><div class="item column3">' . $structureRow['Default'] . '</div></td>';
		echo '</tr>';
		echo '</table>';
		echo '</div>';
		
		$fieldList[] = $structureRow['Field'];
		
		$m++;
	}
	
	echo '</div>';
	echo '</div>';
	
	?>

	<div id="newfield" class="inputbox">
		<h4><?php echo __("Add a column"); ?></h4>
	
		<form onsubmit="submitAddColumn(); return false">
		<table cellpadding="5">
		<tr>
		<td class="secondaryheader">
		<?php echo __("Name"); ?>:
		</td>
		<td>
		<input type="text" class="text" name="NAME" style="width: 145px" />
		</td>
		<td class="secondaryheader">
		<?php echo __("Type"); ?>:
		</td>
		<td>
		<select name="TYPE" onchange="toggleValuesLine(this, 'newfield')" style="width: 145px">
		<?php
		
		foreach ($typeList as $type) {
			echo '<option value="' . $type . '">' . $type . '</option>';
		}
		
		?>
		</select>
		</td>
		</tr>
		<tr class="valueline" style="display: none">
		<td class="secondaryheader">
		<?php echo __("Values"); ?>:
		</td>
		<td colspan="3" class="inputarea">
		<input type="text" class="text" name="VALUES" style="width: 145px" />
		</td>
		</tr>
		<tr>
		<td class="secondaryheader">
		<?php echo __("Size"); ?>:
		</td>
		<td>
		<input type="text" class="text" name="SIZE" style="width: 145px" />
		</td>
		<td class="secondaryheader">
		<?php echo __("Key"); ?>:
		</td>
		<td>
		<select name="KEY" style="width: 145px">
		<option value=""> - - - - </option>
		<option value="primary"><?php echo __("primary"); ?></option>
		<option value="unique"><?php echo __("unique"); ?></option>
		<option value="index"><?php echo __("index"); ?></option>
		</select>
		</td>
		</tr>
		<tr>
		<td class="secondaryheader">
		<?php echo __("Default"); ?>:
		</td>
		<td>
		<input type="text" class="text" name="DEFAULT" style="width: 145px" />
		</td>
		<?php
		
		if (isset($charsetList)) {
			echo "<td class=\"secondaryheader charsetToggle\">";
			echo __("Charset") . ":";
			echo "</td>";
			echo "<td class=\"inputarea charsetToggle\">";
			echo "<select name=\"CHARSET\" style=\"width: 145px\">";
			echo "<option></option>";
			foreach ($charsetList as $charset) {
				echo "<option value=\"" . $charset . "\">" . $charset . "</option>";
			}
			echo "</select>";
			echo "</td>";
		} else {
			echo "<td></td>";
			echo "<td></td>";
		}
		
		?>
		</tr>
		<tr>
		<td class="secondaryheader">
		<?php echo __("Other"); ?>:
		</td>
		<td colspan="3">
		<label><input type="checkbox" name="UNSIGN"><?php echo __("Unsigned"); ?></label>
		<label><input type="checkbox" name="BINARY"><?php echo __("Binary"); ?></label>
		<label><input type="checkbox" name="NOTNULL"><?php echo __("Not Null"); ?></label>
		<label><input type="checkbox" name="AUTO"><?php echo __("Auto Increment"); ?></label>
		</td>
		</tr>
		<tr>
		<td class="secondaryheader" colspan="3">
		<?php echo __("Insert this column"); ?>:&nbsp;&nbsp;<select id="INSERTPOS">
		<option value=""><?php echo __("At end of table"); ?></option>
		<option value=" FIRST"><?php echo __("At beginning of table"); ?></option>
		<option value=""> - - - - - - - - </option>
		<?php
		for ($i=0; $i<count($fieldList); $i++) {	
			echo '<option value=" AFTER ' . $fieldList[$i] . '">' . __("After") . ' ' . $fieldList[$i] . '</option>';
		}
		?>
		</select>
		</td>
		<td align="right" style="padding-right: 30px">
		<input type="submit" class="inputbutton" value="<?php echo __("Submit"); ?>" />
		</td>
		</tr>
		</table>
		</form>
	</div>
	
	<div class="inputbox" style="width: 235px">
	<h4><?php echo __("Edit table"); ?></h4>
	
	<div id="editTableMessage"></div>
	<form onsubmit="editTable(); return false">
	<table cellpadding="0">
	<tr>
	<td class="secondaryheader">
	<?php echo __("Name"); ?>:
	</td>
	<td class="inputarea">
	<input type="text" class="text" name="RENAME" id="RENAME" value="<?php echo $table; ?>" style="width: 140px" />
	</td>
	</tr>
	<?php
	
	if (isset($charsetList) && isset($collationList)) {
		
		$infoSql = $conn->query("SHOW TABLE STATUS LIKE '$table'");
		
		if ($conn->isResultSet($infoSql) == 1) {
		
		$infoRow = $conn->fetchAssoc($infoSql);
		
		echo "<tr>";
		echo "<td class=\"secondaryheader\">";
		echo __("Charset") . ":";
		echo "</td>";
		echo "<td class=\"inputarea\">";
		echo "<select name=\"CHARSET\" id=\"RECHARSET\" style=\"width: 145px\">";
		echo "<option></option>";
		foreach ($charsetList as $charset) {
			echo "<option value=\"" . $charset . "\"";
			
			if ($collationList[$infoRow['Collation']] == $charset) {
				echo ' selected="selected"';
			}
			
			echo ">" . $charset . "</option>";
		}
		echo "</select>";
		echo "</td>";
		echo "</tr>";
		
		}
	}
	
	echo '<tr>';
	echo '<td></td>';
	echo '<td align="left">';
	echo '<input type="submit" class="inputbutton" value="' . __("Submit") . '" />';
	echo '</td>';
	echo '</tr>';
	
	?>
	</table>
	</form>
	</div>
	
	<?php
	
	$indexListSQL = $conn->query("SHOW INDEX FROM `$table`");
	
	if ($conn->isResultSet($indexListSQL)) {
		
		?>
		
		<div style="width: 440px">
		
		<table class="browsenav" style="margin-top: 15px">
		<tr>
		<td class="options">
		
		<?php
		
		echo '<strong>' . __("Indexes") . '</strong>&nbsp;&nbsp;&nbsp;&nbsp;';
		
		echo __("Select") . ':&nbsp;&nbsp;<a onclick="checkAll(\'grid2\')">' . __("All") . '</a>&nbsp;&nbsp;<a onclick="checkNone(\'grid2\')">' . __("None") . '</a>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . __("With selected") . ':&nbsp;&nbsp;<a onclick="deleteSelectedIndexes(\'grid2\')">' . __("Delete") . '</a>';
		
		?>
		
		</td>
		</tr>
		</table>
		
		<?php
		
		$indexList = array();
		
		while ($indexListRow = $conn->fetchAssoc($indexListSQL)) {	
			if (!array_key_exists($indexListRow['Key_name'], $indexList)) {
				$indexList[$indexListRow['Key_name']] = $indexListRow['Column_name'];
			} else {
				$indexList[$indexListRow['Key_name']] .= ", " . $indexListRow['Column_name'];
			}
		}
		
		echo '<div class="grid" id="grid2">';
	
		echo '<div class="emptyvoid">&nbsp;</div>';
		
		echo '<div class="gridheader impotent">';
		echo '<div class="gridheaderinner">';
		echo '<table cellpadding="0" cellspacing="0">';
		echo '<tr>';
		echo '<td><div column="1" class="headertitle column1">' . __("Key") . '</div></td>';
		echo '<td><div class="columnresizer"></div></td>';
		echo '<td><div column="2" class="headertitle column2">' . __("Columns") . '</div></td>';
		echo '<td><div class="columnresizer"></div></td>';
		echo '<td><div class="emptyvoid" style="width: 15px; border-right: 0"></div></td>';
		echo '</tr>';
		echo '</table>';
		echo '</div>';
		echo '</div>';
		
		echo '<div class="leftchecks" style="max-height: 400px">';
		
		$m = 0;
		
		foreach ($indexList as $keyName => $columns) {
			echo '<dl class="manip';
			
			if ($m % 2 == 1)
				echo ' alternator';
			else 
				echo ' alternator2';
			
			echo '"><dt><input type="checkbox" class="check' . $m . '" onclick="rowClicked(' . $m++ . ', \'grid2\')" querybuilder="' . $keyName . '" /></dt></dl>';
		}
		
		echo '</div>';
		
		echo '<div class="gridscroll withchecks" style="overflow-x: hidden; max-height: 400px">';
		
		$m = 0;
		
		foreach ($indexList as $keyName => $columns) {
			echo '<div class="row' . $m . ' browse';
			
			if ($m % 2 == 1) { echo ' alternator'; }
			else 
			{ echo ' alternator2'; }
			
			echo '">';
			echo '<table cellpadding="0" cellspacing="0">';
			echo '<tr>';
			echo '<td><div class="item column1">' . $keyName . '</div></td>';
			echo '<td><div class="item column2">' . $columns . '</div></td>';
			echo '</tr>';
			echo '</table>';
			echo '</div>';
			
			
			$m++;
		}
		
		echo '</div>';
		echo '</div>';
		
		$m++;
		
	}
	
	?>
		
	<div id="newindex" class="inputbox" style="width: 275px">
	<h4><?php echo __("Add an index"); ?></h4>
	<div class="universalindent">
		<form id="ADDINDEXFORM" onsubmit="submitForm('ADDINDEXFORM'); return false">
		<table cellpadding="4">
		<tr>
			<td class="secondaryheader">
			<?php echo __("Type"); ?>:
			</td>
			<td class="inputarea" valign="top">
			<select name="INDEXTYPE" style="width: 115px">
			<option value="INDEX"><?php echo __("Index"); ?></option>
			<option value="UNIQUE"><?php echo __("Unique"); ?></option>
			</select>
			</td>
		</tr>
		<tr>
			<td class="secondaryheader" style="width: 70px">
			<?php echo __("Column(s)"); ?>:
			</td>
			<td class="inputarea" valign="top">
			<?php
			
			$finish = (count($fieldList) < 5) ? count($fieldList) : 5;
			
			for ($i=0; $i<$finish; $i++) {	
				echo '<label><input type="checkbox" name="INDEXCOLUMNLIST[]" value="' . $fieldList[$i] . '">' . $fieldList[$i] . '</label><br />';
			}
			
			if (count($fieldList) > 5) {
				echo '<a onclick="show(\'columnListFull\'); hide(\'columnListLink\'); return false;" id="columnListLink">' . sprintf(__("Show %d more..."), count($fieldList) - 5) . '</a>';
				echo '<div id="columnListFull" style="display: none">';
				for ($i=5; $i<count($fieldList); $i++) {	
					echo '<label><input type="checkbox" name="INDEXCOLUMNLIST[]" value="' . $fieldList[$i] . '">' . $fieldList[$i] . '</label><br />';
				}
				echo '</div>';
			}
			
			?>
			</td>
		</tr>
		<tr>
			<td></td>
			<td>
			<input type="submit" class="inputbutton" value="<?php echo __("Submit"); ?>" />
			</td>
		</tr>
		</table>
		</form>
	</div>
	</div>
	
</td>
<td valign="top">
	<div style="margin: 4px 0 0 20px; padding-left: 15px; border-left: 1px solid rgb(215, 215, 215)">
	
	<h3><?php echo __("Options"); ?></h3>
	
	<div style="padding: 2px 0 15px">
		<a onclick="confirmEmptyTable()"><?php echo __("Empty table"); ?></a><br />
		<a onclick="confirmDropTable()"><?php echo __("Drop table"); ?></a><br />
		<a onclick="optimizeTable()"><?php echo __("Optimize table"); ?></a>
	</div>
	
	<?php
	
	$infoSql = $conn->query("SHOW TABLE STATUS LIKE '$table'");
	
	if ($conn->isResultSet($infoSql) == 1) {
	
	$infoRow = $conn->fetchAssoc($infoSql);
	
	?>
	
	<h3><?php echo __("Table Information"); ?></h3>
	<dl class="information">
	<?php
	
	$engine = (array_key_exists("Type", $infoRow)) ? $infoRow['Type'] : $infoRow['Engine'];
	
	echo '<dt>' . __("Storage engine") . ':</dt><dd>' . $engine . '</dd>';
	
	if (array_key_exists('Collation', $infoRow) && isset($collationList)) {
		echo '<dt>' . ("Charset") . ':</dt><dd>' . $collationList[$infoRow['Collation']] . '</dd>';
	}
	
	echo '<dt>' . __("Rows") . ':</dt><dd>' . number_format($infoRow['Rows']) . '</dd>';
	echo '<dt>' . __("Size") . ':</dt><dd>' . memoryFormat($infoRow['Data_length']) . '</dd>';
	echo '<dt>' . __("Overhead") . ':</dt><dd>' . memoryFormat($infoRow['Data_free']) . '</dd>';
	echo '<dt>' . __("Auto Increment") . ':</dt><dd>' . number_format($infoRow['Auto_increment']) . '</dd>';
	
	?>
	</dl>
	<div class="clearer"></div>
	
	<?php
	
	}
	
	?>
	
	<script type="text/javascript" authkey="<?php echo $requestKey; ?>">
	setTimeout("startGrid()", 1);
	</script>
	
	</div>
</td>
</tr>
</table>

<?php

} else if ($conn->getAdapter() == "sqlite" && sizeof($structureSql) > 0) {

?>
<table cellpadding="0" width="100%" class="structure" style="margin: 2px 7px 7px">
<tr>
<td valign="top" width="575">
	
	<table class="browsenav">
	<tr>
	<td class="options">
	
	<?php
	
	echo '<strong>' . __("Columns") . '</strong>';
		
	?>
	
	</td>
	</tr>
	</table>
	
	<?php
	
	echo '<div class="grid">';
	
	echo '<div class="gridheader impotent">';
	echo '<div class="gridheaderinner">';
	echo '<table cellpadding="0" cellspacing="0">';
	echo '<tr>';
	echo '<td><div column="1" class="headertitle column1">' . __("Name") . '</div></td>';
	echo '<td><div class="columnresizer"></div></td>';
	echo '<td><div column="2" class="headertitle column2">' . __("Type") . '</div></td>';
	echo '<td><div class="columnresizer"></div></td>';
	echo '</tr>';
	echo '</table>';
	echo '</div>';
	echo '</div>';
	
	echo '<div class="gridscroll" style="overflow-x: hidden; max-height: 300px">';
	
	$m = 0;
	
	foreach ($structureSql as $column) {
		
		echo '<div class="row' . $m . ' browse';
		
		if ($m % 2 == 1) { echo ' alternator'; }
		else 
		{ echo ' alternator2'; }
		
		echo '">';
		echo '<table cellpadding="0" cellspacing="0">';
		echo '<tr>';
		echo '<td><div class="item column1">' . $column[0] . '</div></td>';
		echo '<td><div class="item column2">' . $column[1] . '</div></td>';
		echo '</tr>';
		echo '</table>';
		echo '</div>';
		
		$fieldList[] = $column[0];
		
		$m++;
	}
	
	echo '</div>';
	echo '</div>';
	
	if (version_compare($conn->getVersion(), "3.1.3", ">")) {
	
	?>

	<div id="newfield" class="inputbox">
		<h4><?php echo __("Add a column"); ?></h4>
	
		<form onsubmit="submitAddColumn(); return false">
		<table cellpadding="5">
		<tr>
		<td class="secondaryheader">
		<?php echo __("Name"); ?>:
		</td>
		<td>
		<input type="text" class="text" name="NAME" style="width: 145px" />
		</td>
		<td class="secondaryheader">
		<?php echo __("Type"); ?>:
		</td>
		<td>
		<select name="TYPE" style="width: 150px">
		<option value="">typeless</option>
		<?php
		
		foreach ($sqliteTypeList as $type) {
			echo '<option value="' . $type . '">' . $type . '</option>';
		}
		
		?>
		</select>
		</td>
		</tr>
		<tr>
		<td class="secondaryheader">
		<?php echo __("Size"); ?>:
		</td>
		<td>
		<input type="text" class="text" name="SIZE" style="width: 145px" />
		</td>
		<td class="secondaryheader">
		<?php echo __("Default"); ?>:
		</td>
		<td>
		<input type="text" class="text" name="DEFAULT" style="width: 145px" />
		</td>
		</tr>
		<tr>
		<td class="secondaryheader">
		<?php echo __("Other"); ?>:
		</td>
		<td colspan="3">
		<label><input type="checkbox" name="NOTNULL"><?php echo __("Not Null"); ?></label>
		<label><input type="checkbox" name="UNIQUE"><?php echo __("Unique"); ?></label>
		</td>
		</tr>
		<tr>
		<td colspan="4" align="right" style="padding-right: 30px">
		<input type="submit" class="inputbutton" value="<?php echo __("Submit"); ?>" />
		</td>
		</tr>
		</table>
		</form>
	</div>
	
	<div class="inputbox" style="width: 235px">
	<h4><?php echo __("Edit table"); ?></h4>
	
	<div id="editTableMessage"></div>
	<form onsubmit="editTable(); return false">
	<table cellpadding="0">
	<tr>
	<td class="secondaryheader">
	<?php echo __("Name"); ?>:
	</td>
	<td class="inputarea">
	<input type="text" class="text" name="RENAME" id="RENAME" value="<?php echo $table; ?>" style="width: 140px" />
	</td>
	</tr>
	<tr>
	<td></td>
	<td align="left">
	<input type="submit" class="inputbutton" value="<?php echo __("Submit"); ?>" />
	</td>
	</tr>
	</table>
	</form>
	</div>
	
	<?php
	
	}
	
	?>

</td>	
<td valign="top">
	<div style="margin: 4px 0 0 20px; padding-left: 15px; border-left: 1px solid rgb(215, 215, 215)">
	
	<h3><?php echo __("Options"); ?></h3>
	
	<div style="padding: 2px 0 15px">
		<a onclick="confirmEmptyTable()"><?php echo __("Empty table"); ?></a><br />
		<a onclick="confirmDropTable()"><?php echo __("Drop table"); ?></a><br />
	</div>
	
	<?php
	
	$rowCount = $conn->tableRowCount($table);
	
	?>
	
	<h3><?php echo __("Table Information"); ?></h3>
	<dl class="information">
	<?php
	
	echo '<dt>' . __("Rows") . ':</dt><dd>' . number_format($rowCount) . '</dd>';
		
	?>
	</dl>
	<div class="clearer"></div>
	
	<script type="text/javascript" authkey="<?php echo $requestKey; ?>">
	setTimeout("startGrid()", 1);
	</script>
	
	</div>
</td>
</tr>
</table>

<?php

} else {
	?>
	
	<div class="errorpage">
	<h4><?php echo __("Oops"); ?></h4>
	<p><?php printf(__('There was a bit of trouble locating the "%s" table.'), $table); ?></p>
	</div>
	
	<?php
}

?>