<?php
if (!isConnect('admin')) {
  throw new Exception('{{401 - Accès non autorisé}}');
}

global $CONFIG;
//get all tables and their columns:
$sqlQuery = 'SELECT TABLE_NAME, COLUMN_NAME, DATA_TYPE, EXTRA FROM information_schema.columns WHERE table_schema=:db_name ORDER BY table_name,ordinal_position';
$result = DB::prepare($sqlQuery, array('db_name' => $CONFIG['db']['dbname']), DB::FETCH_TYPE_ALL);

//exclude parameters from listing
global $excludeParams;
$excludeParams = [];
$excludeParams['eqLogic'] = ['order', 'display', 'comment'];
$excludeParams['eqLogic']['configuration'] = ['createtime', 'updatetime', 'batterytime'];
$excludeParams['scenario'] = ['description', 'display', 'trigger', 'scenarioElement'];

//scan DB for keys, values, jsonValues:
global $typePossibilities;
scanDB('eqLogic');
scanDB('cmd');
scanDB('object');
scanDB('scenario');
//sorting all this:
foreach($typePossibilities as &$item) {
  ksort($item);
}
foreach($typePossibilities as &$item) {
  foreach($item as &$key) {
    if (!is_string($key[0])) {
      uksort($key, function ($a, $b) {
        $a = strtolower($a);
        $b = strtolower($b);
        return strcmp($a, $b);
      });
    } else {
      sort($key);
    }
  }
}
sendVarToJS('typePossibilities', $typePossibilities);

function scanDB($_table) {
  global $excludeParams;
  global $typePossibilities;
  $typePossibilities[$_table] = [];
  $className = $_table;
  if ($_table == 'object') $className = 'jeeObject';

  if (!class_exists($className)) return false;
  try {
    $items = $className::all();
  } catch (Exception $e) {
    return false;
  }
  foreach ($items as $item) {
    $sqlQuery = 'SELECT * FROM `'.$_table.'` WHERE `id` = '.$item->getId();
    $result = DB::prepare($sqlQuery, array('db_name' => $CONFIG['db']['dbname']), DB::FETCH_TYPE_ALL);
    foreach ($result[0] as $key => $value) {
      if ($value == '' || $value == '[]') continue;
      if (in_array($key, $excludeParams[$_table])) continue;
      if (!array_key_exists($key, $typePossibilities[$_table])) {
        $typePossibilities[$_table][$key] = [];
      }
      if (is_object(json_decode($value))) {
        try {
          $json = json_decode($value, true);
          foreach ($json as $jkey => $jvalue) {
            if (isset($excludeParams[$_table][$key]) && in_array($jkey, $excludeParams[$_table][$key])) {
              continue;
            }
            if (!array_key_exists($jkey, $typePossibilities[$_table][$key])) {
              $typePossibilities[$_table][$key][$jkey] = [];
            }
            if (!in_array($jvalue, $typePossibilities[$_table][$key][$jkey])) {
              array_push($typePossibilities[$_table][$key][$jkey], $jvalue);
            }
          }
        } catch (Exception $e) {}
      } else {
        if (!in_array($value, $typePossibilities[$_table][$key])) {
          array_push($typePossibilities[$_table][$key], $value);
        }
      }
    }
  }
}
?>

<div class="row row-overflow">
  <div class="hasfloatingbar col-xs-12">

    <div class="floatingbar">
      <div class="input-group">
        <span class="input-group-btn">
          <a href="index.php?v=d&p=backup" class="btn btn-success btn-sm roundedLeft"><i class="fas fa-save"></i> {{Sauvegarde Système}}
          </a><a class="btn btn-info btn-sm" id="bt_exportFilter"><i class="fas fa-file-export"></i> {{Exporter}}
          </a><span class="btn btn-info btn-sm btn-file"><i class="fas fa-file-import"></i> {{Importer}}<input  id="bt_importFilter" type="file" name="file" style="display:inline-block;"></span>
          </a><a class="btn btn-danger btn-sm roundedRight" id="bt_execMassEdit"><i class="fas fa-fill-drip"></i> {{Exécuter}}</a>
        </span>
      </div>
    </div>

    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation" class="active"><a href="#generaltab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-fill-drip"></i> {{Editeur en masse}}</a></li>
    </ul>

    <div class="tab-content">
      <div role="tabpanel" class="tab-pane active" id="generaltab">
        <br>
        <fieldset>
          <div class="alert alert-warning"><i class="fas fa-radiation"></i> {{EXPERIMENTAL : Il est fortement conseillé de réaliser un backup système avant d'utiliser cet outil !}}
          </div>
        </fieldset>

        <!-- Filter part -->
        <div class="col-xs-12">
          <legend><i class="fas fa-filter"></i> {{Filtre}}</legend>
          <div class="form-group">
            <div class="col-lg-4 col-xs-8">
              <select id="sel_FilterByType" class="form-control">
                <option value="eqLogic">{{Equipement}}</option>
                <option value="cmd">{{Commande}}</option>
                <option value="object">{{Objet}}</option>
                <option value="scenario">{{Scénario}}</option>
              </select>
            </div>
            <div class="col-lg-1 col-xs-2">
              <a id="bt_addFilter" class="btn btn-xs btn-success" title="{{Ajouter un filtre}}"><i class="fas fa-plus"></i> {{Ajouter}}</a>
            </div>
            <div class="col-lg-1 col-xs-2">
              <a id="bt_testFilter" class="btn btn-xs btn-info disabled" title="{{Test}}"><i class="fas fa-vial"></i> {{Test}}</a>
            </div>
          </div>
        </div>

        <div class="col-xs-12">
          <form class="form-horizontal">
            <div class="form-group">
              <div class="col-md-2 col-xs-3"><i class="fas fa-database"></i> {{Colonne}}</div>
              <div class="col-md-3 col-xs-3"><i class="fas fa-asterisk"></i> {{Valeur}}</div>
              <div class="col-md-3 col-xs-3"><i class="fas fa-sitemap"></i> {{Valeur json}}</div>
              <div class="col-md-1 col-xs-2">{{Options}}</div>
            </div>

            <div id="filter">

            </div>

            <div id="testSQL" class="form-group col-lg-12">

            </div>

            <div id="testResult" class="form-group col-lg-12">

            </div>

          </form>
        </div>

        <!-- Edition part -->
        <div class="col-xs-12">
          <hr class="hrPrimary">
          <legend><i class="fas fa-edit"></i> {{Edition}}</legend>
        </div>

        <div class="col-xs-12">
          <form class="form-horizontal">
            <div class="form-group">
              <div class="col-md-2 col-xs-3"><i class="fas fa-database"></i> {{Colonne}}</div>
              <div class="col-md-3 col-xs-3"><i class="fas fa-asterisk"></i> {{Valeur}}</div>
              <div class="col-md-3 col-xs-3"><i class="fas fa-sitemap"></i> {{Valeur json}}</div>
            </div>

            <div id="edit" class="form-group">

            </div>

            <div id="execSQL" class="form-group col-lg-12">

            </div>
          </form>
        </div>
      </div>
    </div>

  </div>
</div>

<?php include_file('desktop', 'massedit', 'js');?>