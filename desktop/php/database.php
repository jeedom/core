<?php
if (!isConnect('admin')) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
global $CONFIG;
//get all tables and their columns:
$sqlQuery = 'SELECT TABLE_NAME, COLUMN_NAME, DATA_TYPE, EXTRA FROM information_schema.columns WHERE table_schema=:db_name ORDER BY table_name,ordinal_position';
$result = DB::prepare($sqlQuery, array('db_name' => $CONFIG['db']['dbname']), DB::FETCH_TYPE_ALL);

$tableList = array();
foreach($result as $res) {
  if (!isset($tableList[$res['TABLE_NAME']]) || !is_array($tableList[$res['TABLE_NAME']])) {
    $tableList[$res['TABLE_NAME']] = array();
  }
  $tableList[$res['TABLE_NAME']][] = array('colName' => $res['COLUMN_NAME'], 'colType' => $res['DATA_TYPE'], 'colExtra' => $res['EXTRA']);
}
sendVarToJS('_tableList_', $tableList);
?>

<div id="div_rowSystemCommand" class="row">
  <div class="col-lg-2 col-md-3 col-sm-4" style="height: auto; overflow: hidden auto;">
    <div class="bs-sidebar">
      <ul class="nav nav-list bs-sidenav list-group" id='ul_listSqlHistory'></ul>
      <ul class="nav nav-list bs-sidenav list-group" id='ul_listSqlRequest'>
        <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_dbCommand" data-command="SHOW TABLES">{{Tables}}</a></li>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_dbCommand" data-command="SELECT table_name AS `Table`, round(((data_length + index_length) / 1024 / 1024), 2) `MB`,table_rows as `Ligne` FROM information_schema.TABLES WHERE table_schema='<?php  echo $CONFIG['db']['dbname'];?>' ORDER BY (data_length + index_length) DESC">{{Taille}}</a></li>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_dbCommand" data-command="SELECT * FROM dataStore WHERE type='scenario'">{{Select Variables}}</a></li>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_dbCommand" data-command="SELECT * FROM eqLogic">{{Select eqLogics}}</a></li>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_dbCommand" data-command="SELECT id, name, configuration FROM eqLogic">{{Select eqLogics configuration}}</a></li>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_dbCommand" data-command="SELECT * FROM cmd WHERE id=1">{{Select cmd id 1}}</a></li>
      </ul>
      <div id="h3_executeCommand" class="alert alert-info">{{Cliquez sur une commande ci dessus ou éxécutez une commande personnalisée.}}</div>
    </div>
  </div>
  <div class="col-lg-10 col-md-9 col-sm-8" style="padding-top: 5px;">
    <div id="dbCommands">
      <label style="width: 100%;"><i class="fas fa-database"></i> {{Constructeur SQL}}
        <div class="input-group pull-right" style="display:inline-flex; right: -8px;">
          <span class="input-group-btn">
            <a id="bt_writeDynamicCommand" class="btn btn-info btn-sm roundedLeft"><i class="fas fa-vial"></i> {{Tester}}
            </a><a id="bt_execDynamicCommand" class="btn btn-warning btn-sm roundedRight"><i class="fas fa-radiation"></i> {{Exécuter}}</a>
          </span>
        </div>
      </label>
      
      <div id="dynamicsql" class="content">
        <form class="form-horizontal">
          <fieldset>
            
            <!-- SQL UI OPERATION selector-->
            <div class="form-group">
              <div class="col-md-2 col-xs-3">
                <select id="sqlOperation" class="form-control input-sm info">
                  <option value="SELECT">SELECT</option>
                  <option value="INSERT">INSERT</option>
                  <option value="UPDATE">UPDATE</option>
                  <option value="DELETE">DELETE</option>
                </select>
              </div>
              
              <div class="col-md-4 col-xs-3">
                <input id="sql_selector" class="form-control input-sm" type="text" value="*" placeholder="* or col1,col2,..."/>
              </div>
              <label id="lblFrom" class="col-md-2 col-xs-2 control-label">FROM</label>
              <div class="col-md-3 col-xs-4">
                <select id="sqlTable" class="form-control input-sm">
                  <?php
                  $options = '';
                  foreach ($tableList as $table => $cols) {
                    $options .= '<option value="'.$table.'">'.$table.'</option>';
                  }
                  echo $options;
                  ?>
                </select>
              </div>
            </div>
            
            <!-- SQL UI SET-->
            <div id="sqlSetGroup" class="form-group" style="display: none;">
              <label class="col-xs-12">SET</label>
            </div>
            
            <!-- SQL UI WHERE-->
            <div id="sqlWhereGroup" class="form-group">
              <label class="col-md-2 col-xs-3 control-label">
                <input id="checksqlwhere" type="checkbox"/>WHERE
              </label>
              <div class="col-md-2 col-xs-3">
                <select id="sqlWhere" class="form-control input-sm disabled">
                  <?php
                  $options = '';
                  foreach ($tableList['cmd'] as $col) {
                    $options .= '<option value="'.$col['colName'].'">'.$col['colName'].'</option>';
                  }
                  echo $options;
                  ?>
                </select>
              </div>
              <div class="col-md-1 col-xs-3">
                <select id="sqlLike" class="form-control input-sm disabled">
                  <option value="=">=</option>
                  <option value="LIKE">LIKE</option>
                </select>
              </div>
              <div class="col-md-6 col-xs-3">
                <input id="sqlLikeValue" class="form-control input-sm disabled" type="text" value="" placeholder="int or 'string', like % wildcard"/>
              </div>
            </div>
            
          </fieldset>
        </form>
      </div>
      
      <label><i class="fas fa-database"></i> {{Commande SQL}}</label>
      <div class="input-group content">
        <input id="in_specificCommand" class="form-control input-sm" type="text"/>
        <div class="input-group-btn">
          <a id="bt_validateSpecificCommand" class="btn btn-warning btn-sm"><i class="fas fa-radiation"></i> {{Exécuter}} </a>
        </div>
      </div>
    </div>
    
    <!-- SQL RESULT -->
    <div id="div_commandResult" style="overflow: auto;"></div>
  </div>
</div>

<?php include_file("desktop", "database", "js");?>
