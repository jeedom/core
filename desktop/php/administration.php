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
                        <i class="fa fa-wrench"></i>  {{Configuration générale}}
                    </a>
                </h3>
            </div>
            <div id="config_generale" class="panel-collapse collapse in">
                <div class="panel-body">
                    <form class="form-horizontal">
                        <fieldset>
                          <div class="form-group">
                            <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label help" data-help="{{Nom de votre Jeedom (utilisé notamment par le market)}}">{{Nom de votre Jeedom}}</label>
                            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                                <input type="text" class="configKey form-control" data-l1key="name" />
                            </div>
                        </div>
                        <div class="form-group expertModeVisible">
                            <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label help" data-help="{{Clef API globale de Jeedom}}">{{Clef API}}</label>
                            <div class="col-lg-4 col-md-5 col-sm-6 col-xs-6">
                                <textarea id="in_keyAPI" style="width:100%;" rows="5" disabled><?php echo config::byKey('api'); ?></textarea>
                            </div>
                            <div class="col-lg-2 col-md-3 col-sm-3">
                                <a class="btn btn-default form-control" id="bt_genKeyAPI"><i class="fa fa-refresh"></i> {{Générer}}</a>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label help" data-help="{{Indique votre type de matériel}}">{{Système}}</label>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <span class="label label-info" style="font-size : 1em;"><?php echo jeedom::getHardwareName() ?></span>
                            </div>
                            <div class="col-lg-2 col-md-3 col-sm-3">
                                <a class="btn btn-default form-control" id="bt_resetHardwareType"><i class="fa fa-refresh"></i> {{Rafraîchir}}</a>
                            </div>
                        </div>
                        <div class="form-group expertModeVisible">
                            <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label help" data-help="{{Clef d'installation qui permet d'identifier votre Jeedom quand il communique avec le market}}">{{Clef d'installation}}</label>
                            <div class="col-lg-4 col-md-5 col-sm-6 col-xs-6">
                                <span class="label label-info" style="font-size : 1em;"><?php echo jeedom::getHardwareKey() ?></span>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-2">
                                <a class="btn btn-default form-control" id="bt_resetHwKey"><i class="fa fa-refresh"></i> {{Remise à zéro}}</a>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label help" data-help="{{Langue de votre Jeedom}}">{{Langue}}</label>
                            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                                <select class="configKey form-control" data-l1key="language">
                                    <option value="fr_FR">Français</option>
                                    <option value="en_US">English</option>
                                    <option value="de_DE">Deutsch</option>
                                    <option value="es_ES">Español</option>
                                    <option value="ru_RU">Pусский</option>
                                    <option value="id_ID">Bahasa Indonesia</option>
                                    <option value="it_IT">Italiano</option>
                                </select>
                            </div>
                            <div class="expertModeVisible">
                              <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Générer les traductions}}</label>
                              <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                                  <input type="checkbox" class="configKey tooltips bootstrapSwitch" data-l1key="generateTranslation" title="{{Option pour les développeurs permettant à Jeedom de générer les phrases à traduire}}" />
                              </div>
                          </div>
                      </div>
                      <div class="form-group">
                        <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label help" data-help="{{Durée de vie de votre connexion à Jeedom si vous n'avez pas coché la case enregistrer cet ordinateur}}">{{Durée de vie des sessions (heure)}}</label>
                        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                            <input type="text"  class="configKey form-control" data-l1key="session_lifetime" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label help" data-help="{{Fuseau horaire de votre Jeedom}}">{{Date et heure}}</label>
                        <div class="col-lg-4 col-md-5 col-sm-6 col-xs-6">
                            <select class="configKey form-control" data-l1key="timezone">
                                <option value="Pacific/Midway">(GMT-11:00) Midway Island, Samoa</option>
                                <option value="Pacific/Tahiti">(GMT-10:00) Pacific/Tahiti</option>
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
                            <a class="btn btn-primary" id="bt_forceSyncHour"><i class="fa fa-clock-o"></i> {{Forcer la synchronisation de l'heure}}</a>
                        </div>
                    </div>
                    <div class="form-group expertModeVisible">
                        <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label help" data-help="{{Permet d'ajouter un serveur de temps à Jeedom utilisé lorsque Jeedom force la synchronisation de l'heure}}">{{Serveur de temps optionnel}}</label>
                        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                            <input type="text"  class="configKey form-control" data-l1key="ntp::optionalServer" />
                        </div>
                    </div>
                    <div class="form-group expertModeVisible">
                        <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label help" data-help="{{Indique à Jeedom de ne pas prendre en compte l'heure du système}}">{{Ignorer la vérification de l'heure}}</label>
                        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                           <input type="checkbox" class="configKey bootstrapSwitch" data-l1key="ignoreHourCheck" />
                       </div>
                   </div>
                   <div class="form-group expertModeVisible has-error">
                    <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label help" data-help="{{Permet de passer Jeedom en mode exclave.}}">{{Mode}}</label>
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
           </fieldset>
       </form>
   </div>
</div>
</div>

<div class="panel panel-default expertModeVisible">
    <div class="panel-heading">
        <h3 class="panel-title">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#config_component">
                <i class="fa fa-home"></i>  {{Composants Jeedom}}
            </a>
        </h3>
    </div>
    <div id="config_component" class="panel-collapse collapse">
        <div class="panel-body">
            <form class="form-horizontal">
                <fieldset>
                 <div class="form-group expertModeVisible">
                    <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Activer la gestion des droits avancés}}</label>
                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                        <input type="checkbox" class="configKey bootstrapSwitch" data-l1key="rights::enable" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 col-xs-6 control-label">{{Activer la sécurité anti-piratage}}</label>
                    <div class="col-sm-3 col-xs-6">
                        <input type="checkbox" class="configKey bootstrapSwitch" data-l1key="security::enable"/>
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
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#config_system">
                <i class="fa fa-terminal"></i>  {{Système (OS)}}
            </a>
        </h3>
    </div>
    <div id="config_system" class="panel-collapse collapse">
        <div class="panel-body">
            <form class="form-horizontal">
                <fieldset>
                    <div class="alert alert-danger">{{ATTENTION : ces opérations sont risquées, vous pouvez perdre l'accès à votre système et à Jeedom. L'équipe Jeedom se réserve le droit de refuser toute demande de support en cas de mauvaise manipulation.}}</div>
                    <div class="form-group">
                        <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Accès à l'interface d'administration}}</label>
                        <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                            <a class="btn btn-danger" id="bt_accessSystemAdministration"><i class="fa fa-exclamation-triangle"></i> {{Lancer}}</a>
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
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#config_database">
                <i class="fa fa-database"></i>  {{Base de données}}
            </a>
        </h3>
    </div>
    <div id="config_database" class="panel-collapse collapse">
        <div class="panel-body">
            <form class="form-horizontal">
                <fieldset>
                    <div class="alert alert-danger">{{ATTENTION : ces opérations sont risquées, vous pouvez perdre l'accès à votre système et à Jeedom. Suite à une modification de la base de données, l'équipe Jeedom se réserve le droit de refuser toute demande de support.}}</div>
                    <div class="form-group">
                        <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Accès à l'interface d'administration}}</label>
                        <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                            <a class="btn btn-danger" id="bt_accessDB" data-href="<?php echo jeedom::getCurrentAdminerFolder() . '/index.php' ?>"><i class="fa fa-exclamation-triangle"></i> {{Se connecter}}</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Machine (hostname)}}</label>
                        <div class="col-sm-1">
                            <?php
