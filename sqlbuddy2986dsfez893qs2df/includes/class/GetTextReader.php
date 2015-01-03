<?php
/*

SQL Buddy - Web based MySQL administration
http://www.sqlbuddy.com/

gettextreader.php
- translation file reader

MIT license

2008 Calvin Lough <http://calv.in>

*/

class GetTextReader {
	
	var $translationIndex = array();
	var $basePath = "locale/";
	
	function GetTextReader($inputFile) {
		
		$msgId = "";
		$msgIdPlural = "";
		$msgStr = "";
		$msgStrPlural = "";
		
		$readFile = $this->basePath . $inputFile;
		
		if (file_exists($readFile)) {
			$handle = fopen($readFile, "r");
			if ($handle) {
				while (!feof($handle)) 
				{
				   $lines[] = trim(fgets($handle, 4096));
				}
				fclose($handle);
			}
			
			foreach ($lines as $line) {
				if (substr($line, 0, 6) == "msgid:") {
					$msgId = substr($line, 8, -1);
					$msgStr = "";
				} else if (substr($line, 0, 13) == "msgid_plural:") {
					$msgIdPlural = substr($line, 15, -1);
				} else if (substr($line, 0, 7) == "msgstr:") {
					$msgStr = substr($line, 9, -1);
				} else if (substr($line, 0, 10) == "msgstr[0]:") {
					$msgStr = substr($line, 12, -1);
				} else if (substr($line, 0, 10) == "msgstr[1]:") {
					$msgStrPlural = substr($line, 12, -1);
				}
				
				if ($msgId && $msgStr) {
					$this->translationIndex[$msgId] = $msgStr;
					if ($msgIdPlural)
						$this->translationIndex[$msgIdPlural] = $msgStrPlural;
					
					$msgId = "";
					$msgIdPlural = "";
					$msgStr = "";
					$msgStrPlural = "";
				}
			}
		}
	}
	
	function getTranslation($lookup) {
		if (array_key_exists($lookup, $this->translationIndex)) {
			return $this->translationIndex[$lookup];
		} else {
			return $lookup;
		}
	}
	
}

?>