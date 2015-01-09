<?php
/*

SQL Buddy - Web based MySQL administration
http://www.sqlbuddy.com/

import.php
- import from file

MIT license

2008 Calvin Lough <http://calv.in>

*/

include "functions.php";

loginCheck();

?>

<div class="import">
	
	<div id="importMessage" style="display: none; margin-bottom: 11px"></div>
	
	<h4><?php echo __("Import"); ?></h4>
	
	<form id="importForm" onsubmit="startImport()" action="ajaximportfile.php?db=<?php if (isset($db)) echo $db; ?>&table=<?php if (isset($table)) echo $table; ?>&ajaxRequest=1&requestKey=<?php echo $requestKey; ?>" method="post" enctype="multipart/form-data">
	<table cellpadding="0">
	<?php
	
	if (!isset($table)) {
	?>
	<tr>
		<td class="secondaryheader" colspan="2"><?php echo __("Choose a .sql file to import"); ?>.</td>
	</tr>
	<?php
	}
	
	?>
	<tr>
		<td class="secondaryheader"><?php echo __("File"); ?>:</td>
		<td>
			<input type="file" name="INPUTFILE" />
		</td>
	</tr>
	<?php
	
	if (isset($table)) {
	?>
	<tr>
		<td class="secondaryheader"><?php echo __("Format"); ?>:</td>
		<td>
			<label><input type="radio" name="FORMAT" value="SQL" onchange="updatePane('CSVTOGGLE', 'icsvpane')" onclick="updatePane('CSVTOGGLE', 'icsvpane')" checked="checked"' /><?php echo __("SQL"); ?></label><br />
			<label><input type="radio" name="FORMAT" id="CSVTOGGLE" value="CSV" onchange="updatePane('CSVTOGGLE', 'icsvpane')" onclick="updatePane('CSVTOGGLE', 'icsvpane')" /><?php echo __("CSV"); ?></label>
		</td>
	</tr>
	<?php
	}
	
	?>
	</table>
	
	<div class="exportseperator"></div>
	
	<table cellpadding="0" id="icsvpane" style="display: none">
	<tr>
		<td class="secondaryheader"><?php echo __("Options"); ?>:</td>
		<td>
			<label><input type="checkbox" name="IGNOREFIRST" value="TRUE" /><?php echo __("Ignore first line"); ?></label><br />
		</td>
	</tr>
	<tr>
		<td colspan="2"><div class="exportseperator"></div></td>
	</tr>
	</table>
	
	<table cellpadding="0">
	<tr>
		<td colspan="2"><input type="submit" class="inputbutton" value="<?php echo __("Submit"); ?>" /><span id="importLoad" style="padding-left: 10px; color: rgb(150, 150, 150); display: none;"><?php echo __("Importing..."); ?></span></td>
	</tr>
	</table>
	
	</form>
	
</div>

<iframe id="importFrame" name="importFrame" src="about:blank" style="display: none; width: 0; height: 0; line-height: 0"></iframe>