global $CONFIG;
echo $CONFIG['db']['host'] . ':' . $CONFIG['db']['port'];
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

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#config_network">
                <i class="fa fa-rss"></i>  {{Configuration réseaux}}
            </a>
        </h3>
    </div>
    <div id="config_network" class="panel-collapse collapse">
        <div class="panel-body">
            <form class="form-horizontal">
                <fieldset>
                    <div class="alert alert-warning">{{Attention : cette configuration n'est là que pour informer Jeedom de sa configuration réseau et n'a aucun impact sur les ports ou l'IP réellement utilisés pour joindre Jeedom}}</div>
                    <div class="row">
                        <div class="col-sm-6">
                           <legend>{{Accès interne}}</legend>
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
                            <label class="col-xs-3 control-label">{{Protocole}}</label>
                            <div class="col-xs-3">
                                <select class="configKey form-control" data-l1key="internalProtocol">
                                    <option value="">Aucun</option>
                                    <option value="http://">HTTP</option>
                                    <option value="https://">HTTPS</option>
                                </select>
                            </div>
                            <label class="col-xs-3 control-label">{{Port}}</label>
                            <div class="col-xs-3">
                                <input type="number"class="configKey form-control" data-l1key="internalPort" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-3 control-label">{{Adresse IP}}</label>
                            <div class="col-xs-3">
                                <input type="text" class="configKey form-control" data-l1key="internalAddr" />
                            </div>
                            <label class="col-xs-3 control-label">{{Complément}}</label>
                            <div class="col-xs-3">
                                <input type="text" class="configKey form-control" data-l1key="internalComplement" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-3 control-label">{{Statut}}</label>
                            <div class="col-xs-8">
                              <?php
if (network::test('internal')) {
	echo '<span class="label label-success" style="font-size : 1em;">{{OK}}</span>';
} else {
	echo '<span class="label label-warning tooltips">{{NOK}}</span>';
}
?>
                       </div>
                   </div>
               </div>
               <div class="col-sm-6">
                <legend>{{Accès externe}}</legend>
                <div class="form-group">
                    <label class="col-xs-3 control-label">{{Protocole}}</label>
                    <div class="col-xs-3">
                        <select class="configKey form-control" data-l1key="externalProtocol">
                            <option value="">Aucun</option>
                            <option value="http://">HTTP</option>
                            <option value="https://">HTTPS</option>
                        </select>
                    </div>
                    <label class="col-xs-3 control-label">{{Port}}</label>
                    <div class="col-xs-3">
                        <input type="number" class="configKey form-control" data-l1key="externalPort" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-3 control-label">{{Adresse URL ou IP}}</label>
                    <div class="col-xs-3">
                        <input type="text" class="configKey form-control" data-l1key="externalAddr" />
                    </div>
                    <label class="col-xs-3 control-label">{{Complément}}</label>
                    <div class="col-xs-3">
                        <input type="text"  class="configKey form-control" data-l1key="externalComplement" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-3 control-label">{{Statut}}</label>
                    <div class="col-xs-3">
                      <?php
if (network::test('external')) {
	echo '<span class="label label-success" style="font-size : 1em;">{{OK}}</span>';
} else {
	echo '<span class="label label-warning tooltips">{{NOK}}</span>';
}
?>
               </div>
           </div>
       </div>
   </div>

   <div class="row">
    <div class="col-sm-6">
        <legend>{{Gestion avancée}}</legend>
        <div class="form-group expertModeVisible has-error">
            <label class="col-xs-4 control-label">{{Désactiver la gestion du réseau par Jeedom}}</label>
            <div class="col-xs-8">
                <input type="checkbox" class="configKey bootstrapSwitch" data-l1key="network::disableMangement" />
            </div>
        </div>
        <?php
foreach (array('eth0', 'wlan0', 'bond0') as $value) {
	$mac = network::getInterfaceMac($value);
	if ($mac !== false) {
		echo '<div class="form-group expertModeVisible">';
		echo '<label class="col-xs-4 control-label">{{Addresse mac}} ' . $value . '</label>';
		echo '<div class="col-xs-8">';
		echo '<span class="label label-primary" style="font-size:1em;">' . $mac . '</span>';
		echo '</div>';
		echo '</div>';
	}
}
?>
  </div>
  <div class="col-sm-6">
    <?php if (config::byKey('jeeNetwork::mode') != 'slave') {
	foreach (repo::all() as $key => $value) {
		if (!isset($value['scope']['proxy']) || $value['scope']['proxy'] == false) {
			continue;
		}
		if (config::byKey($key . '::enable') == 0) {
			continue;
		}
		echo '<legend>Proxy ' . $value['name'] . '</legend>';
		if (config::byKey('dns::token') == '') {
			echo '<div class="alert alert-warning">{{Attention : cette fonctionnalité n\'est pas disponible dans le service pack community (voir votre service pack sur votre page profil sur le market)}}</div>';
			continue;
		}
		echo '<div class="form-group">';
		echo '<label class="col-xs-4 control-label">{{Utiliser les DNS Jeedom}}</label>';
		echo '<div class="col-xs-8">';
		echo '<input type="checkbox" class="configKey bootstrapSwitch" data-l1key="' . $key . '::allowDNS" />';
		echo '</div>';
		echo '</div>';
		echo '<div class="form-group">';
		echo '<label class="col-xs-4 control-label">{{Statut DNS}}</label>';
		echo '<div class="col-xs-8">';
		if (config::byKey('market::allowDNS') == 1 && network::dns_run()) {
			echo '<span class="label label-success" style="font-size : 1em;">{{Démarré : }} <a href="' . network::getNetworkAccess('external') . '" target="_blank" style="color:white;text-decoration: underline;">' . network::getNetworkAccess('external') . '</a></span>';
		} else {
			echo '<span class="label label-warning tooltips" title="{{Normal si vous n\'avez pas coché la case : Utiliser les DNS Jeedom}}">{{Arrêté}}</span>';
		}
		echo '</div>';
		echo '</div>';
		echo '<div class="form-group">';
		echo '<label class="col-xs-4 control-label">{{Gestion}}</label>';
		echo '<div class="col-xs-8">';
		echo '<a class="btn btn-success" id="bt_restartDns"><i class=\'fa fa-play\'></i> {{(Re)démarrer}}</a>';
		echo '<a class="btn btn-danger" id="bt_haltDns"><i class=\'fa fa-stop\'></i> {{Arrêter}}</a>';
		echo '</div>';
		echo '</div>';
	}
}
?>


</div>
</div>

</fieldset>
</form>
</div>
</div>
</div>

<?php if (config::byKey('jeeNetwork::mode') == 'master') {
	?>

  <div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#config_color">
                <i class="fa fa-tint"></i>  {{Configuration des couleurs}}
            </a>
        </h3>
    </div>
    <div id="config_color" class="panel-collapse collapse">
        <form class="form-horizontal">
            <fieldset>
               <div  style="margin-left:10px;">
                <?php
foreach (jeedom::getConfiguration('eqLogic:category') as $key => $category) {
		echo '<legend>' . $category['name'] . '</legend>';
		echo '<div class="form-group">';
		echo '<label class="col-sm-3 control-label">{{Dashboard couleur de fond}}</label>';
		echo '<div class="col-sm-2">';
		echo '<input type="color" class="configKey form-control cursor noSet" data-l1key="eqLogic:category:' . $key . ':color" value="' . $category['color'] . '" />';
		echo '</div>';
		echo '<div class="col-sm-1">';
		echo '<a class="btn btn-default bt_resetColor tooltips" data-l1key="eqLogic:category:' . $key . ':color" title="{{Remettre par défaut}}"><i class="fa fa-times"></i></a>';
		echo '</div>';
		echo '<label class="col-sm-3 control-label">{{Dashboard couleur commande}}</label>';
		echo '<div class="col-sm-2">';
		echo '<input type="color" class="configKey form-control cursor noSet" data-l1key="eqLogic:category:' . $key . ':cmdColor" value="' . $category['cmdColor'] . '" />';
		echo '</div>';
		echo '<div class="col-sm-1">';
		echo '<a class="btn btn-default bt_resetColor tooltips" data-l1key="eqLogic:category:' . $key . ':cmdColor" title="{{Remettre par défaut}}"><i class="fa fa-times"></i></a>';
		echo '</div>';
		echo '</div>';
		echo '<div class="form-group">';
		echo '<label class="col-sm-3 control-label">{{Mobile couleur de fond}}</label>';
		echo '<div class="col-sm-2">';
		echo '<input type="color" class="configKey form-control cursor noSet" data-l1key="eqLogic:category:' . $key . ':mcolor" value="' . $category['mcolor'] . '"/>';
		echo '</div>';
		echo '<div class="col-sm-1">';
		echo '<a class="btn btn-default bt_resetColor tooltips" data-l1key="eqLogic:category:' . $key . ':mcolor" title="{{Remettre par défaut}}"><i class="fa fa-times"></i></a>';
		echo '</div>';
		echo '<label class="col-sm-3 control-label">{{Mobile couleur commande}}</label>';
		echo '<div class="col-sm-2">';
		echo '<input type="color" class="configKey form-control cursor noSet" data-l1key="eqLogic:category:' . $key . ':mcmdColor" value="' . $category['mcmdColor'] . '" />';
		echo '</div>';
		echo '<div class="col-sm-1">';
		echo '<a class="btn btn-default bt_resetColor tooltips" data-l1key="eqLogic:category:' . $key . ':mcmdColor" title="{{Remettre par défaut}}"><i class="fa fa-times"></i></a>';
		echo '</div>';
		echo '</div>';
	}
	?>
          </div>
      </fieldset>
  </form>

</div>
</div>


<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#config_cmd">
                <i class="icon divers-table29"></i>  {{Configuration des commandes}}
            </a>
        </h3>
    </div>
    <div id="config_cmd" class="panel-collapse collapse">
        <div class="panel-body">
            <legend>{{Historique}}</legend>
            <form class="form-horizontal">
                <fieldset>
                    <div class="form-group">
                        <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Afficher les statistiques sur les widgets}}</label>
                        <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                            <input type="checkbox"  class="configKey bootstrapSwitch" data-l1key="displayStatsWidget" />
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
                    <div class="form-group">
                        <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Période d'affichage des graphiques par défaut}}</label>
                        <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                            <select  class="configKey form-control" data-l1key="history::defautShowPeriod" >
                                <option value="-6 month">6 mois</option>
                                <option value="-3 month">3 mois</option>
                                <option value="-1 month">1 mois</option>
                                <option value="-1 week">1 semaine</option>
                                <option value="-1 day">1 jour</option>
                            </select>
                        </div>
                    </div>
                </fieldset>
            </form>
            <legend>{{Push}}</legend>
            <form class="form-horizontal">
                <fieldset>
                   <div class="form-group">
                    <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{URL de push globale}}</label>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <input type="text"  class="configKey form-control tooltips" data-l1key="cmdPushUrl" title="{{Mettez ici l'URL à appeler lors d'une mise à jour de la valeur des commandes. Vous pouvez utiliser les tags suivants : #value# (valeur de la commande), #cmd_id# (id de la commande) et #cmd_name# (nom de la commande)}}"/>
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
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#config_cache">
                <i class="fa fa-hdd-o"></i> {{Configuration du cache}}
            </a>
        </h3>
    </div>
    <div id="config_cache" class="panel-collapse collapse">
        <div class="panel-body">
            <form class="form-horizontal">
                <fieldset>
                    <div class="alert alert-info">
                        {{Attention toute modification du moteur de cache nécessite un redémarrage de Jeedom}}
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Moteur de cache}}</label>
                        <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                            <select type="text"  class="configKey form-control" data-l1key="cache::engine" >
                                <option value="FilesystemCache">{{Système de fichier (/tmp/jeedom-cache)}}</option>
                                <?php if (class_exists('memcached')) {?>
                                <option value="MemcachedCache">{{Memcached}}</option>
                                <?php }
	?>
                                <?php if (class_exists('redis')) {?>
                                <option value="RedisCache">{{Redis}}</option>
                                <?php }
	?>
                            </select>
                        </div>
                    </div>
                    <div class="cacheEngine MemcachedCache">
                        <div class="form-group">
                            <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Adresse Memcache}}</label>
                            <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                <input type="text"  class="configKey form-control" data-l1key="cache::memcacheaddr" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Port Memcache}}</label>
                            <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                <input type="text"  class="configKey form-control" data-l1key="cache::memcacheport" />
                            </div>
                        </div>
                    </div>
                    <div class="cacheEngine RedisCache">
                        <div class="form-group">
                            <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Adresse Redis}}</label>
                            <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                <input type="text"  class="configKey form-control" data-l1key="cache::redisaddr" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Port redis}}</label>
                            <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                <input type="text"  class="configKey form-control" data-l1key="cache::redisport" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Vider toutes les données en cache}}</label>
                        <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                            <a class="btn btn-warning" id="bt_flushMemcache"><i class="fa fa-trash"></i> {{Vider}}</a>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>
