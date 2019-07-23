<?php
if (!isConnect('admin')) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
global $CONFIG;
?>
<style>
.bs-sidenav .list-group-item{
  padding : 2px 2px 2px 2px;
}
</style>
<div id="div_rowSystemCommand" class="row row-overflow">
  <div class="col-lg-2 col-md-3 col-sm-4" style="overflow-y:auto;overflow-x:hidden;">
    <a class="btn btn-warning" style="width:100%" id="bt_checkDatabase"><i class="fas fa-check"></i> {{Vérification}}</a>
    <div class="bs-sidebar">
      <ul class="nav nav-list bs-sidenav list-group" id='ul_listSqlHistory'></ul>
      <ul class="nav nav-list bs-sidenav list-group" id='ul_listSqlRequest'>
        <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_dbCommand" data-command="SHOW TABLES;">{{Tables}}</a></li>
        <li class="cursor list-group-item list-group-item-success"><a class="bt_dbCommand" data-command="SELECT table_name AS `Table`, round(((data_length + index_length) / 1024 / 1024), 2) `MB`,table_rows as `Ligne` FROM information_schema.TABLES WHERE table_schema='<?php  echo $CONFIG['db']['dbname'];?>' ORDER BY (data_length + index_length) DESC;">{{Taille}}</a></li>
      </ul>
    </div>
  </div>
  <div class="col-lg-10 col-md-9 col-sm-8" style="border-left: solid 1px #EEE;">
    <h3 id="h3_executeCommand">{{Cliquez sur une commande à droite ou tapez une commande personnalisée ci-dessous}}</h3>
    <div class="input-group">
      <input id="in_specificCommand" class="form-control" />
      <div class="input-group-btn">
        <a id="bt_validateSpecifiCommand" class="btn btn-warning"><i class="fas fa-check"></i> {{OK}}</a>
      </div>
    </div>
    <div id="div_commandResult"></div>
  </div>
</div>
<?php include_file("desktop", "database", "js");?>
