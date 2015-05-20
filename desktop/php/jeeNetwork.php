<?php
if (!hasRight('jeeNetworkview', true)) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>

<div class="row row-overflow">
    <div class="col-md-2 col-sm-3">
        <div class="bs-sidebar">
            <ul id="ul_jeeNetwork" class="nav nav-list bs-sidenav">
                <a id="bt_addJeeNetwork" class="btn btn-default" style="width : 100%;margin-top : 5px;margin-bottom: 5px;"><i class="fa fa-plus-circle"></i> {{Ajouter un Jeedom}}</a>
                <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
                <?php
foreach (jeeNetwork::all() as $jeeNetwork) {
	echo '<li class="cursor li_jeeNetwork" data-jeeNetwork_id="' . $jeeNetwork->getId() . '"><a>' . $jeeNetwork->getName() . '</a></li>';
}
?>
           </ul>
       </div>
   </div>
   <div class="col-md-10 col-sm-9 jeeNetwork" style="display: none;" id="div_conf">

      <ul class="nav nav-tabs" role="tablist">
          <li class="active"><a href="#main" role="tab" data-toggle="tab">{{Général}}</a></li>
          <li><a href="#log"  role="tab" data-toggle="tab">{{Log et messages}}</a></li>
          <li><a href="#update" role="tab" data-toggle="tab">{{Mise à jour}}</a></li>
          <li><a href="#backup" role="tab" data-toggle="tab">{{Backup}}</a></li>
          <li><a href="#administration" role="tab" data-toggle="tab">{{Administration}}</a></li>
      </ul>

      <div class="tab-content">
         <div role="tabpanel" class="tab-pane active" id="main">
             <br/>
             <div class="row">
                <div class="col-sm-6">
                    <form class="form-horizontal">
                        <fieldset>
                            <legend>{{Configuration}}</legend>
                            <div class="form-group">
                                <label class="col-sm-4 col-xs-6 control-label">{{Nom du Jeedom esclave}}</label>
                                <div class="col-sm-6 col-xs-6">
                                    <input class="form-control jeeNetworkAttr" type="text" data-l1key="id" style="display : none;"/>
                                    <input class="form-control jeeNetworkAttr" type="text" data-l1key="name" placeholder="Nom du Jeedom exclave"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 col-xs-6 control-label">{{IP}}</label>
                                <div class="col-sm-4 col-xs-6">
                                    <input class="form-control jeeNetworkAttr" type="text" data-l1key="ip" placeholder="IP"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 col-xs-6 control-label">{{Complément}}</label>
                                <div class="col-sm-4 col-xs-6">
                                    <input class="form-control jeeNetworkAttr" type="text" data-l1key="configuration" data-l2key="addrComplement" placeholder="ex : /jeedom"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 col-xs-6 control-label">{{Clef API}}</label>
                                <div class="col-sm-6 col-xs-6">
                                    <input class="form-control jeeNetworkAttr" type="text" data-l1key="apikey" placeholder="Clef API"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 col-xs-6 control-label">{{Accéder (attention ne marche que si vous êtes sur le réseau local)}}</label>
                                <div class="col-sm-6 col-xs-6">
                                    <a class="btn btn-default" id="bt_connectToSlave" target="_blank"><i class="fa fa-caret-square-o-right"></i> {{Se connecter}}</a>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 col-xs-6 control-label">{{Arrêter/Redémarrer}}</label>
                                <div class="col-sm-6 col-xs-6">
                                    <a class="btn btn-danger" id="bt_haltSlave" style="margin-bottom: 5px;"><i class="fa fa-stop"></i> {{Arrêter}}</a>
                                    <a class="btn btn-warning" id="bt_rebootSlave" style="margin-bottom: 5px;"><i class="fa fa-repeat"></i> {{Redémarrer}}</a>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="col-sm-6">
                    <form class="form-horizontal">
                        <fieldset>
                            <legend>{{Informations}}</legend>
                            <div class="form-group">
                                <label class="col-sm-4 col-xs-6 control-label">{{Statut}}</label>
                                <div class="col-sm-3 col-xs-6">
                                    <span class="label label-default jeeNetworkAttr" type="text" data-l1key="status" ></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 col-xs-6 control-label">{{Version de Jeedom}}</label>
                                <div class="col-sm-3 col-xs-6">
                                    <span class="label label-default jeeNetworkAttr" type="text" data-l1key="configuration" data-l2key="version" ></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 col-xs-6 control-label">{{Nombre de mise(s) à jour}}</label>
                                <div class="col-sm-3 col-xs-6">
                                    <span class="label label-default jeeNetworkAttr" type="text" data-l1key="configuration" data-l2key="nbUpdate" ></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 col-xs-6 control-label">{{Nombre de message(s)}}</label>
                                <div class="col-sm-3 col-xs-6">
                                    <span class="label label-default jeeNetworkAttr" type="text" data-l1key="configuration" data-l2key="nbMessage" ></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 col-xs-6 control-label">{{Dernière communication}}</label>
                                <div class="col-sm-3 col-xs-6">
                                    <span class="label label-default jeeNetworkAttr" type="text" data-l1key="configuration" data-l2key="lastCommunication" ></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 col-xs-6 control-label">{{Plugin}}</label>
                                <div class="col-sm-6 col-xs-6" id="div_pluginList"></div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
            <form class="form-horizontal">
                <fieldset>
                    <div class="form-actions">
                        <a class="btn btn-danger" id="bt_removeJeeNetwork"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
                        <a class="btn btn-success" id="bt_saveJeeNetwork"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
                    </div>
                </fieldset>
            </form>
        </div>


        <div role="tabpanel" class="tab-pane" id="log">
            <br/>
            <form class="form-horizontal">
                <fieldset>
                    <select id="sel_logSlave" class="form-control" style="display: inline-block;width: 200px;margin-bottom: 5px;"></select>
                    <a class="btn btn-primary" id="bt_refreshLog" style="margin-bottom: 5px;"><i class="fa fa-refresh"></i> {{Rafraîchir}}</a>
                    <a class="btn btn-warning" id="bt_emptyLog" style="margin-bottom: 5px;"><i class="fa fa-times"></i> {{Vider}}</a>
                    <a class="btn btn-danger" id="bt_removeLog" style="margin-bottom: 5px;"><i class="fa fa-trash-o"></i> {{Supprimer}}</a>
                    <a class="btn btn-default pull-right" id="bt_showMessage" style="margin-bottom: 5px;"><i class="fa fa-eye"></i> {{Voir les messages}} (<span class="jeeNetworkAttr" data-l1key="configuration" data-l2key="nbMessage"></span>)</a>
                    <a class="btn btn-danger pull-right" id="bt_emptyMessage" style="margin-bottom: 5px;"><i class="fa fa-trash-o"></i> {{Vider les messages}}</a>
                    <br/>
                    <pre id="pre_logInfo" style="overflow: scroll;"></pre>
                </fieldset>
            </form>
        </div>


        <div role="tabpanel" class="tab-pane" id="update">
            <br/>
            <a class="btn btn-warning pull-right" id="bt_checkUpdateSlave" style="margin-bottom: 5px;"><i class="fa fa-refresh"></i>  {{Vérifier}}</a>
            <a class="btn btn-default pull-right" id="bt_updateSlave" style="margin-bottom: 5px;"><i class="fa fa-check"></i> {{Mettre à jour}} (<span class="jeeNetworkAttr" type="text" data-l1key="configuration" data-l2key="nbUpdate" ></span>)</a>
            <br/> <br/>
            <pre id="pre_updateInfo" style="overflow: scroll;"></pre>
        </div>


        <div role="tabpanel" class="tab-pane" id="backup">
            <br/>
            <a class="btn btn-default pull-right" id="bt_restoreSlave"> <i class="fa fa-file"></i> {{Restaurer}}</a>
            <select class="form-control pull-right" id="sel_backupList" style="width : 400px;"></select>
            <a class="btn btn-default pull-right" id="bt_backupSlave"><i class="fa fa-floppy-o"></i> {{Sauvegarder}}</a>
            <br/> <br/>
            <pre id="pre_backupInfo" style="overflow: scroll;"></pre>
        </div>


        <div role="tabpanel" class="tab-pane" id="administration">
            <br/>
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
                                    <div class="form-group">
                                        <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Langue}}</label>
                                        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                                            <select class="configKey form-control" data-l1key="language">
                                                <option value="fr_FR">{{Français}}</option>
                                                <option value="en_US">{{English}}</option>
                                                <option value="de_DE">{{Deutsch}}</option>
                                                <option value="es_ES">{{Español}}</option>
                                                <option value="ru_RU">{{Pусский}}</option>
                                                <option value="id_ID">{{Indonésien}}</option>
                                                <option value="it_IT">{{Italiano}}</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 expertModeVisible">
                                          <label>
                                              <input type="checkbox" class="configKey tooltips" data-l1key="generateTranslation" title="{{Option pour les développeurs permettant à Jeedom de générer les phrases à traduire}}" /> {{Générer les traductions}}
                                          </label>
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
                                <div class="row">
                                    <div class="col-xs-6">
                                      <legend>Accès interne</legend>

                                      <div class="form-group expertModeVisible">
                                      <label class="col-xs-4 control-label">{{IP Maître}}</label>
                                         <div class="col-xs-8">
                                            <span class="label label-info configKey" data-l1key="jeeNetwork::master::ip"></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-xs-4 control-label">{{Protocole}}</label>
                                        <div class="col-xs-8">
                                            <select class="configKey form-control" data-l1key="internalProtocol">
                                                <option value="">Aucun</option>
                                                <option value="http://">HTTP</option>
                                                <option value="https://">HTTPS</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-4 control-label">{{Adresse URL ou IP}}</label>
                                        <div class="col-xs-8">
                                            <input type="text"  class="configKey form-control" data-l1key="internalAddr" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-4 control-label">{{Complément (exemple : /jeedom)}}</label>
                                        <div class="col-xs-8">
                                            <input type="text"  class="configKey form-control" data-l1key="internalComplement" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-4 control-label">{{Port}}</label>
                                        <div class="col-xs-8">
                                            <input type="number" class="configKey form-control" data-l1key="internalPort" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                 <legend>Accès externe</legend>
                                 <div class="form-group">
                                    <label class="col-xs-4 control-label">{{Protocole}}</label>
                                    <div class="col-xs-8">
                                        <select class="configKey form-control" data-l1key="externalProtocol">
                                            <option value="">Aucun</option>
                                            <option value="http://">HTTP</option>
                                            <option value="https://">HTTPS</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-4 control-label">{{Adresse URL ou IP}}</label>
                                    <div class="col-xs-8">
                                        <?php if (config::byKey('market::allowDNS') == 0) {
	echo '<input type="text"  class="configKey form-control" data-l1key="externalAddr" />';
} else {
	echo '<input type="text"  class="configKey form-control" data-l1key="externalAddr" disabled />';
}
?>
                                   </div>
                               </div>
                               <div class="form-group">
                                <label class="col-xs-4 control-label">{{Complément (exemple : /jeedom)}}</label>
                                <div class="col-xs-8">
                                    <input type="text"  class="configKey form-control" data-l1key="externalComplement" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-xs-4 control-label">{{Port}}</label>
                                <div class="col-xs-8">
                                    <input type="number" class="configKey form-control" data-l1key="externalPort" />
                                </div>
                            </div>
                        </div>
                    </div>

