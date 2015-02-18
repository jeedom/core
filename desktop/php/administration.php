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
                                <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Clef API}}</label>
                                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6"> 
                                    <p class="form-control-static" id="in_keyAPI"><?php echo config::byKey('api'); ?></p>
                                </div>
                                <div class="col-lg-1 col-md-2 col-sm-3"> 
                                    <a class="btn btn-default form-control" id="bt_genKeyAPI">{{Générer}}</a>
                                </div>
                            </div>
                            <?php if (config::byKey('jeeNetwork::mode') == 'master') { ?>
                                <div class="form-group expertModeVisible">
                                    <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Clef NodeJS}}</label>
                                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6"> 
                                        <p class="form-control-static" id="in_nodeJsKey"><?php echo config::byKey('nodeJsKey'); ?></p>
                                    </div>
                                    <div class="col-lg-1 col-md-2 col-sm-3"> 
                                        <a class="btn btn-default form-control" id="bt_nodeJsKey" >{{Générer}}</a>
                                    </div>
                                </div>
                                <?php } ?>
                                <div class="form-group">
                                    <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Commande d'information utilisateur}}</label>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <input type="text"  class="configKey form-control" data-l1key="emailAdmin" />
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-2">
                                        <a class="btn btn-default cursor" title="Rechercher une commande" id="bt_selectMailCmd"><i class="fa fa-list-alt"></i></a>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Langue}}</label>
                                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                                        <select class="configKey form-control" data-l1key="language">
                                            <option value="fr_FR">{{Français}}</option>
                                            <option value="en_US">{{English}}</option>
                                            <option value="de_DE">{{Deutsch}}</option>
                                            <option value="sp_ES">{{Español}}</option>
                                            <option value="ru_RU">{{Pусский}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Durée de vie des sessions (heure)}}</label>
                                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                                        <input type="text"  class="configKey form-control" data-l1key="session_lifetime" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Date et heure}}</label>
                                    <div class="col-lg-4 col-md-5 col-sm-6 col-xs-6">
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
                                    <div class="col-lg-4 col-md-5 col-sm-6 col-xs-6">
                                        <a class="btn btn-primary" id="bt_forceSyncHour"><i class="fa fa-clock-o"></i> Forcer la synchronisation de l'heure</a>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Configuration des scénarios en mode expert par défaut}}</label>
                                    <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                        <input type="checkbox" class="configKey" data-l1key="scenario::expertMode" />
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
            <div class="panel panel-danger expertModeVisible">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#config_database">
                            {{Base de données}}
                        </a>
                    </h3>
                </div>
                <div id="config_database" class="panel-collapse collapse">
                    <div class="panel-body">
                        <form class="form-horizontal">
                            <fieldset>
                                <div class="alert alert-danger">{{ATTENTION ces opérations sont risquées, vous pouvez perdre l'accès à votre système et à Jeedom. Suite à une modification de la base de données, l'équipe Jeedom se réserve le droit de refuser toute demande de support.}}</div>
                                <div class="form-group">
                                    <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Accès à l'interface d'administration}}</label>
                                    <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                        <a class="btn btn-danger" id="bt_accessDB" data-href="<?php echo jeedom::getCurrentSqlBuddyFolder() . '/index.php' ?>"><i class="fa fa-exclamation-triangle"></i> {{Se connecter}}</a>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Machine (hostname)}}</label>
                                    <div class="col-sm-1">
                                        <?php
                                        global $CONFIG;
                                        echo $CONFIG['db']['host'];
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Utilisateur}}</label>
                                    <div class="col-sm-1">
                                        <?php
                                        echo $CONFIG['db']['username'];
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Mot de passe}}</label>
                                    <div class="col-sm-1">
                                        <?php
                                        echo $CONFIG['db']['password'];
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
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#config_network">
                            {{Configuration réseaux}}
                        </a>
                    </h3>
                </div>
                <div id="config_network" class="panel-collapse collapse">
                    <div class="panel-body">
                        <form class="form-horizontal">
                            <fieldset>
                                <legend>Accès interne</legend>
                                <?php
                                if (config::byKey('jeeNetwork::mode') == 'slave') {
                                    echo '<div class="form-group expertModeVisible">';
                                    echo '<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{IP Maître}}</label>';
                                    echo '<div class="col-sm-6">';
                                    echo '<span class="label label-info">' . config::byKey('jeeNetwork::master::ip') . '</span>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                                ?>
                                <div class="form-group">
                                    <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Protocole}}</label>
                                    <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                        <select class="configKey form-control" data-l1key="internalProtocol">
                                            <option value="">Aucun</option>
                                            <option value="http://">HTTP</option>
                                            <option value="https://">HTTPS</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Adresse URL ou IP}}</label>
                                    <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                        <input type="text"  class="configKey form-control" data-l1key="internalAddr" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Complément (exemple : /jeedom)}}</label>
                                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                                        <input type="text"  class="configKey form-control" data-l1key="internalComplement" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Port}}</label>
                                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                                        <input type="number" class="configKey form-control" data-l1key="internalPort" />
                                    </div>
                                </div>
                                <legend>Accès externe</legend>
                                <?php if (config::byKey('jeeNetwork::mode') == 'master') { ?>
                                    <div class="form-group">
                                        <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Protocole}}</label>
                                        <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                            <select class="configKey form-control" data-l1key="externalProtocol">
                                                <option value="">Aucun</option>
                                                <option value="http://">HTTP</option>
                                                <option value="https://">HTTPS</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Adresse URL ou IP}}</label>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
                                            <?php if(config::byKey('market::allowDNS') == 0){
                                                echo '<input type="text"  class="configKey form-control" data-l1key="externalAddr" />';
                                            }else{
                                                echo '<input type="text"  class="configKey form-control" data-l1key="externalAddr" disabled />';
                                            }
                                            ?>
                                        </div>
                                        <?php if(config::byKey('market::allowDNS') != 0){ ?>
                                            <div class="col-lg-1 col-md-1 col-sm-2 col-xs-2">
                                                <a class="btn btn-default" href="http://market.jeedom.fr/index.php?v=d&p=profils" target="_blank">Configurer</a>
                                            </div>
                                            <?php }?>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Complément (exemple : /jeedom)}}</label>
                                            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                                                <input type="text"  class="configKey form-control" data-l1key="externalComplement" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Port}}</label>
                                            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                                                <input type="number" class="configKey form-control" data-l1key="externalPort" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Activer l'ouverture automatique des ports (UPnP)}}</label>
                                            <div class="col-sm-1">
                                                <input type="checkbox"  class="configKey" data-l1key="allowupnpn" />
                                            </div>
                                            <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                                <a class="btn btn-default" id="bt_forceApplyUPnP"> {{Appliquer UPnP}}</a>
                                            </div>
                                        </div>
                                        <?php }?>
                                        <legend>Autres</legend>
                                        <div class="form-group expertModeVisible alert alert-danger">
                                            <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Mode}}</label>
                                            <div class="col-sm-6">
                                                <?php
                                                if (config::byKey('jeeNetwork::mode') == 'master') {
                                                    echo '<a class="btn btn-success changeJeeNetworkMode" data-mode="master">{{Maître}}</a> ';
                                                    echo '<a class="btn btn-default changeJeeNetworkMode" data-mode="slave">{{Esclave}}</a>';
                                                } else {
                                                    echo '<a class="btn btn-default changeJeeNetworkMode" data-mode="master">{{Maître}}</a> ';
                                                    echo '<a class="btn btn-success changeJeeNetworkMode" data-mode="slave">{{Esclave}}</a>';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <?php
                                        if (config::byKey('jeedom::licence') >= 5 && file_exists('/etc/nginx/sites-available/default_ssl')) {
                                            echo '<div class="form-group expertModeVisible">';
                                            echo '<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Forcer le https}}</label>';
                                            echo '<div class="col-xs-1">';
                                            echo '<input type="checkbox" class="configKey" data-l1key="forceHttps" />';
                                            echo '</div>';
                                            echo '<div class="col-sm-3">';
                                            echo '<a class="btn btn-default btn-sm" target="_blank" href="https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '"><i class="fa fa-lock"></i> Tester le https</a>';
                                            echo '</div>';
                                            echo '<div class="col-sm-4 col-xs-12 alert alert-danger">';
                                            echo '{{Attention si vous n\'avez pas de HTTPS et que vous activez cette option votre jeedom ne sera plus accessible}}';
                                            echo '</div>';
                                            echo '</div>';
                                        }
                                        ?>
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
                                        {{Configuration du cache}}
                                    </a>
                                </h3>
                            </div>
                            <div id="config_memcache" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <form class="form-horizontal">
                                        <fieldset>
                                            <div class="form-group">
                                                <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Durée de vie de memcache (en secondes)}}</label>
                                                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                                    <input type="text"  class="configKey form-control" data-l1key="lifetimeMemCache" />
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Vider toutes les données en cache}}</label>
                                                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                                    <a class="btn btn-warning" id="bt_flushMemcache">{{Vider}}</a>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Cron persistance du cache}}</label>
                                                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                                    <input type="text"  class="configKey form-control" data-l1key="persist::cron" />
                                                </div>
                                                <div class="col-sm-1">
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
                                        {{Configuration de l'historique}}
                                    </a>
                                </h3>
                            </div>
                            <div id="config_history" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <form class="form-horizontal">
                                        <fieldset>
                                            <div class="form-group">
                                                <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Afficher les statistiques sur les widgets}}</label>
                                                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                                    <input type="checkbox"  class="configKey" data-l1key="displayStatsWidget" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Période de calcul pour min, max, moyenne (en heures)}}</label>
                                                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                                    <input type="text"  class="configKey form-control" data-l1key="historyCalculPeriod" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Période de calcul pour la tendance (en heures)}}</label>
                                                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                                    <input type="text"  class="configKey form-control" data-l1key="historyCalculTendance" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Délai avant archivage (en heures)}}</label>
                                                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                                    <input type="text"  class="configKey form-control" data-l1key="historyArchiveTime" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Archiver par paquet de (en heures)}}</label>
                                                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                                    <input type="text"  class="configKey form-control" data-l1key="historyArchivePackage" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Seuil de calcul de tendance bas}}</label>
                                                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                                    <input type="text"  class="configKey form-control" data-l1key="historyCalculTendanceThresholddMin" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Seuil de calcul de tendance haut}}</label>
                                                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
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
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#configuration_interact">
                                        {{Configuration des interactions}}
                                    </a>
                                </h3>
                            </div>
                            <div id="configuration_interact" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <form class="form-horizontal">
                                        <fieldset>
                                            <div class="form-group">
                                                <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Sensibilité (par défaut 10)}}</label>
                                                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                                    <input type="text" class="configKey form-control" data-l1key="interact::confidence"/>
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
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#configuration_cron">
                                        {{Configuration des crontask, scripts & démons}}
                                    </a>
                                </h3>
                            </div>
                            <div id="configuration_cron" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <form class="form-horizontal">
                                        <fieldset>
                                            <div class="form-group">
                                                <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Rattrapage maximum autorisé (en minutes, -1 pour infini)}}</label>
                                                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                                    <input type="text" class="configKey form-control" data-l1key="maxCatchAllow"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Crontask : temps d'exécution max (en minutes)}}</label>
                                                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                                    <input type="text" class="configKey form-control" data-l1key="maxExecTimeCrontask"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Script : temps d'exécution max (en minutes)}}</label>
                                                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                                    <input type="text" class="configKey form-control" data-l1key="maxExecTimeScript"/>
                                                </div>
                                            </div>
                                            <div class="form-group alert alert-danger">
                                                <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Temps de sommeil Jeecron}}</label>
                                                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                                    <input type="text" class="configKey form-control" data-l1key="cronSleepTime"/>
                                                </div>
                                            </div>
                                            <div class="form-group alert alert-danger">
                                                <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Temps de sommeil des Démons}}</label>
                                                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
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
                                                <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Ajouter un message à chaque erreur dans les logs}}</label>
                                                <div class="col-sm-1">
                                                    <input type="checkbox" class="configKey" data-l1key="addMessageForErrorLog" checked/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Nombre de lignes maximum dans un fichier de log}}</label>
                                                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                                    <input type="text" class="configKey form-control" data-l1key="maxLineLog"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Logs actifs}}</label>
                                                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
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
                                                <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Activer l'authentification LDAP}}</label>
                                                <div class="col-sm-1">
                                                    <input type="checkbox" class="configKey" data-l1key="ldap:enable"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Hôte}}</label>
                                                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                                    <input type="text"  class="configKey form-control" data-l1key="ldap:host" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Port}}</label>
                                                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                                    <input type="text"  class="configKey form-control" data-l1key="ldap:port" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Domaine}}</label>
                                                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                                    <input type="text"  class="configKey form-control" data-l1key="ldap:domain" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Base DN}}</label>
                                                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                                    <input type="text"  class="configKey form-control" data-l1key="ldap:basedn" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Nom d'utilisateur}}</label>
                                                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                                    <input type="text"  class="configKey form-control" data-l1key="ldap:username" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Mot de passe}}</label>
                                                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                                    <input type="password"  class="configKey form-control" data-l1key="ldap:password" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Filtre (optionnel)}}</label>
                                                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                                    <input type="text"  class="configKey form-control" data-l1key="ldap:filter" />
                                                </div>
                                            </div>
                                            <div class="form-group alert alert-danger">
                                                <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Autoriser REMOTE_USER}}</label>
                                                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                                    <input type="checkbox"  class="configKey" data-l1key="sso:allowRemoteUser" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6"></div>
                                                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                                    <a class="btn btn-default" id="bt_testLdapConnection"><i class="fa fa-cube"></i> Tester</a>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </form>
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
                                            <div class="col-sm-6">
                                                <i class="fa fa-plus-circle pull-right cursor" id="bt_addColorConvert" style="font-size: 1.8em;"></i>
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
                                                    <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Nombre d'échecs avant désactivation de l'équipement}}</label>
                                                    <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
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
                                                    <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Port interne NodeJS}}</label>
                                                    <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                                        <input type="text"  class="configKey form-control" data-l1key="nodeJsInternalPort" />
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
                                                    <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Adresse}}</label>
                                                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                                                        <input class="configKey form-control" data-l1key="market::address"/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Nom d'utilisateur}}</label>
                                                    <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                                        <input type="text"  class="configKey form-control" data-l1key="market::username" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Mot de passe}}</label>
                                                    <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                                        <input type="password"  class="configKey form-control" data-l1key="market::password" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6"></div>
                                                    <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                                        <a class="btn btn-default" id="bt_testMarketConnection"><i class="fa fa-cube"></i> Tester</a>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Installer automatiquement les widgets manquants}}</label>
                                                    <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                                        <input type="checkbox"  class="configKey" data-l1key="market::autoInstallMissingWidget" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Utiliser le market comme DNS}}</label>
                                                    <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                                        <input type="checkbox"  class="configKey" data-l1key="market::allowDNS" />
                                                    </div>
                                                </div>
                                                <?php if (config::byKey('jeedom::licence') >= 5) { ?>
                                                    <div class="form-group">
                                                        <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Afficher les plugins mis en avant par le market}}</label>
                                                        <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                                            <input type="checkbox"  class="configKey" data-l1key="market::showPromotion" />
                                                        </div>
                                                    </div>
                                                    <?php } ?>
                                                    <div class="form-group alert alert-danger">
                                                        <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Voir les modules en beta (à vos risques et périls)}}</label>
                                                        <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
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
                                                        <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Faire une sauvegarde avant la mise à jour}}</label>
                                                        <div class="col-sm-1">
                                                            <input type="checkbox" class="configKey" data-l1key="update::backupBefore"/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group expertModeVisible alert alert-danger">
                                                        <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Mettre à jour automatiquement}}</label>
                                                        <div class="col-sm-1">
                                                            <input type="checkbox" class="configKey" data-l1key="update::auto"/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Branche}}</label>
                                                        <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                                            <select class="configKey form-control" data-l1key="market::branch">
                                                                <option value="stable">{{Stable}}</option>
                                                                <option value="master">{{Développement}}</option>
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
                                                        <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Timeout de résolution DNS sur les requêtes HTTP}}</label>
                                                        <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                                            <input class="configKey form-control" data-l1key="http::ping_timeout"/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group alert alert-danger expertModeVisible">
                                                        <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Désactiver la vérification du ping}}</label>
                                                        <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
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