<?php }
?>

<div class="panel panel-default expertModeVisible">
    <div class="panel-heading">
        <h3 class="panel-title">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#configuration_interact">
                <i class="fa fa-microphone"></i>  {{Configuration des interactions}}
            </a>
        </h3>
    </div>
    <div id="configuration_interact" class="panel-collapse collapse">
        <div class="panel-body">
            <form class="form-horizontal">
                <fieldset>
                    <div class="alert alert-info">
                        {{Plus la sensibilité est basse (proche de 1) plus la corrrespondance doit être exacte}}
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Sensibilité}}</label>
                        <div class="col-lg-6 col-md-8 col-sm-8 col-xs-6">
                         <div class="input-group">
                          <span class="input-group-addon">1 mot</span>
                          <input type="text" class="configKey form-control" data-l1key="interact::confidence1"/>
                          <span class="input-group-addon">2 mots</span>
                          <input type="text" class="configKey form-control" data-l1key="interact::confidence2"/>
                          <span class="input-group-addon">3 mots</span>
                          <input type="text" class="configKey form-control" data-l1key="interact::confidence3"/>
                          <span class="input-group-addon">> 3 mots</span>
                          <input type="text" class="configKey form-control" data-l1key="interact::confidence"/>
                      </div>

                  </div>
              </div>
              <div class="form-group">
                <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Ne pas répondre si l'interaction n'est pas comprise}}</label>
                <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                    <input type="checkbox" class="configKey bootstrapSwitch" data-l1key="interact::noResponseIfEmpty"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Regex général d'exclusion pour les interactions}}</label>
                <div class="col-lg-10 col-md-9 col-sm-8 col-xs-6">
                    <textarea type="text" class="configKey form-control" data-l1key="interact::regexpExcludGlobal"></textarea>
                </div>
            </div>
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
        </fieldset>
    </form>
