<?php
/*

SQL Buddy - Web based MySQL administration
http://www.sqlbuddy.com/

sql-php4.php
- sql class (php4 compatible)

MIT license

2008 Calvin Lough <http://calv.in>

*/

class SQL {
	
	var $adapter = "";
	var $method = "";
	var $version = "";
	var $conn = "";
	var $options = "";
	var $errorMessage = "";
	var $db = "";
	
	function SQL($connString, $user = "", $pass = "") {
		list($this->adapter, $options) = explode(":", $connString, 2);
		
		if ($this->adapter != "sqlite") {
			$this->adapter = "mysql";
		}
		
		$optionsList = explode(";", $options);
		
		foreach ($optionsList as $option) {
			list($a, $b) = explode("=", $option);
			$opt[$a] = $b;
		}
		
		$this->options = $opt;
		$database = (array_key_exists("database", $opt)) ? $opt['database'] : "";
		
		if ($this->adapter == "sqlite") {
			$this->method = "sqlite";
			$this->conn = sqlite_open($database, 0666, $sqliteError);
		} else {
			$this->method = "mysql";
			$host = (array_key_exists("host", $opt)) ? $opt['host'] : "";
			$this->conn = @mysql_connect($host, $user, $pass);
		}
		
		if ($this->conn && $this->adapter == "mysql") {
			$this->query("SET NAMES 'utf8'");
		}
	}
	
	function isConnected() {
		return ($this->conn !== false);
	}
	
	function disconnect() {
		if ($this->conn) {
			if ($this->method == "mysql") {
				mysql_close($this->conn);
				$this->conn = null;
			} else if ($this->method == "sqlite") {
				sqlite_close($this->conn);
				$this->conn = null;
			}
		}
	}
	
	function getAdapter() {
		return $this->adapter;
	}
	
	function getMethod() {
		return $this->method;
	}
	
	function getOptionValue($optKey) {
		if (array_key_exists($optKey, $this->options)) {
			return $this->options[$optKey];
		} else {
			return false;
		}
	}
	
	function selectDB($db) {
		if ($this->conn) {
			
			$this->db = $db;
			
			if ($this->method == "mysql") {
				return (mysql_select_db($db));
			} else {
				return true;
			}
		} else {
			return false;
		}
	}

	function query($queryText) {
		if ($this->conn) {
			if ($this->method == "mysql") {
				$queryResult = @mysql_query($queryText, $this->conn);

				if (!$queryResult) {
					$this->errorMessage = mysql_error();
				}

				return $queryResult;
			} else if ($this->method == "sqlite") {
				$queryResult = sqlite_query($this->conn, $queryText);

				if (!$queryResult) {
					$this->errorMessage = sqlite_error_string(sqlite_last_error($this->conn));
				}

				return $queryResult;
			}
		} else {
			return false;
		}
	}

	function rowCount($resultSet) {
		if ($this->conn) {
			if ($this->method == "mysql") {
				return @mysql_num_rows($resultSet);
			} else if ($this->method == "sqlite") {
				return @sqlite_num_rows($resultSet);
			}
		}
	}
	
	function isResultSet($resultSet) {
		if ($this->conn) {
			return ($this->rowCount($resultSet) > 0);
		}
	}
	
	function fetchArray($resultSet) {
		if (!$resultSet)
			return false;
		
		if ($this->conn) {
			if ($this->method == "mysql") {
				return mysql_fetch_row($resultSet);
			} else if ($this->method == "sqlite") {
				return sqlite_fetch_array($resultSet, SQLITE_NUM);
			}
		}
	}

	function fetchAssoc($resultSet) {
		if (!$resultSet)
			return false;
		
		if ($this->conn) {
			if ($this->method == "mysql") {
				return mysql_fetch_assoc($resultSet);
			} else if ($this->method == "sqlite") {
				return sqlite_fetch_array($resultSet, SQLITE_ASSOC);
			}
		}
	}

	function affectedRows($resultSet) {
		if (!$resultSet)
			return false;
		
		if ($this->conn) {
			if ($this->method == "mysql") {
				return @mysql_affected_rows($resultSet);
			} else if ($this->method == "sqlite") {
				return sqlite_changes($resultSet);
			}
		}
	}
	
