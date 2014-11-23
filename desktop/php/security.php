<?php
if (!hasRight('securityview',true)) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
include_file('3rdparty', 'jquery.tablesorter/theme.bootstrap', 'css');
include_file('3rdparty', 'jquery.tablesorter/jquery.tablesorter.min', 'js');
include_file('3rdparty', 'jquery.tablesorter/jquery.tablesorter.widgets.min', 'js');
?>
<ul class="nav nav-tabs" id="security_tab">
    <li class="active"><a href="#status">{{Statut}}</a></li>
    <li><a href="#config">{{Configuration}}</a></li>
</ul>


<div class="tab-content">
    <div class="tab-pane active" id="status">
        <br/>
        <table id="table_security" class="table table-bordered table-condensed tablesorter" >
            <thead>
                <tr>
                    <th class="ip" style="width: 80px;">{{IP}}</th>
                    <th class="username">{{Utilisateur}}</th>
                    <th class="username">{{Localisation}}</th>
                    <th class="failure">{{Echec de connexion}}</th>
                    <th class="datetime">{{Date}}</th>
                    <th class="username">{{Statut}}</th>
                    <th class="username">{{Informations}}</th>
                    <th class="username" style="width: 200px;">{{Action}}</th>
                </tr>
            </thead>
            <tbody> 
                <?php
                foreach (connection::all() as $connection) {
                    echo '<tr data-id="' . $connection->getId() . '">';
                    echo '<td class="ip">';
                    echo $connection->getIp();
                    echo '</td>';
                    echo '<td>';
                    echo $connection->getUsername();
                    echo '</td>';
                    echo '<td>';
                    echo $connection->getLocalisation();
                    echo '</td>';
                    echo '<td>';
                    echo $connection->getFailure();
                    echo '</td>';
                    echo '<td>';
                    echo $connection->getDatetime();
                    echo '</td>';
                    echo '<td>';
                    echo $connection->getStatus();
                    echo '</td>';
                    echo '<td>';
                    echo $connection->getInformations('org');
                    echo '</td>';
                    echo '<td>';
                    echo '<a class="btn btn-warning btn-xs remove pull-right" style="color : white;"><i class="fa fa-trash-o"></i> Supprimer</a> ';
                    echo '<a class="btn btn-danger btn-xs ban pull-right" style="color : white;"><i class="fa fa-thumbs-down"></i> Bannir</a>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="tab-pane" id="config">
        <br/>
        <form class="form-horizontal">
            <fieldset>
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{Activer la sécurité anti-piratage}}</label>
                    <div class="col-sm-3">
                        <input type="checkbox" class="configKey" data-l1key="security::enable"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{Nombre de tentatives de connexion max}}</label>
                    <div class="col-sm-1">
                        <input type="text" class="configKey form-control" data-l1key="security::retry" />
                    </div>
                    <label class="col-sm-1 control-label">{{en (min) }}</label>
                    <div class="col-sm-1">
                        <input type="text" class="configKey form-control" data-l1key="security::backlogtime" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{Durée du bannissement (min)}}</label>
                    <div class="col-sm-1">
                        <input type="text" class="configKey form-control" data-l1key="security::bantime" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{Liste blanche d'IP}}</label>
                    <div class="col-sm-8">
                        <input type="text" class="configKey form-control" data-l1key="security::protectIp" />
                    </div>
                </div>

            </fieldset>
        </form>
        <div class="form-actions" style="height: 20px;">
            <a class="btn btn-success" id="bt_saveSecurityConfig"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
        </div>
    </div>
</div>


<?php include_file("desktop", "security", "js"); ?>