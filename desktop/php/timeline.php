<?php
if (!hasRight('timelineview',true)) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
include_file('3rdparty', 'jquery.tablesorter/theme.bootstrap', 'css');
include_file('3rdparty', 'jquery.tablesorter/jquery.tablesorter.min', 'js');
include_file('3rdparty', 'jquery.tablesorter/jquery.tablesorter.widgets.min', 'js');
?>

<table id="table_timeline" class="table table-bordered table-condensed tablesorter" >
    <thead>
        <tr>
            <th style="width: 200px;">{{Date}}</th>
            <th style="width: 200px;">{{Evénement}}</th>
            <th>{{Valeur}}</th>
        </tr>
    </thead>
    <tbody> 
        <?php
        foreach (internalEvent::all() as $internalEvent) {
            echo '<tr>';
            echo '<td>';
            echo $internalEvent->getDatetime();
            echo '</td>';
            echo '<td>';
            echo $internalEvent->getEvent();
            echo '</td>';
            echo '<td>';
            echo '<table class="table table-bordered table-condensed" style="margin-bottom : 0px;">';
            echo '<thead>';
            echo '<tr>';
            if (is_array($internalEvent->getOptions())) {
                foreach ($internalEvent->getOptions() as $key => $value) {
                    echo '<th>';
                    echo $key;
                    echo '</th>';
                    if ($key == 'id') {
                        echo '<th>{{Nom}}</th>';
                    }
                }
            }
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            echo '<tr>';
            if (is_array($internalEvent->getOptions())) {
                foreach ($internalEvent->getOptions() as $key => $value) {
                    echo '<td style="width: 200px;">';
                    echo $value;
                    echo '</td>';
                    if ($key == 'id') {
                        echo '<td>';
                        if (strpos($internalEvent->getEvent(), 'cmd') !== false) {
                            $cmd = cmd::byId($value);
                            if (is_object($cmd)) {
                                echo $cmd->getHumanName();
                            }
                        }
                        if (strpos($internalEvent->getEvent(), 'eqLogic') !== false) {
                            $eqLogic = eqLogic::byId($value);
                            if (is_object($eqLogic)) {
                                echo $eqLogic->getHumanName();
                            }
                        }
                        if (strpos($internalEvent->getEvent(), 'scenario') !== false) {
                            $scenario = scenario::byId($value);
                            if (is_object($scenario)) {
                                echo $scenario->getHumanName();
                            }
                        }
                        if (strpos($internalEvent->getEvent(), 'object') !== false) {
                            $object = object::byId($value);
                            if (is_object($object)) {
                                echo $object->getHumanName();
                            }
                        }
                        echo '</td>';
                    }
                }
            }
            echo '</tr>';
            echo '</tbody>';
            echo '</table>';
            echo '</td>';
            echo '</tr>';
        }
        ?>
    </tbody>
</table>