</div>
</div>
</div>

<div class="panel panel-default expertModeVisible">
    <div class="panel-heading">
        <h3 class="panel-title">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#configuration_logMessage">
                <i class="fa fa-pencil-square-o"></i> {{Configuration des logs & messages}}
            </a>
        </h3>
    </div>
    <div id="configuration_logMessage" class="panel-collapse collapse">
        <div class="panel-body">
            <form class="form-horizontal">
                <fieldset>
                    <legend>{{Messages}}</legend>
                    <div class="form-group">
                        <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Ajouter un message à chaque erreur dans les logs}}</label>
                        <div class="col-sm-1">
                            <input type="checkbox" class="configKey bootstrapSwitch" data-l1key="addMessageForErrorLog" checked/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Commande d'information utilisateur}}</label>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                         <div class="input-group">
                            <input type="text"  class="configKey form-control" data-l1key="emailAdmin" />
                            <span class="input-group-btn">
                                <a class="btn btn-default cursor" title="Rechercher une commande" id="bt_selectMailCmd"><i class="fa fa-list-alt"></i></a>
                            </span>
                        </div>
                    </div>
                </div>
                <legend>{{Log}}</legend>
                <div class="form-group">
                    <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Moteur de log}}</label>
                    <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                        <select class="configKey form-control" data-l1key="log::engine">
                            <option value="StreamHandler">{{Defaut}}</option>
                            <option value="SyslogHandler">{{Syslog}}</option>
                            <option value="SyslogUdp">{{SyslogUdp}}</option>
                        </select>
                    </div>
                </div>
                <div class="logEngine SyslogUdp">
                    <div class="form-group">
                        <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Adresse syslog udp}}</label>
                        <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                            <input type="text"  class="configKey form-control" data-l1key="log::syslogudphost" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Port syslog udp}}</label>
                        <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                            <input type="text"  class="configKey form-control" data-l1key="log::syslogudpport" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Format des logs}}</label>
                    <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                        <input type="text" class="configKey form-control" data-l1key="log::formatter" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Nombre de lignes maximum dans un fichier de log}}</label>
                    <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                        <input type="text" class="configKey form-control" data-l1key="maxLineLog"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Niveau de log par défaut}}</label>
                    <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                        <select class="configKey form-control" data-l1key="log::level">
                            <option value="100">{{Debug}}</option>
                            <option value="200">{{Info}}</option>
                            <option value="250">{{Notice}}</option>
                            <option value="300">{{Warning}}</option>
                            <option value="400">{{Erreur}}</option>
                        </select>
                    </div>
                </div>
                <table class="table table-condensed table-striped">
                    <thead>
                        <tr>
                            <th>{{Nom}}</th>
                            <th>{{Aucun}}</th>
                            <th>{{Défaut}}</th>
                            <th>{{Debug}}</th>
                            <th>{{Info}}</th>
                            <th>{{Notice}}</th>
                            <th>{{Warning}}</th>
                            <th>{{Erreur}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

$other_log = array('scenario', 'plugin', 'market', 'api', 'connection');
foreach ($other_log as $name) {
	echo '<tr>';
	echo '<td>' . ucfirst($name) . '</td>';
	echo '<td><input type="radio" name="rd_logupdate' . $name . '" class="configKey" data-l1key="log::level::' . $name . '" data-l2key="1000" /></td>';
	echo '<td><input type="radio" name="rd_logupdate' . $name . '" class="configKey" data-l1key="log::level::' . $name . '" data-l2key="default" /></td>';
	echo '<td><input type="radio" name="rd_logupdate' . $name . '" class="configKey" data-l1key="log::level::' . $name . '" data-l2key="100" /></td>';
	echo '<td><input type="radio" name="rd_logupdate' . $name . '" class="configKey" data-l1key="log::level::' . $name . '" data-l2key="200" /></td>';
	echo '<td><input type="radio" name="rd_logupdate' . $name . '" class="configKey" data-l1key="log::level::' . $name . '" data-l2key="250" /></td>';
	echo '<td><input type="radio" name="rd_logupdate' . $name . '" class="configKey" data-l1key="log::level::' . $name . '" data-l2key="300" /></td>';
	echo '<td><input type="radio" name="rd_logupdate' . $name . '" class="configKey" data-l1key="log::level::' . $name . '" data-l2key="400" /></td>';
	echo '</tr>';
}
foreach (plugin::listPlugin(true) as $plugin) {
	echo '<tr>';
	echo '<td>' . $plugin->getName() . '</td>';
	echo '<td><input type="radio" name="rd_logupdate' . $plugin->getId() . '" class="configKey" data-l1key="log::level::' . $plugin->getId() . '" data-l2key="1000" /></td>';
	echo '<td><input type="radio" name="rd_logupdate' . $plugin->getId() . '" class="configKey" data-l1key="log::level::' . $plugin->getId() . '" data-l2key="default" /></td>';
	echo '<td><input type="radio" name="rd_logupdate' . $plugin->getId() . '" class="configKey" data-l1key="log::level::' . $plugin->getId() . '" data-l2key="100" /></td>';
	echo '<td><input type="radio" name="rd_logupdate' . $plugin->getId() . '" class="configKey" data-l1key="log::level::' . $plugin->getId() . '" data-l2key="200" /></td>';
	echo '<td><input type="radio" name="rd_logupdate' . $plugin->getId() . '" class="configKey" data-l1key="log::level::' . $plugin->getId() . '" data-l2key="250" /></td>';
	echo '<td><input type="radio" name="rd_logupdate' . $plugin->getId() . '" class="configKey" data-l1key="log::level::' . $plugin->getId() . '" data-l2key="300" /></td>';
	echo '<td><input type="radio" name="rd_logupdate' . $plugin->getId() . '" class="configKey" data-l1key="log::level::' . $plugin->getId() . '" data-l2key="400" /></td>';
	echo '</tr>';
}
?>
                   </tbody>
               </table>
           </fieldset>
       </form>
   </div>
</div>
</div>

<div class="panel panel-default expertModeVisible">
    <div class="panel-heading">
        <h3 class="panel-title">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#config_ldap">
                <i class="icon personne-toilet1"></i>  {{Configuration LDAP}}
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
                            <input type="checkbox" class="configKey bootstrapSwitch" data-l1key="ldap:enable"/>
                        </div>
                    </div>
                    <div id="div_config_ldap">
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
                        <div class="form-group has-error">
                            <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Autoriser REMOTE_USER}}</label>
                            <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                <input type="checkbox"  class="configKey bootstrapSwitch" data-l1key="sso:allowRemoteUser" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6"></div>
                            <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                                <a class="btn btn-default" id="bt_testLdapConnection"><i class="fa fa-cube"></i> Tester</a>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>