	function result($resultSet, $targetRow, $targetColumn = "") {
		if (!$resultSet)
			return false;
		
		if ($this->conn) {
			if ($this->method == "mysql") {
				return mysql_result($resultSet, $targetRow, $targetColumn);
			} else if ($this->method == "sqlite") {
				return sqlite_column($resultSet, $targetColumn);
			}
		}
	}
	
	function listDatabases() {
		if ($this->conn) {
			if ($this->adapter == "mysql") {
				return $this->query("SHOW DATABASES");
			} else if ($this->adapter == "sqlite") {
				$database = (array_key_exists("database", $this->options)) ? $this->options['database'] : "";
				return $database;
			}
		}
	}
	
	function listTables() {
		if ($this->conn) {
			if ($this->adapter == "mysql") {
				return $this->query("SHOW TABLES");
			} else if ($this->adapter == "sqlite") {
				return $this->query("SELECT name FROM sqlite_master WHERE type = 'table' ORDER BY name");
			}
		}
	}
	
	function hasCharsetSupport()
	{
		if ($this->conn) {
			if ($this->adapter == "mysql" && version_compare($this->getVersion(), "4.1", ">")) {
				return true;
			} else  {
				return false;
			}
		}
	}
	
	function listCharset() {
		if ($this->conn) {
			if ($this->adapter == "mysql") {	
				return $this->query("SHOW CHARACTER SET");
			} else if ($this->adapter == "sqlite") {
				return "";
			}
		}
	}
	
	function listCollation() {
		if ($this->conn) {
			if ($this->adapter == "mysql") {
				return $this->query("SHOW COLLATION");
			} else if ($this->adapter == "sqlite") {
				return "";
			}
		}
	}
	
	function insertId($resultSet = null) {
		if ($this->conn) {
			if ($this->method == "mysql") {
				return mysql_insert_id($resultSet);
			} else if ($this->method == "sqlite") {
				return sqlite_last_insert_rowid($resultSet);
			}
		}
	}

	function escapeString($toEscape) {
		if ($this->conn) {
			if ($this->adapter == "mysql") {
				return mysql_real_escape_string($toEscape);
			} else if ($this->adapter == "sqlite") {
				return sqlite_escape_string($toEscape);
			}
		}
	}
	
	function getVersion() {
		if ($this->conn) {
			// cache
			if ($this->version) {
				return $this->version;
			}
			
			if ($this->adapter == "mysql") {
				$verSql = mysql_get_server_info();
				$version = explode("-", $verSql);
				$this->version = $version[0];
				return $this->version;
			} else if ($this->adapter == "sqlite") {
				$this->version = sqlite_libversion();
				return $this->version;
			}
		}

	}
	
	// returns the number of rows in a table
	function tableRowCount($table) {
		if ($this->conn) {
			if ($this->adapter == "mysql") {
				$countSql = $this->query("SELECT COUNT(*) AS `RowCount` FROM `" . $table . "`");
				$count = (int)($this->result($countSql, 0, "RowCount"));
				return $count;
			} else if ($this->adapter == "sqlite") {
				$countSql = $this->query("SELECT COUNT(*) AS 'RowCount' FROM '" . $table . "'");
				$count = (int)($this->result($countSql, 0, "RowCount"));
				return $count;
			}
		}
	}
	
	// gets column info for a table
	function describeTable($table) {
		if ($this->conn) {
			if ($this->adapter == "mysql") {
				return $this->query("DESCRIBE `" . $table . "`");
			} else if ($this->adapter == "sqlite") {
				$columnSql = $this->query("SELECT sql FROM sqlite_master where tbl_name = '" . $table . "'");
				$columnInfo = $this->result($columnSql, 0, "sql");
				$columnStart = strpos($columnInfo, '(');
				$columns = substr($columnInfo, $columnStart+1, -1);
				$columns = split(',[^0-9]', $columns);
				
				$columnList = array();
				
				foreach ($columns as $column) {
					$column = trim($column);
					$columnSplit = explode(" ", $column, 2);
					$columnName = $columnSplit[0];
					$columnType = (sizeof($columnSplit) > 1) ? $columnSplit[1] : "";
					$columnList[] = array($columnName, $columnType);
				}
				
				return $columnList;
			}
		}
	}
	
