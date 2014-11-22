<?php
if (!hasRight('administrationview', true)) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
sendVarToJS('ldapEnable', config::byKey('ldap::enable'));
?>
<div id="config">
    <div class="panel-group" id="accordionConfiguration">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#config_generale">
                        {{Configuration générale}}
                    </a>
                </h3>
            </div>
            <div id="config_generale" class="panel-collapse collapse in">
                <div class="panel-body">
                    <form class="form-horizontal">
                        <fieldset>
                            <div class="form-group expertModeVisible">
                                <label class="col-lg-2 control-label">{{Clef api}}</label>
                                <div class="col-lg-2"> 
                                    <p class="form-control-static" id="in_keyAPI"><?php echo config::byKey('api'); ?></p>
                                </div>
                                <div class="col-lg-1"> 
                                    <a class="btn btn-default form-control" id="bt_genKeyAPI">{{Générer}}</a>
                                </div>
                            </div>
                            <?php if (config::byKey('jeeNetwork::mode') == 'master') { ?>
                                <div class="form-group expertModeVisible">
                                    <label class="col-lg-2 control-label">{{Clef nodeJS}}</label>
                                    <div class="col-lg-2"> 
                                        <p class="form-control-static" id="in_nodeJsKey"><?php echo config::byKey('nodeJsKey'); ?></p>
                                    </div>
                                    <div class="col-lg-1"> 
                                        <a class="btn btn-default form-control" id="bt_nodeJsKey" >{{Générer}}</a>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">{{Dernière date enregistrée}}</label>
                                <div class="col-lg-2"> 
                                    <?php
                                    $cache = cache::byKey('jeedom::lastDate');
                                    echo '<p class="form-control-static" id="in_jeedomLastDate">' . $cache->getValue() . '</p>';
                                    ?>
                                </div>
                                <div class="col-lg-2"> 
                                    <a class="btn btn-default form-control" id="bt_clearJeedomLastDate">{{Réinitialiser}}</a>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">{{Commande d'information utilisateur}}</label>
                                <div class="col-lg-3">
                                    <input type="text"  class="configKey form-control" data-l1key="emailAdmin" />
                                </div>
                                <div class="col-lg-2">
                                    <a class="btn btn-default cursor" title="Rechercher une commande" id="bt_selectMailCmd"><i class="fa fa-list-alt"></i></a>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">{{Langue}}</label>
                                <div class="col-lg-2">
                                    <select class="configKey form-control" data-l1key="language">
                                        <option value="fr_FR">{{Français}}</option>
                                        <option value="en_US">{{Anglais}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">{{Durée de vie des sessions (heure)}}</label>
                                <div class="col-lg-2">
                                    <input type="text"  class="configKey form-control" data-l1key="session_lifetime" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">{{Date et heure}}</label>
                                <div class="col-lg-4">
                                    <select class="configKey form-control" data-l1key="timezone">
                                        <option value="Pacific/Midway">(GMT-11:00) Midway Island, Samoa</option>
                                        <option value="America/Adak">(GMT-10:00) Hawaii-Aleutian</option>
                                        <option value="Etc/GMT+10">(GMT-10:00) Hawaii</option>
                                        <option value="Pacific/Marquesas">(GMT-09:30) Marquesas Islands</option>
                                        <option value="Pacific/Gambier">(GMT-09:00) Gambier Islands</option>
                                        <option value="America/Anchorage">(GMT-09:00) Alaska</option>
                                        <option value="America/Ensenada">(GMT-08:00) Tijuana, Baja California</option>
                                        <option value="Etc/GMT+8">(GMT-08:00) Pitcairn Islands</option>
                                        <option value="America/Los_Angeles">(GMT-08:00) Pacific Time (US & Canada)</option>
                                        <option value="America/Denver">(GMT-07:00) Mountain Time (US & Canada)</option>
                                        <option value="America/Chihuahua">(GMT-07:00) Chihuahua, La Paz, Mazatlan</option>
                                        <option value="America/Dawson_Creek">(GMT-07:00) Arizona</option>
                                        <option value="America/Belize">(GMT-06:00) Saskatchewan, Central America</option>
                                        <option value="America/Cancun">(GMT-06:00) Guadalajara, Mexico City, Monterrey</option>
                                        <option value="Chile/EasterIsland">(GMT-06:00) Easter Island</option>
                                        <option value="America/Chicago">(GMT-06:00) Central Time (US & Canada)</option>
                                        <option value="America/New_York">(GMT-05:00) Eastern Time (US & Canada)</option>
                                        <option value="America/Havana">(GMT-05:00) Cuba</option>
                                        <option value="America/Bogota">(GMT-05:00) Bogota, Lima, Quito, Rio Branco</option>
                                        <option value="America/Caracas">(GMT-04:30) Caracas</option>
                                        <option value="America/Santiago">(GMT-04:00) Santiago</option>
                                        <option value="America/La_Paz">(GMT-04:00) La Paz</option>
                                        <option value="Atlantic/Stanley">(GMT-04:00) Faukland Islands</option>
                                        <option value="America/Campo_Grande">(GMT-04:00) Brazil</option>
                                        <option value="America/Goose_Bay">(GMT-04:00) Atlantic Time (Goose Bay)</option>
                                        <option value="America/Glace_Bay">(GMT-04:00) Atlantic Time (Canada)</option>
                                        <option value="America/St_Johns">(GMT-03:30) Newfoundland</option>
                                        <option value="America/Araguaina">(GMT-03:00) UTC-3</option>
                                        <option value="America/Montevideo">(GMT-03:00) Montevideo</option>
                                        <option value="America/Miquelon">(GMT-03:00) Miquelon, St. Pierre</option>
                                        <option value="America/Godthab">(GMT-03:00) Greenland</option>
                                        <option value="America/Argentina/Buenos_Aires">(GMT-03:00) Buenos Aires</option>
                                        <option value="America/Sao_Paulo">(GMT-03:00) Brasilia</option>
                                        <option value="America/Noronha">(GMT-02:00) Mid-Atlantic</option>
                                        <option value="Atlantic/Cape_Verde">(GMT-01:00) Cape Verde Is.</option>
                                        <option value="Atlantic/Azores">(GMT-01:00) Azores</option>
                                        <option value="Europe/Belfast">(GMT) Greenwich Mean Time : Belfast</option>
                                        <option value="Europe/Dublin">(GMT) Greenwich Mean Time : Dublin</option>
                                        <option value="Europe/Lisbon">(GMT) Greenwich Mean Time : Lisbon</option>
                                        <option value="Europe/London">(GMT) Greenwich Mean Time : London</option>
                                        <option value="Africa/Abidjan">(GMT) Monrovia, Reykjavik</option>
                                        <option value="Europe/Amsterdam">(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna</option>
                                        <option value="Europe/Belgrade">(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague</option>
                                        <option value="Europe/Brussels">(GMT+01:00) Brussels, Copenhagen, Madrid, Paris</option>
                                        <option value="Africa/Algiers">(GMT+01:00) West Central Africa</option>
                                        <option value="Africa/Windhoek">(GMT+01:00) Windhoek</option>
                                        <option value="Asia/Beirut">(GMT+02:00) Beirut</option>
                                        <option value="Africa/Cairo">(GMT+02:00) Cairo</option>
                                        <option value="Asia/Gaza">(GMT+02:00) Gaza</option>
                                        <option value="Africa/Blantyre">(GMT+02:00) Harare, Pretoria</option>
                                        <option value="Asia/Jerusalem">(GMT+02:00) Jerusalem</option>
                                        <option value="Europe/Minsk">(GMT+02:00) Minsk</option>
                                        <option value="Asia/Damascus">(GMT+02:00) Syria</option>
                                        <option value="Europe/Moscow">(GMT+03:00) Moscow, St. Petersburg, Volgograd</option>
                                        <option value="Africa/Addis_Ababa">(GMT+03:00) Nairobi</option>
                                        <option value="Asia/Tehran">(GMT+03:30) Tehran</option>
                                        <option value="Asia/Dubai">(GMT+04:00) Abu Dhabi, Muscat</option>
                                        <option value="Asia/Yerevan">(GMT+04:00) Yerevan</option>
                                        <option value="Asia/Kabul">(GMT+04:30) Kabul</option>
                                        <option value="Asia/Yekaterinburg">(GMT+05:00) Ekaterinburg</option>
                                        <option value="Asia/Tashkent">(GMT+05:00) Tashkent</option>
                                        <option value="Asia/Kolkata">(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>
                                        <option value="Asia/Katmandu">(GMT+05:45) Kathmandu</option>
                                        <option value="Asia/Dhaka">(GMT+06:00) Astana, Dhaka</option>
                                        <option value="Asia/Novosibirsk">(GMT+06:00) Novosibirsk</option>
                                        <option value="Asia/Rangoon">(GMT+06:30) Yangon (Rangoon)</option>
                                        <option value="Asia/Bangkok">(GMT+07:00) Bangkok, Hanoi, Jakarta</option>
                                        <option value="Asia/Krasnoyarsk">(GMT+07:00) Krasnoyarsk</option>
                                        <option value="Asia/Hong_Kong">(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi</option>
                                        <option value="Asia/Irkutsk">(GMT+08:00) Irkutsk, Ulaan Bataar</option>
                                        <option value="Australia/Perth">(GMT+08:00) Perth</option>
                                        <option value="Australia/Eucla">(GMT+08:45) Eucla</option>
                                        <option value="Asia/Tokyo">(GMT+09:00) Osaka, Sapporo, Tokyo</option>
                                        <option value="Asia/Seoul">(GMT+09:00) Seoul</option>
                                        <option value="Asia/Yakutsk">(GMT+09:00) Yakutsk</option>
                                        <option value="Australia/Adelaide">(GMT+09:30) Adelaide</option>
                                        <option value="Australia/Darwin">(GMT+09:30) Darwin</option>
                                        <option value="Australia/Brisbane">(GMT+10:00) Brisbane</option>
                                        <option value="Australia/Hobart">(GMT+10:00) Hobart</option>
                                        <option value="Asia/Vladivostok">(GMT+10:00) Vladivostok</option>
                                        <option value="Australia/Lord_Howe">(GMT+10:30) Lord Howe Island</option>
                                        <option value="Etc/GMT-11">(GMT+11:00) Solomon Is., New Caledonia</option>
                                        <option value="Asia/Magadan">(GMT+11:00) Magadan</option>
                                        <option value="Pacific/Norfolk">(GMT+11:30) Norfolk Island</option>
                                        <option value="Asia/Anadyr">(GMT+12:00) Anadyr, Kamchatka</option>
                                        <option value="Pacific/Auckland">(GMT+12:00) Auckland, Wellington</option>
                                        <option value="Etc/GMT-12">(GMT+12:00) Fiji, Kamchatka, Marshall Is.</option>
                                        <option value="Pacific/Chatham">(GMT+12:45) Chatham Islands</option>
                                        <option value="Pacific/Tongatapu">(GMT+13:00) Nuku'alofa</option>
                                        <option value="Pacific/Kiritimati">(GMT+14:00) Kiritimati</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">{{Licence}}</label>
                                <div class="col-lg-3">
                                    <?php
                                    switch (config::byKey('jeedom::licence')) {
                                        case 0:
                                            echo '<span class="label label-primary">Free</span>';
                                            break;
                                        case 5:
                                            echo '<span class="label label-info">Power user</span>';
                                            break;
                                        case 10:
                                            echo '<span class="label label-success">Pro</span>';
                                            break;
                                    }
                                    ?>
                                </div>
                            </div>

                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
        <div class="panel panel-default expertModeVisible">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#config_systeme">
                        {{Système}}
                    </a>
                </h3>
            </div>
            <div id="config_systeme" class="panel-collapse collapse">
                <div class="panel-body">
                    <form class="form-horizontal">
                        <fieldset>
                            <div class="alert alert-danger">{{ATTENTION ces opérations sont risquées, vous pouvez perdre l'accès à votre systeme. Ces opérations peuvent ne pas marcher en fonction du niveau de droits de l'utilisateur www-data}}</div>
                            <a class="btn btn-danger" id="bt_haltSysteme"><i class="fa fa-stop"></i> {{Arrêter le système}}</a>
                            <a class="btn btn-warning" id="bt_rebootSysteme"><i class="fa fa-repeat"></i> {{Redémarrer le système}}</a>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>

        <div class="panel panel-default expertModeVisible">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#config_network">
                        {{Configuration réseaux}}
                    </a>
                </h3>
            </div>
            <div id="config_network" class="panel-collapse collapse">
                <div class="panel-body">
                    <form class="form-horizontal">
                        <fieldset>
                            <?php
                            if (config::byKey('jeeNetwork::mode') == 'slave') {
                                echo '<div class="form-group expertModeVisible">';
                                echo '<label class="col-lg-2 control-label">{{IP Maitre}}</label>';
                                echo '<div class="col-lg-6">';
                                echo '<span class="label label-info">' . config::byKey('jeeNetwork::master::ip') . '</span>';
                                echo '</div>';
                                echo '</div>';
                            }
                            ?>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">{{Adresse interne}}</label>
                                <div class="col-lg-3">
                                    <input type="text"  class="configKey form-control" data-l1key="internalAddr" />
                                </div>
                                <div class="col-lg-4">
                                    <div class="alert alert-info">{{Attention ne pas oublié le /jeedom après l'ip si vous l'utilisez pour vous rendre sur l'interface de jeedom}}</div>
                                </div>
                            </div>
                            <?php if (config::byKey('jeeNetwork::mode') == 'master') { ?>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">{{Adresse externe}}</label>
                                    <div class="col-lg-3">
                                        <input type="text"  class="configKey form-control" data-l1key="externalAddr" />
                                    </div>
                                    <label class="col-lg-2 control-label">{{Port externe}}</label>
                                    <div class="col-lg-2">
                                        <input type="text"  class="configKey form-control" data-l1key="externalPort" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">{{Activer l'ouverture automatique de port (UPnP)}}</label>
                                    <div class="col-lg-1">
                                        <input type="checkbox"  class="configKey" data-l1key="allowupnpn" />
                                    </div>
                                    <div class="col-lg-3">
                                        <a class="btn btn-default" id="bt_forceApplyUPnP"> {{Appliquer l'ouverture des ports}}</a>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (config::byKey('jeedom::licence') >= 5) { ?>
                                <div class="form-group expertModeVisible alert alert-danger">
                                    <label class="col-lg-2 control-label">{{Mode}}</label>
                                    <div class="col-lg-6">
                                        <?php
                                        if (config::byKey('jeeNetwork::mode') == 'master') {
                                            echo '<a class="btn btn-success changeJeeNetworkMode" data-mode="master">{{Maitre}}</a> ';
                                            echo '<a class="btn btn-default changeJeeNetworkMode" data-mode="slave">{{Esclave}}</a>';
                                        } else {
                                            echo '<a class="btn btn-default changeJeeNetworkMode" data-mode="master">{{Maitre}}</a> ';
                                            echo '<a class="btn btn-success changeJeeNetworkMode" data-mode="slave">{{Esclave}}</a>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>

        <?php if (config::byKey('jeeNetwork::mode') == 'master') { ?>
            <div class="panel panel-default expertModeVisible">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#config_memcache">
                            {{Configuration cache}}
                        </a>
                    </h3>
                </div>
                <div id="config_memcache" class="panel-collapse collapse">
                    <div class="panel-body">
                        <form class="form-horizontal">
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">{{Durée de vie memcache (secondes)}}</label>
                                    <div class="col-lg-3">
                                        <input type="text"  class="configKey form-control" data-l1key="lifetimeMemCache" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-2 control-label">{{Vider toutes les données en cache}}</label>
                                    <div class="col-lg-3">
                                        <a class="btn btn-warning" id="bt_flushMemcache">{{Vider}}</a>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-2 control-label">{{Cron persistance du cache}}</label>
                                    <div class="col-lg-3">
                                        <input type="text"  class="configKey form-control" data-l1key="persist::cron" />
                                    </div>
                                    <div class="col-lg-1">
                                        <i class="fa fa-question-circle cursor bt_pageHelp" data-name='cronSyntaxe'></i>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#config_history">
                            {{Configuration historique}}
                        </a>
                    </h3>
                </div>
                <div id="config_history" class="panel-collapse collapse">
                    <div class="panel-body">
                        <form class="form-horizontal">
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">{{Afficher statistique sur les widgets}}</label>
                                    <div class="col-lg-3">
                                        <input type="checkbox"  class="configKey" data-l1key="displayStatsWidget" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">{{Période de calcul pour min, max, moyenne (en heure)}}</label>
                                    <div class="col-lg-3">
                                        <input type="text"  class="configKey form-control" data-l1key="historyCalculPeriod" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">{{Période de calcul pour la tendance (en heure)}}</label>
                                    <div class="col-lg-3">
                                        <input type="text"  class="configKey form-control" data-l1key="historyCalculTendance" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">{{Délai avant archivage (heure)}}</label>
                                    <div class="col-lg-3">
                                        <input type="text"  class="configKey form-control" data-l1key="historyArchiveTime" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">{{Archiver par paquet de (heure)}}</label>
                                    <div class="col-lg-3">
                                        <input type="text"  class="configKey form-control" data-l1key="historyArchivePackage" />
                                    </div>
                                </div>
                                <div class="form-group alert alert-danger">
                                    <label class="col-lg-2 control-label">{{Seuil de calcul de tendance}}</label>
                                    <label class="col-lg-1 control-label">{{Min}}</label>
                                    <div class="col-lg-1">
                                        <input type="text"  class="configKey form-control" data-l1key="historyCalculTendanceThresholddMin" />
                                    </div>
                                    <label class="col-lg-1 control-label">{{Max}}</label>
                                    <div class="col-lg-1">
                                        <input type="text"  class="configKey form-control" data-l1key="historyCalculTendanceThresholddMax" />
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="panel panel-default expertModeVisible">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#configuration_cron">
                        {{Configuration crontask, scripts & deamons}}
                    </a>
                </h3>
            </div>
            <div id="configuration_cron" class="panel-collapse collapse">
                <div class="panel-body">
                    <form class="form-horizontal">
                        <fieldset>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">{{Rattrapage maximum autorisé (min, -1 pour infini)}}</label>
                                <div class="col-lg-3">
                                    <input type="text" class="configKey form-control" data-l1key="maxCatchAllow"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">{{Crontask : temps exécution max (min)}}</label>
                                <div class="col-lg-3">
                                    <input type="text" class="configKey form-control" data-l1key="maxExecTimeCrontask"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">{{Script : temps exécution max (min)}}</label>
                                <div class="col-lg-3">
                                    <input type="text" class="configKey form-control" data-l1key="maxExecTimeScript"/>
                                </div>
                            </div>
                            <div class="form-group alert alert-danger">
                                <label class="col-lg-2 control-label">{{Jeecron sleep time}}</label>
                                <div class="col-lg-3">
                                    <input type="text" class="configKey form-control" data-l1key="cronSleepTime"/>
                                </div>
                            </div>
                            <div class="form-group alert alert-danger">
                                <label class="col-lg-2 control-label">{{Deamons sleep time}}</label>
                                <div class="col-lg-3">
                                    <input type="text" class="configKey form-control" data-l1key="deamonsSleepTime"/>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>

        <div class="panel panel-default expertModeVisible">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#configuration_logMessage">
                        {{Configuration des logs & messages}}
                    </a>
                </h3>
            </div>
            <div id="configuration_logMessage" class="panel-collapse collapse">
                <div class="panel-body">
                    <form class="form-horizontal">
                        <fieldset>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">{{Ajouter un message à chaque erreur dans les logs}}</label>
                                <div class="col-lg-1">
                                    <input type="checkbox" class="configKey" data-l1key="addMessageForErrorLog" checked/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">{{Nombre de lignes maximum dans un fichier de log}}</label>
                                <div class="col-lg-3">
                                    <input type="text" class="configKey form-control" data-l1key="maxLineLog"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">{{Logs actifs}}</label>
                                <div class="col-lg-2">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" class="configKey" data-l1key="logLevel" data-l2key="debug" checked /> Debug
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" class="configKey" data-l1key="logLevel" data-l2key="info" checked /> Info
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" class="configKey" data-l1key="logLevel" data-l2key="event" checked /> Event
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" class="configKey" data-l1key="logLevel" data-l2key="error" checked /> Error
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>

        <div class="panel panel-default expertModeVisible">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#config_ldap">
                        {{Configuration LDAP}}
                    </a>
                </h3>
            </div>
            <div id="config_ldap" class="panel-collapse collapse">
                <div class="panel-body">
                    <form class="form-horizontal">
                        <fieldset>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">{{Activer l'authentification LDAP}}</label>
                                <div class="col-lg-1">
                                    <input type="checkbox" class="configKey" data-l1key="ldap:enable"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">{{Hôte}}</label>
                                <div class="col-lg-3">
                                    <input type="text"  class="configKey" data-l1key="ldap:host" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">{{Port}}</label>
                                <div class="col-lg-3">
                                    <input type="text"  class="configKey form-control" data-l1key="ldap:port" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">{{Domaine}}</label>
                                <div class="col-lg-3">
                                    <input type="text"  class="configKey form-control" data-l1key="ldap:domain" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">{{Base DN}}</label>
                                <div class="col-lg-3">
                                    <input type="text"  class="configKey form-control" data-l1key="ldap:basedn" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">{{Nom d'utilisateur}}</label>
                                <div class="col-lg-3">
                                    <input type="text"  class="configKey form-control" data-l1key="ldap:username" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">{{Mot de passe}}</label>
                                <div class="col-lg-3">
                                    <input type="password"  class="configKey form-control" data-l1key="ldap:password" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">{{Filtre (optionnel)}}</label>
                                <div class="col-lg-3">
                                    <input type="text"  class="configKey form-control" data-l1key="ldap:filter" />
                                </div>
                            </div>
                            <div class="form-group alert alert-danger">
                                <label class="col-lg-2 control-label">{{Autoriser REMOTE_USER}}</label>
                                <div class="col-lg-3">
                                    <input type="checkbox"  class="configKey" data-l1key="sso:allowRemoteUser" />
                                </div>
                            </div>
                        </fieldset>
                    </form>
                    <div class="alert alert-info">{{N'oubliez pas de sauvegarder la configuration avant de tester}}</div>
                    <a class='btn btn-default' id='bt_testLdapConnection'>{{Tester la connexion}}</a>
                </div>
            </div>
        </div>
        <?php if (config::byKey('jeeNetwork::mode') == 'master') { ?>
            <div class="panel panel-default expertModeVisible">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#configuration_convertColor">
                            {{Conversion des couleurs en html}}
                        </a>
                    </h3>
                </div>
                <div id="configuration_convertColor" class="panel-collapse collapse">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <i class="fa fa-plus-circle pull-right" id="bt_addColorConvert" style="font-size: 1.8em;"></i>
                                <table class="table table-condensed table-bordered" id="table_convertColor" >
                                    <thead>
                                        <tr>
                                            <th>{{Nom}}</th><th>{{Code HTML}}</th>
                                        </tr>
                                        <tr class="filter" style="display : none;">
                                            <td class="color"><input class="filter form-control" filterOn="color" /></td>
                                            <td class="codeHtml"><input class="filter form-control" filterOn="codeHtml" /></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default expertModeVisible">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#configuration_commandeEqlogic">
                            {{Commandes & Equipements}}
                        </a>
                    </h3>
                </div>
                <div id="configuration_commandeEqlogic" class="panel-collapse collapse">
                    <div class="panel-body">
                        <form class="form-horizontal">
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">{{Nombre d'échecs avant désactivation de l'équipement}}</label>
                                    <div class="col-lg-3">
                                        <input type="text"  class="configKey form-control" data-l1key="numberOfTryBeforeEqLogicDisable" />
                                    </div>
                                </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>

                <div class="panel panel-default expertModeVisible">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#configuration_nodeJS">
                                {{NodeJS}}
                            </a>
                        </h3>
                    </div>
                    <div id="configuration_nodeJS" class="panel-collapse collapse">
                        <div class="panel-body">
                            <form class="form-horizontal">
                                <fieldset>
                                    <div class="form-group expertModeVisible">
                                        <label class="col-lg-2 control-label">{{Port interne NodeJS}}</label>
                                        <div class="col-lg-3">
                                            <input type="text"  class="configKey" data-l1key="nodeJsInternalPort" />
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#configuration_market">
                            {{Market}}
                        </a>
                    </h3>
                </div>
                <div id="configuration_market" class="panel-collapse collapse">
                    <div class="panel-body">
                        <form class="form-horizontal">
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">{{Adresse}}</label>
                                    <div class="col-lg-2">
                                        <input class="configKey form-control" data-l1key="market::address"/>
                                    </div>
                                    <label class="col-lg-1 control-label">{{Nom d'utilisateur}}</label>
                                    <div class="col-lg-1">
                                        <input type="text"  class="configKey form-control" data-l1key="market::username" />
                                    </div>
                                    <label class="col-lg-1 control-label">{{Mot de passe}}</label>
                                    <div class="col-lg-1">
                                        <input type="password"  class="configKey form-control" data-l1key="market::password" />
                                    </div>
                                    <div class="col-lg-1">
                                        <a class="btn btn-default" id="bt_testMarketConnection">Tester</a>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="alert alert-info">{{N'oubliez pas de sauvegarder la configuration avant de tester}}</div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">{{Installer automatiquement les widgets manquants}}</label>
                                    <div class="col-lg-3">
                                        <input type="checkbox"  class="configKey" data-l1key="market::autoInstallMissingWidget" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">{{Utiliser le market comme DNS}}</label>
                                    <div class="col-lg-3">
                                        <input type="checkbox"  class="configKey" data-l1key="market::allowDNS" />
                                    </div>
                                </div>
                                <?php if (config::byKey('jeedom::licence') >= 5) { ?>
                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">{{Afficher les plugins mis en avant par le market}}</label>
                                        <div class="col-lg-3">
                                            <input type="checkbox"  class="configKey" data-l1key="market::showPromotion" />
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="form-group alert alert-danger">
                                    <label class="col-lg-2 control-label">{{Voir modules en beta (à vos risques et périls)}}</label>
                                    <div class="col-lg-3">
                                        <input type="checkbox"  class="configKey" data-l1key="market::showBetaMarket" />
                                    </div>
                                </div>

                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>

            <div class="panel panel-default expertModeVisible">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#configuration_update">
                            {{Mise à jour}}
                        </a>
                    </h3>
                </div>
                <div id="configuration_update" class="panel-collapse collapse">
                    <div class="panel-body">
                        <form class="form-horizontal">
                            <fieldset>
                                <div class="form-group expertModeVisible">
                                    <label class="col-lg-2 control-label">{{Faire une sauvegarde avant la mise à jour}}</label>
                                    <div class="col-lg-1">
                                        <input type="checkbox" class="configKey" data-l1key="update::backupBefore"/>
                                    </div>
                                </div>
                                <div class="form-group expertModeVisible alert alert-danger">
                                    <label class="col-lg-2 control-label">{{Mettre à jour automatiquement}}</label>
                                    <div class="col-lg-1">
                                        <input type="checkbox" class="configKey" data-l1key="update::auto"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">{{Branche}}</label>
                                    <div class="col-lg-2">
                                        <select class="configKey form-control" data-l1key="market::branch">
                                            <option value="stable">Stable</option>
                                            <option value="master">Developpement</option>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
            <div class="panel panel-default expertModeVisible">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#configuration_http">
                            {{HTTP}}
                        </a>
                    </h3>
                </div>
                <div id="configuration_http" class="panel-collapse collapse">
                    <div class="panel-body">
                        <form class="form-horizontal">
                            <fieldset>
                                <div class="form-group expertModeVisible">
                                    <label class="col-lg-2 control-label">{{Timeout de résolution DNS sur les requêtes HTTP}}</label>
                                    <div class="col-lg-1">
                                        <input class="configKey form-control" data-l1key="http::ping_timeout"/>
                                    </div>
                                </div>
                                <div class="form-group alert alert-danger expertModeVisible">
                                    <label class="col-lg-2 control-label">{{Désactiver la vérification du ping}}</label>
                                    <div class="col-lg-1">
                                        <input type="checkbox" class="configKey" data-l1key="http::ping_disable"/>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
            <div class="form-actions" style="height: 20px;">
                <a class="btn btn-success" id="bt_saveGeneraleConfig"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
            </div>
        </div>
    </div>

    <?php include_file("desktop", "administration", "js"); ?>