<?php if (config::byKey('jeeNetwork::mode') == 'master') {?>
    <div class="panel panel-default expertModeVisible">
        <div class="panel-heading">
            <h3 class="panel-title">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#configuration_commandeEqlogic">
                    <i class="icon divers-svg"></i>  {{Configuration des équipements}}
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
                        <div class="form-group">
                            <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Seuil des piles}}</label>
                            <label class="col-lg-1 col-md-1 col-sm-1 col-xs-1 eqLogicAttr label label-danger" style="font-size : 1.4em">{{Danger}}</label>
                            <div class="col-xs-1">
                               <input class="configKey form-control" data-l1key="battery::danger" />
                           </div>
                           <label class="col-xs-1 label label-warning" style="font-size : 1.4em">{{Warning}}</label>
                           <div class="col-xs-1">
                               <input class="configKey form-control" data-l1key="battery::warning" />
                           </div>
                           <label class="col-xs-1 label label-success" style="font-size : 1.4em">{{Ok}}</label>
                       </div>
                   </fieldset>
               </form>
           </div>
       </div>
   </div>
   <?php }
?>

   <div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#configuration_market">
                <i class="fa fa-credit-card"></i>  {{Repositories, plugins et mise à jour}}
            </a>
        </h3>
    </div>
    <div id="configuration_market" class="panel-collapse collapse">
        <div class="panel-body">
            <form class="form-horizontal">
                <fieldset>
                    <legend>{{Mise à jour de jeedom}}</legend>
                    <div class="form-group">
                        <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Branche}}</label>
                        <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                            <select class="configKey form-control" data-l1key="core::repo::provider">
                             <option value="default">{{Défaut}}</option>
                            <?php
