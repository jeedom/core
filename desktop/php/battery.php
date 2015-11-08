<?php
if (!hasRight('batteryview')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
$list = array();
$plugins = array();
$battery = array();
$objects = array();
foreach (object::all() as $object) {
    foreach ($object->getEqLogic() as $eqLogic) {
        $battery_type = $eqLogic->getConfiguration('battery_type', '');
        if ($eqLogic->getConfiguration('batteryStatus', -2) != -2) {
            array_push($list, $eqLogic);
            array_push($plugins, $eqLogic->getEqType_name());
            array_push($objects, $eqLogic->getobject()->getName());
            if ($battery_type != '') {
                if (strpos($battery_type,' ') === false) {
                    array_push($battery,$battery_type);
                } else {
                    array_push($battery,substr(strrchr($battery_type, " "), 1));
                }
            }
        }
    }
}
usort($list, function ($a, $b) {
    return ($a->getConfiguration('batteryStatus') < $b->getConfiguration('batteryStatus')) ? -1 : (($a->getConfiguration('batteryStatus') > $b->getConfiguration('batteryStatus')) ? 1 : 0);
});
sort($plugins);
sort($battery);
?>
<div style="position : fixed;height:100%;width:15px;top:50px;left:0px;z-index:998;background-color:#f6f6f6;" class="div_smallSideBar" id="bt_displayFilter"><i class="fa fa-arrow-circle-o-right" style="color : #b6b6b6;"></i></div>
<div class="row row-overflow">
    <div class="col-md-3 col-sm-4" id="sd_filterList" style="z-index:999">
        <div class="bs-sidebar">
            <ul id="ul_object" class="nav nav-list bs-sidenav">
                <li><div class="col-md-8 col-sm-9" style="cursor :default;"><legend><i class="icon divers-caduceus3"></i>  {{Santé}}</legend></div><div class="col-md-4 col-sm-3" style="margin-top:15px;"><i class="fa fa-times cursor tooltips  pull-right bt_globalsanteoff" title="Tout masquer" style="color : grey;"></i><i class="fa fa-refresh cursor tooltips pull-right bt_globalsantetoggle" title="Inverser" style="color : grey;"></i><i class="fa fa-check cursor tooltips pull-right bt_globalsanteon" title="Tout afficher" style="color : grey;"></i></li>
                <li><div class="col-md-8 col-sm-9" style="cursor :default;">{{Critique}}</div>
                <div class="col-md-4 col-sm-3">
                <input type="checkbox" data-size="mini" id="critical" class="globalsante bootstrapSwitch" checked/></div></li>
               <li><div class="col-md-8 col-sm-9" style="cursor :default;">{{Warning}}</div>
               <div class="col-md-4 col-sm-3">
                <input type="checkbox" id="test" data-size="mini" id="warning" class="globalsante bootstrapSwitch" checked/></div></li>
               <li><div class="col-md-8 col-sm-9" style="cursor :default;">{{Bonne}}</div>
               <div class="col-md-4 col-sm-3">
                <input type="checkbox" data-size="mini" id="good" class="globalsante bootstrapSwitch" checked/></div></li>
                <li><div class="col-md-8 col-sm-9" style="cursor :default;"><legend><i class="icon techno-charging"></i>  {{Piles}}</legend></div><div class="col-md-4 col-sm-3" style="margin-top:15px;"><i class="fa fa-times cursor tooltips  pull-right bt_globalpileoff" title="Tout masquer" style="color : grey;"></i><i class="fa fa-refresh cursor tooltips pull-right bt_globalpiletoggle" title="Inverser" style="color : grey;"></i><i class="fa fa-check cursor tooltips pull-right bt_globalpileon" title="Tout afficher" style="color : grey;"></i></li>
                <li><div class="col-md-8 col-sm-9" style="cursor :default;">{{Non définies}}</div><div class="col-md-4 col-sm-3"><input type="checkbox" data-size="mini" id="none" class="globalpile bootstrapSwitch" checked/></div></li>
                <?php
                foreach (array_unique($battery) as $battery_type) {
                echo '<li><div class="col-md-8 col-sm-9" style="cursor :default;">' .$battery_type.'</div><div class="col-md-4 col-sm-3">';
                echo '<input type="checkbox" data-size="mini" id="' . $battery_type . '" class="globalpile bootstrapSwitch" checked/></div></li>';
                }
                ?>
                <li><div class="col-md-8 col-sm-9" style="cursor :default;"><legend><i class="fa fa-tasks"></i>  {{Plugins}}</legend></div><div class="col-md-4 col-sm-3" style="margin-top:15px;"><i class="fa fa-times cursor tooltips  pull-right bt_globalpluginoff" title="Tout masquer" style="color : grey;"></i><i class="fa fa-refresh cursor tooltips pull-right bt_globalplugintoggle" title="Inverser" style="color : grey;"></i><i class="fa fa-check cursor tooltips pull-right bt_globalpluginon" title="Tout afficher" style="color : grey;"></i></li>
                <?php
                foreach (array_unique($plugins) as $plugins_type) {
                echo '<li><div class="col-md-8 col-sm-9" style="cursor :default;">' .ucfirst($plugins_type).'</div><div class="col-md-4 col-sm-3">';
                echo '<input type="checkbox" data-size="mini" id="' . $plugins_type . '" class="globalplugin bootstrapSwitch" checked/></div></li>';
                }
                ?>
                <li><div class="col-md-8 col-sm-9" style="cursor :default;"><legend><i class="fa fa-picture-o" ></i>  {{Objets}}</legend></div><div class="col-md-4 col-sm-3" style="margin-top:15px;"><i class="fa fa-times cursor tooltips  pull-right bt_globalobjetoff" title="Tout masquer" style="color : grey;"></i><i class="fa fa-refresh cursor tooltips pull-right bt_globalobjettoggle" title="Inverser" style="color : grey;"></i><i class="fa fa-check cursor tooltips pull-right bt_globalobjeton" title="Tout afficher" style="color : grey;"></i></li>
                <?php
                foreach (array_unique($objects) as $objets_type) {
                echo '<li><div class="col-md-8 col-sm-9" style="cursor :default;">' .$objets_type.'</div><div class="col-md-4 col-sm-3">';
                echo '<input type="checkbox" data-size="mini" id="' . str_replace(' ','_',$objets_type) . '" class="globalobjet bootstrapSwitch" checked/></div></li>';
                }
                ?>
           </ul>
       </div>
   </div>
   <div class="col-lg-10 col-md-10 col-sm-9" id="div_resumeBatteryList" style="border-left: solid 1px #EEE; padding-left: 25px;">
   <div class="batteryListContainer">
<?php
foreach ($list as $eqLogic) {
    $color = '#2ecc71';
    $level = 'good';
    $battery = $eqLogic->getConfiguration('battery_type', 'none');
    if (strpos($battery,' ') !== false) {
        $battery=substr(strrchr($battery, " "), 1);
    }
    $plugins = $eqLogic->getEqType_name();
    $objets = str_replace(' ','_',$eqLogic->getobject()->getName());
    if ($eqLogic->getConfiguration('batteryStatus') <= $eqLogic->getConfiguration('battery_danger_threshold', config::byKey('battery::danger'))) {
        $color = '#e74c3c';
        $level = 'critical';
    } else if ($eqLogic->getConfiguration('batteryStatus') <= $eqLogic->getConfiguration('battery_warning_threshold', config::byKey('battery::warning'))) {
        $color = '#f1c40f';
        $level = 'warning';
    }
    $classAttr = $level . ' ' . $battery . ' ' . $plugins . ' ' . $objets;
    echo '<div class="eqLogic eqLogic-widget ' . $classAttr . '" style="min-width:80px;background-color:' . $color . '">';
    echo '<center class="widget-name"><a href="' . $eqLogic->getLinkToConfiguration() . '" style="font-size : 1.5em;">' . $eqLogic->getName() . '</a><br/><span style="font-size: 0.95em;position:relative;top:-5px;cursor:default;">' . $eqLogic->getobject()->getName() . '</span></center>';
    echo '<center><span style="font-size:2.2em;font-weight: bold;cursor:default;">' . $eqLogic->getConfiguration('batteryStatus', -2) . '</span><span>%</span></center>';
    echo '<center style="cursor:default;">{{Le }}' . $eqLogic->getConfiguration('batteryStatusDatetime', __('inconnue', __FILE__)) . '</center>';
    if ($eqLogic->getConfiguration('battery_type', '') != '') {
        echo '<span class="pull-right tooltips" style="font-size : 0.8em;margin-bottom: 3px;margin-right: 5px;cursor:default;" title="Piles">' . $eqLogic->getConfiguration('battery_type', '') . '</span>';
    }
    echo '<span class="pull-left tooltips" style="font-size : 0.8em;margin-bottom: 3px;margin-left: 5px;cursor:default;" title="Plugin">' . ucfirst($eqLogic->getEqType_name()) . '</span>';
    if ($eqLogic->getConfiguration('battery_danger_threshold') != '' or $eqLogic->getConfiguration('battery_warning_threshold') != '') {
        echo '</br><i class="icon techno-fingerprint41 pull-right tooltips" style="margin-top: 8px;margin-right: 8px;cursor:default;" title="Seuil manuel défini"></i>';
    }
    echo '</div>';
}
echo '</div>';
?>
<?php include_file('desktop', 'battery', 'js');?>
</div>
</div>