	/*
		Return names, row counts etc for every database, table and view in a JSON string
	*/
	function getMetadata() {
		$output = '';
		if ($this->conn) {
			if ($this->adapter == "mysql" && version_compare($this->getVersion(), "5.0.0", ">=")) {
				$this->selectDB("information_schema");
				$schemaSql = $this->query("SELECT `SCHEMA_NAME` FROM `SCHEMATA` ORDER BY `SCHEMA_NAME`");
				if ($this->rowCount($schemaSql)) {
					while ($schema = $this->fetchAssoc($schemaSql)) {
						$output .= '{"name": "' . $schema['SCHEMA_NAME'] . '"';
						// other interesting columns: TABLE_TYPE, ENGINE, TABLE_COLUMN and many more
						$tableSql = $this->query("SELECT `TABLE_NAME`, `TABLE_ROWS` FROM `TABLES` WHERE `TABLE_SCHEMA`='" . $schema['SCHEMA_NAME'] . "' ORDER BY `TABLE_NAME`");
						if ($this->rowCount($tableSql)) {
							$output .= ',"items": [';
							while ($table = $this->fetchAssoc($tableSql)) {
								
								if ($schema['SCHEMA_NAME'] == "information_schema") {
									$countSql = $this->query("SELECT COUNT(*) AS `RowCount` FROM `" . $table['TABLE_NAME'] . "`");
									$rowCount = (int)($this->result($countSql, 0, "RowCount"));
								} else {
									$rowCount = (int)($table['TABLE_ROWS']);
								}
								
								$output .= '{"name":"' . $table['TABLE_NAME'] . '","rowcount":' . $rowCount . '},';
							}
							
							if (substr($output, -1) == ",")
								$output = substr($output, 0, -1);
							
							$output .= ']';
						}
						$output .= '},';
					}
					$output = substr($output, 0, -1);
				}
			} else if ($this->adapter == "mysql") {
				$schemaSql = $this->listDatabases();
				
				if ($this->rowCount($schemaSql)) {
					while ($schema = $this->fetchArray($schemaSql)) {
						$output .= '{"name": "' . $schema[0] . '"';
						
						$this->selectDB($schema[0]);
						$tableSql = $this->listTables();
						
						if ($this->rowCount($tableSql)) {
							$output .= ',"items": [';
							while ($table = $this->fetchArray($tableSql)) {
								$countSql = $this->query("SELECT COUNT(*) AS `RowCount` FROM `" . $table[0] . "`");
								$rowCount = (int)($this->result($countSql, 0, "RowCount"));
								$output .= '{"name":"' . $table[0] . '","rowcount":' . $rowCount . '},';
							}
							
							if (substr($output, -1) == ",")
								$output = substr($output, 0, -1);
							
							$output .= ']';
						}
						$output .= '},';
					}
					$output = substr($output, 0, -1);
				}
			} else if ($this->adapter == "sqlite") {
				$database = (array_key_exists("database", $this->options)) ? $this->options['database'] : "";
				
				$output .= '{"name": "' . $database . '"';
				
				$tableSql = $this->listTables();

				if ($this->rowCount($tableSql)) {
					$output .= ',"items": [';
					while ($tableRow = $this->fetchArray($tableSql)) {
						$countSql = $this->query("SELECT COUNT(*) AS 'RowCount' FROM '" . $tableRow[0] . "'");
						$rowCount = (int)($this->result($countSql, 0, "RowCount"));
						$output .= '{"name":"' . $tableRow[0] . '","rowcount":' . $rowCount . '},';
					}
					
					if (substr($output, -1) == ",")
						$output = substr($output, 0, -1);
					
					$output .= ']';
				}
				$output .= '}';
			}
		}
		return $output;
	}

	function error() {
		return $this->errorMessage;
	}

}