<div class="row">
                                    <div class="col-xs-6">
                    <legend>DNS Jeedom</legend>
                    <div class="form-group">
                        <label class="col-xs-4 control-label">{{Utiliser les DNS Jeedom}}</label>
                        <div class="col-xs-8">
                            <input type="checkbox"  class="configKey" data-l1key="market::allowDNS" />
                        </div>
                    </div>
                    <div class="alert alert-info">{{Toute modification nécessite de redémarrer le service DNS Jeedom (ligne "Gestion" puis "Redémarrer")}}</div>
                    <div class="alert alert-danger">
                    <div class="form-group">
                        <label class="col-xs-4 control-label" title="{{A ne faire que si on vous le demande ou en connaissance de cause}}">{{Rediriger le SSH}}</label>
                        <div class="col-xs-8">
                            <input type="checkbox"  class="configKey" data-l1key="market::redirectSSH" />
                        </div>
                    </div>
                     </div>
                    <div class="form-group">
                        <label class="col-xs-4 control-label">{{Authentification (optionnelle)}}</label>
                        <div class="col-xs-4">
                            <input  class="configKey form-control" data-l1key="market::userDNS" placeholder="Nom d'utilisateur" />
                        </div>
                        <div class="col-xs-4">
                            <input type="password" class="configKey form-control" data-l1key="market::passwordDNS" placeholder="Mot de passe" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-4 control-label">{{Statut http}}</label>
                        <div class="col-xs-8" id="div_ngrokHttpStatus">

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-4 control-label">{{Statut SSH}}</label>
                        <div class="col-xs-8" id="div_ngrokSSHStatus">

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-4 control-label">{{Gestion}}</label>
                        <div class="col-xs-8">
                           <a class="btn btn-success" id="bt_restartNgrok"><i class='fa fa-play'></i> {{(Re)démarrer}}</a>
                           <a class="btn btn-danger" id="bt_haltNgrok"><i class='fa fa-stop'></i> {{Arrêter}}</a>
                       </div>
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

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#configuration_market">
                {{Market et mise à jour}}
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
                        <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Installer automatiquement les widgets manquants}}</label>
                        <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                            <input type="checkbox"  class="configKey" data-l1key="market::autoInstallMissingWidget" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Afficher les plugins mis en avant par le market}}</label>
                        <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                            <input type="checkbox"  class="configKey" data-l1key="market::showPromotion" />
                        </div>
                    </div>
                    <div class="form-group alert alert-danger">
                        <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Voir les modules en beta (à vos risques et périls)}}</label>
                        <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                            <input type="checkbox"  class="configKey" data-l1key="market::showBetaMarket" />
                        </div>
                    </div>
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


</div>


</div>
</div>

<?php include_file('desktop', 'jeeNetwork', 'js');?>