foreach (repo::all() as $key => $value) {
	if (!isset($value['scope']['core']) || $value['scope']['core'] == false) {
		continue;
	}
	if (config::byKey($key . '::enable') == 0) {
		continue;
	}
	echo '<option value="' . $key . '">' . $value['name'] . '</option>';

}
?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group expertModeVisible">
                        <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Faire une sauvegarde avant la mise à jour}}</label>
                        <div class="col-sm-1">
                            <input type="checkbox" class="configKey bootstrapSwitch" data-l1key="update::backupBefore"/>
                        </div>
                    </div>

                    <?php

foreach (repo::all() as $key => $value) {
	echo '<legend>' . $value['name'] . '</legend>';
	echo '<div class="form-group">';
	echo '<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Activer}} ' . $value['name'] . '</label>';
	echo '<div class="col-sm-1">';
	echo '<input type="checkbox" class="configKey bootstrapSwitch enableRepository" data-repo="' . $key . '" data-l1key="' . $key . '::enable"/>';
	echo '</div>';
	echo '</div>';
	if ($value['scope']['hasConfiguration'] == false) {
		continue;
	}
	echo '<div class="repositoryConfiguration' . $key . '">';
	foreach ($value['configuration']['configuration'] as $pKey => $parameter) {
		echo '<div class="form-group">';
		echo '<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">';
		echo $parameter['name'];
		echo '</label>';
		echo '<div class="col-sm-4">';
		$default = (isset($parameter['default'])) ? $parameter['default'] : '';
		switch ($parameter['type']) {
			case 'checkbox':
				echo '<input type="checkbox" class="configKey" data-l1key="' . $key . '::' . $pKey . '" value="' . $default . '" />';
				break;
			case 'input':
				echo '<input class="configKey form-control" data-l1key="' . $key . '::' . $pKey . '" value="' . $default . '" />';
				break;
			case 'number':
				echo '<input type="number" class="configKey form-control" data-l1key="' . $key . '::' . $pKey . '" value="' . $default . '" />';
				break;
			case 'password':
				echo '<input type="password" class="configKey form-control" data-l1key="' . $key . '::' . $pKey . '" value="' . $default . '" />';
				break;
		}
		echo '</div>';
		echo '</div>';
	}
	echo '</div>';
}
?>
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

<?php include_file("desktop", "administration", "js");?>
