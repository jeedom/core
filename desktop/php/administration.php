<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}

$repos = update::listRepo();

$keys = array('api', 'apipro', 'dns::token', 'market::allowDNS', 'ldap::enable', 'apimarket');
foreach ($repos as $key => $value) {
	$keys[] = $key . '::enable';
}
global $JEEDOM_INTERNAL_CONFIG;
$configs = config::byKeys($keys);
sendVarToJS('ldapEnable', $configs['ldap::enable']);
user::isBan();
?>
<br/>
<div id="config">
	<ul class="nav nav-tabs nav-primary" role="tablist">
		<li role="presentation" class="active"><a href="#generaltab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-wrench"></i> {{Général}}</a></li>
		<li role="presentation"><a href="#infotab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-info" title="{{Informations}}"></i><span> {{Informations}}</span></a></li>
		<li role="presentation"><a href="#apitab" aria-controls="profile" role="tab" data-toggle="tab" style="padding:10px 5px !important"><i class="fas fa-key"></i> {{API}}</a></li>
		<li role="presentation"><a href="#ostab" aria-controls="profile" role="tab" data-toggle="tab" style="padding:10px 5px !important"><i class="fas fa-terminal"></i> {{OS/DB}}</a></li>
		<li role="presentation"><a href="#securitytab" aria-controls="profile" role="tab" data-toggle="tab" style="padding:10px 5px !important"><i class="fas fa-shield-alt"></i> {{Securité}}</a></li>
		<li role="presentation"><a href="#networktab" aria-controls="profile" role="tab" data-toggle="tab" style="padding:10px 5px !important"><i class="fas fa-rss"></i> {{Réseaux}}</a></li>
		<li role="presentation"><a href="#widgettab" aria-controls="profile" role="tab" data-toggle="tab" style="padding:10px 5px !important"><i class="fas fa-tint"></i> {{Tuiles}}</a></li>
		<li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab" style="padding:10px 5px !important"><i class="icon divers-table29"></i> {{Commandes}}</a></li>
		<li role="presentation"><a href="#cachetab" aria-controls="profile" role="tab" data-toggle="tab" style="padding:10px 5px !important"><i class="fas fa-hdd-o"></i> {{Cache}}</a></li>
		<li role="presentation"><a href="#interacttab" aria-controls="profile" role="tab" data-toggle="tab" style="padding:10px 5px !important"><i class="fas fa-microphone"></i> {{Interactions}}</a></li>
		<li role="presentation"><a href="#repporttab" aria-controls="profile" role="tab" data-toggle="tab" style="padding:10px 5px !important"><i class="fas fa-newspaper-o"></i> {{Rapports}}</a></li>
		<li role="presentation"><a href="#graphlinktab" aria-controls="profile" role="tab" data-toggle="tab" style="padding:10px 5px !important"><i class="fas fa-sitemap"></i> {{Liens}}</a></li>
		<li role="presentation"><a href="#summarytab" aria-controls="profile" role="tab" data-toggle="tab" style="padding:10px 5px !important"><i class="fas fa-table"></i> {{Résumés}}</a></li>
		<li role="presentation"><a href="#logtab" aria-controls="profile" role="tab" data-toggle="tab" style="padding:10px 5px !important"><i class="far fa-file"></i> {{Logs}}</a></li>
		<li role="presentation"><a href="#eqlogictab" aria-controls="profile" role="tab" data-toggle="tab" style="padding:10px 5px !important"><i class="icon divers-svg"></i> {{Equipements}}</a></li>
		<li role="presentation"><a href="#updatetab" aria-controls="profile" role="tab" data-toggle="tab" style="padding:10px 5px !important"><i class="fas fa-credit-card"></i> {{Mises à jour/Market}}</a></li>
	</ul>
	<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
		<br/><a class="btn btn-success pull-right" id="bt_saveGeneraleConfig"><i class="far fa-check-circle"></i> {{Sauvegarder}}</a><br/>
		<div role="tabpanel" class="tab-pane active" id="generaltab">
			<br/>
			<form class="form-horizontal">
				<fieldset>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-3 col-xs-6 control-label help" data-help="{{Nom de votre <?php echo config::byKey('product_name'); ?> (utilisé notamment par le market)}}">{{Nom de votre}} <?php echo config::byKey('product_name'); ?></label>
						<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
							<input type="text" class="configKey form-control" data-l1key="name" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-3 col-xs-6 control-label help" data-help="{{Indique votre type de matériel}}">{{Système}}</label>
						<div class="col-lg-5 col-md-5 col-sm-6 col-xs-6">
							<span class="label label-info" style="font-size : 1em;"><?php echo jeedom::getHardwareName() ?></span>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3">
							<a class="btn btn-default form-control" id="bt_resetHardwareType"><i class="fas fa-refresh"></i> {{Rafraîchir}}</a>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-3 col-xs-6 control-label help" data-help="{{Clef d'installation qui permet d'identifier votre}} <?php echo config::byKey('product_name'); ?> {{quand il communique avec le market}}">{{Clef d'installation}}</label>
						<div class="col-lg-5 col-md-5 col-sm-6 col-xs-6">
							<span class="label label-info" style="font-size : 1em;"><?php echo jeedom::getHardwareKey() ?></span>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3">
							<a class="btn btn-default form-control" id="bt_resetHwKey"><i class="fas fa-refresh"></i> {{Remise à zéro}}</a>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-3 col-xs-6 control-label help" data-help="{{Langue de votre}} <?php echo config::byKey('product_name'); ?>">{{Langue}}</label>
						<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
							<select class="configKey form-control" data-l1key="language">
								<option value="fr_FR">French</option>
								<option value="en_US">English</option>
								<option value="de_DE">German</option>
								<option value="es_ES">Spanish</option>
								<option value="ru_RU">Russian</option>
								<option value="id_ID">Indonesian</option>
								<option value="it_IT">Italian</option>
								<option value="ja_JP">Japanese</option>
								<option value="pt_PT">Portuguese</option>
								<option value="tr">Turkish</option>
							</select>
						</div>
						
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-3 col-xs-6 control-label help" data-help="{{Durée de vie de votre connexion à}} <?php echo config::byKey('product_name'); ?> {{si vous n'avez pas coché la case enregistrer cet ordinateur}}">{{Durée de vie des sessions (heure)}}</label>
						<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
							<input type="text"  class="configKey form-control" data-l1key="session_lifetime" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-3 col-xs-6 control-label help" >{{Dernière date connue}}</label>
						<div class="col-lg-5 col-md-5 col-sm-6 col-xs-6">
							<?php
							
							$cache = cache::byKey('hour');
							$lastKnowDate = $cache->getValue();
							?>
							<span class="label label-info" style="font-size : 1em;"><?php echo $lastKnowDate ?></span>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3">
							<a class="btn btn-default form-control" id="bt_resetHour"><i class="fas fa-refresh"></i> {{Remise à zéro}}</a>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-3 col-xs-6 control-label help" data-help="{{Fuseau horaire de votre}} <?php echo config::byKey('product_name'); ?>">{{Date et heure}}</label>
						<div class="col-lg-5 col-md-5 col-sm-6 col-xs-6">
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
								<option value="America/Guadeloupe">(GMT-04:00) Guadeloupe</option>
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
								<option value="Africa/Casablanca">(GMT) Greenwich Mean Time : Casablanca</option>
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
						<div class="col-lg-3 col-md-3 col-sm-3">
							<a class="btn btn-primary form-control" id="bt_forceSyncHour"><i class="fas fa-clock-o"></i> {{Forcer la synchronisation de l'heure}}</a>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-3 col-xs-6 control-label help" data-help="{{Permet d'ajouter un serveur de temps à}} <?php echo config::byKey('product_name'); ?> {{utilisé lorsque}} <?php echo config::byKey('product_name'); ?> {{force la synchronisation de l'heure}}">{{Serveur de temps optionnel}}</label>
						<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
							<input type="text"  class="configKey form-control" data-l1key="ntp::optionalServer" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-3 col-xs-6 control-label help" data-help="{{Indique à}} <?php echo config::byKey('product_name'); ?> {{de ne pas prendre en compte l'heure du système}}">{{Ignorer la vérification de l'heure}}</label>
						<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
							<input type="checkbox" class="configKey" data-l1key="ignoreHourCheck" />
						</div>
					</div>
				</fieldset>
			</form>
		</div>
		
				<div role="tabpanel" class="tab-pane" id="infotab">
			<br/>
			<form class="form-horizontal">
				<fieldset>
					<legend>{{Coordonnées}}</legend>
					<div class="alert alert-info">{{Pour obtenir vos coordonnées GPS, vous pouvez utiliser ce <a href="https://www.torop.net/coordonnees-gps.php" target="_blank">site</a>}}</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-3 col-xs-6 control-label">{{Latitude}}
							<sup><i class="fas fa-question-circle" tooltip="{{Coordonnées géographiques de votre maison, permet de ne pas avoir à la remplir dans les plugins}}"></i></sup>
						</label>
						<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
							<input type="text" class="configKey form-control" data-l1key="info::latitude" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-3 col-xs-6 control-label">{{Longitude}}
							<sup><i class="fas fa-question-circle" tooltip="{{Coordonnées géographiques de votre maison, permet de ne pas avoir à la remplir dans les plugins}}"></i></sup>
						</label>
						<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
							<input type="text" class="configKey form-control" data-l1key="info::longitude" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-3 col-xs-6 control-label">{{Altitude}}
							<sup><i class="fas fa-question-circle" tooltip="{{Altitude de votre maison, permet de ne pas avoir à la remplir dans les plugins}}"></i></sup>
						</label>
						<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
							<input type="text" class="configKey form-control" data-l1key="info::altitude" />
						</div>
					</div>
					<legend>{{Adresse}}</legend>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-3 col-xs-6 control-label">{{Code pays (FR,US...)}}
							<sup><i class="fas fa-question-circle" tooltip="{{Adresse de votre maison, permet de ne pas avoir à la remplir dans les plugins}}"></i></sup>
						</label>
						<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
							<input type="text" class="configKey form-control" data-l1key="info::stateCode" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-3 col-xs-6 control-label">{{Code postal}}
							<sup><i class="fas fa-question-circle" tooltip="{{Adresse de votre maison, permet de ne pas avoir à la remplir dans les plugins}}"></i></sup>
						</label>
						<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
							<input type="text" class="configKey form-control" data-l1key="info::postalCode" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-3 col-xs-6 control-label">{{Ville}}
							<sup><i class="fas fa-question-circle" tooltip="{{Adresse de votre maison, permet de ne pas avoir à la remplir dans les plugins}}"></i></sup>
						</label>
						<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
							<input type="text" class="configKey form-control" data-l1key="info::city" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-3 col-xs-6 control-label">{{Adresse}}
							<sup><i class="fas fa-question-circle" tooltip="{{Adresse de votre maison, permet de ne pas avoir à la remplir dans les plugins}}"></i></sup>
						</label>
						<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
							<input type="text" class="configKey form-control" data-l1key="info::address" />
						</div>
					</div>
					<legend>{{Divers}}</legend>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-3 col-xs-6 control-label">{{Surface habitable}} <sub>m²</sub>
							<sup><i class="fas fa-question-circle" tooltip="{{Surface habitable votre maison, permet de ne pas avoir à la remplir dans les plugins}}"></i></sup>
						</label>
						<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
							<input type="text" class="configKey form-control" data-l1key="info::livingSpace" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-3 col-xs-6 control-label">{{Nombre d'occupant}}
							<sup><i class="fas fa-question-circle" tooltip="{{Nombre d'occupant, permet de ne pas avoir à la remplir dans les plugins}}"></i></sup>
						</label>
						<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
							<input type="text" class="configKey form-control" data-l1key="info::nbOccupant" />
						</div>
					</div>
				</fieldset>
			</form>
		</div>
		
		
		<div role="tabpanel" class="tab-pane" id="apitab">
			<br/>
			<form class="form-horizontal">
				<fieldset>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">{{Accès API HTTP}}</label>
						<div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
							<select class="form-control configKey" data-l1key="api::core::http::mode">
								<option value="enable">{{Activé}}</option>
								<option value="whiteip">{{IP blanche}}</option>
								<option value="localhost">{{Localhost}}</option>
								<option value="disable">{{Désactivé}}</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">{{Accès API JSONRPC}}</label>
						<div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
							<select class="form-control configKey" data-l1key="api::core::jsonrpc::mode">
								<option value="enable">{{Activé}}</option>
								<option value="whiteip">{{IP blanche}}</option>
								<option value="localhost">{{Localhost}}</option>
								<option value="disable">{{Désactivé}}</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">{{Accès API TTS}}</label>
						<div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
							<select class="form-control configKey" data-l1key="api::core::tts::mode">
								<option value="enable">{{Activé}}</option>
								<option value="whiteip">{{IP blanche}}</option>
								<option value="localhost">{{Localhost}}</option>
								<option value="disable">{{Désactivé}}</option>
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label help" data-help="{{Clef API globale de}} <?php echo config::byKey('product_name'); ?>">{{Clef API}}</label>
						<div class="col-lg-5 col-md-5 col-sm-7 col-xs-12">
							<div class="input-group">
								<span class="span_apikey"><?php echo $configs['api']; ?></span>
								<span class="input-group-btn">
									<a class="btn btn-default form-control bt_regenerate_api" data-plugin="core"><i class="fas fa-refresh"></i></a>
								</span>
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label help" data-help="{{Clef API Pro de}} <?php echo config::byKey('product_name'); ?>">{{Clef API Pro}}</label>
						<div class="col-lg-5 col-md-5 col-sm-7 col-xs-12">
							<div class="input-group">
								<span class="span_apikey"><?php echo $configs['apipro']; ?></span>
								<span class="input-group-btn">
									<a class="btn btn-default form-control bt_regenerate_api" data-plugin="pro"><i class="fas fa-refresh"></i></a>
								</span>
							</div>
						</div>
						<label class="col-lg-2 col-md-2 col-sm-4 col-xs-12 control-label">{{Accès API}}</label>
						<div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
							<select class="form-control configKey" data-l1key="api::core::pro::mode">
								<option value="enable">{{Activé}}</option>
								<option value="disable">{{Désactivé}}</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label help" data-help="{{Clef Market de}} <?php echo config::byKey('product_name'); ?>">{{Clef Market}}</label>
						<div class="col-lg-5 col-md-5 col-sm-7 col-xs-12">
							<div class="input-group">
								<span class="span_apikey"><?php echo $configs['apimarket']; ?></span>
								<span class="input-group-btn">
									<a class="btn btn-default form-control bt_regenerate_api" data-plugin="apimarket"><i class="fas fa-refresh"></i></a>
								</span>
							</div>
						</div>
						<label class="col-lg-2 col-md-2 col-sm-4 col-xs-12 control-label">{{Accès API}}</label>
						<div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
							<select class="form-control configKey" data-l1key="api::core::market::mode">
								<option value="enable">{{Activé}}</option>
								<option value="disable">{{Désactivé}}</option>
							</select>
						</div>
					</div>
					<hr/>
					<?php
					if (init('rescue', 0) == 0) {
						foreach (plugin::listPlugin(true) as $plugin) {
							if (config::byKey('api', $plugin->getId()) == '') {
								continue;
							}
							echo '<div class="form-group">';
							echo '<label class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label help" data-help="{{Clef API pour le plugin}} ' . $plugin->getName() . '">{{Clef API}} ' . $plugin->getName() . '</label>';
							echo '<div class="col-lg-5 col-md-5 col-sm-7 col-xs-12">';
							echo '<div class="input-group">';
							echo '<span class="span_apikey">' . config::byKey('api', $plugin->getId()) . '</span>';
							echo '<span class="input-group-btn">';
							echo '<a class="btn btn-default form-control bt_regenerate_api" data-plugin="' . $plugin->getId() . '"><i class="fas fa-refresh"></i></a>';
							echo '</span>';
							echo '</div>';
							echo '</div>';
							echo '<label class="col-lg-2 col-md-2 col-sm-4 col-xs-12 control-label">{{Accès API}}</label>';
							echo '<div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">';
							echo '<select class="form-control configKey" data-l1key="api::' . $plugin->getId() . '::mode">';
							echo '<option value="enable">{{Activé}}</option>';
							echo '<option value="whiteip">{{IP blanche}}</option>';
							echo '<option value="localhost">{{Localhost}}</option>';
							echo '<option value="disable">{{Désactivé}}</option>';
							echo '</select>';
							echo '</div>';
							echo '</div>';
						}
					}
					?>
				</fieldset>
			</form>
		</div>
		
		<div role="tabpanel" class="tab-pane" id="ostab">
			<br/>
			<div class="alert alert-danger">{{ATTENTION : ces opérations sont risquées, vous pouvez perdre l'accès à votre système et à}} <?php echo config::byKey('product_name'); ?>{{. L'équipe}} <?php echo config::byKey('product_name'); ?> {{se réserve le droit de refuser toute demande de support en cas de mauvaise manipulation.}}</div>
			<form class="form-horizontal">
				<fieldset>
					<legend><i class="fas fa-brain"></i> {{Général}}</legend>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Vérification général}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Permet de lancer le test de consistence de Jeedom}}"></i></sup>
						</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<a class="btn btn-warning" id="bt_consitency"><i class="fas fa-check"></i> {{Lancer}}</a>
						</div>
					</div>
					<legend><i class="fas fa-terminal"></i> {{Système}}</legend>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Administration}}</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<a class="btn btn-danger" href="index.php?v=d&p=system"><i class="fas fa-exclamation-triangle"></i> {{Lancer}}</a>
						</div>
					</div>
						<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Remettre à plat les droits des dossiers et fichiers}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Permet de réappliquer les bons droits sur les fichiers}}"></i></sup>
						</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<a class="btn btn-warning" id="bt_cleanFileSystemRight"><i class="fas fa-check"></i> {{Lancer}}</a>
						</div>
					</div>
					<legend><i class="fas fa-indent"></i> {{Editeur de fichiers}}</legend>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Editeur}}</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<a class="btn btn-danger" href="index.php?v=d&p=editor"><i class="fas fa-exclamation-triangle"></i> {{Lancer}}</a>
						</div>
					</div>
					<legend><i class="fas fa-database"></i> {{Base de données}}</legend>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Administration}}</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<a class="btn btn-danger" href="index.php?v=d&p=database"><i class="fas fa-exclamation-triangle"></i> {{Lancer}}</a>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Vérification}}</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<a class="btn btn-warning" id="bt_checkDatabase"><i class="fas fa-check"></i> {{Lancer}}</a>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Utilisateur}}</label>
						<div class="col-sm-1">
							<?php
							global $CONFIG;
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
		
		<div role="tabpanel" class="tab-pane" id="securitytab">
			<br/>
			<form class="form-horizontal">
				<fieldset>
					<legend>{{LDAP}}</legend>
					<?php if (function_exists('ldap_connect')) {?>
						<div class="form-group">
							<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Activer l'authentification LDAP}}</label>
							<div class="col-sm-1">
								<input type="checkbox" class="configKey" data-l1key="ldap:enable"/>
							</div>
						</div>
						<div id="div_config_ldap">
							<div class="form-group">
								<label class="col-md-3 col-sm-4 col-xs-12 control-label">{{Samba4}}</label>
								<div class="col-md-3 col-sm-4 col-xs-12">
									<input type="checkbox"  class="configKey form-control" data-l1key="ldap:samba4" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 col-sm-4 col-xs-12 control-label">{{tls}}</label>
								<div class="col-md-3 col-sm-4 col-xs-12">
									<input type="checkbox"  class="configKey form-control" data-l1key="ldap:tls" />
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
								<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Champs recherche utilisateur}}</label>
								<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
									<input type="text" class="configKey form-control" data-l1key="ldap::usersearch" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Filtre (optionnel)}}</label>
								<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
									<input type="text" class="configKey form-control" data-l1key="ldap:filter" />
								</div>
							</div>
							<div class="form-group has-error">
								<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Autoriser REMOTE_USER}}</label>
								<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
									<input type="checkbox"  class="configKey" data-l1key="sso:allowRemoteUser" />
								</div>
							</div>
							<div class="form-group">
								<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6"></div>
								<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
									<a class="btn btn-default" id="bt_testLdapConnection"><i class="fas fa-cube"></i> Tester</a>
								</div>
							</div>
						</div>
					<?php } else {
						echo '<div class="alert alert-info">{{Librairie LDAP non trouvée. Merci de l\'installer avant de pouvoir utiliser la connexion LDAP}}</div>';
					}?>
					<legend>{{Connexion}}</legend>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Nombre d'échecs tolérés}}</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<input type="text" class="configKey form-control" data-l1key="security::maxFailedLogin" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Temps maximum entre les échecs (s)}}</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<input type="text" class="configKey form-control" data-l1key="security::timeLoginFailed" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Durée du bannissement (s), -1 pour infini}}</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<input type="text" class="configKey form-control" data-l1key="security::bantime" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{IP "blanche"}}</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<input type="text" class="configKey form-control" data-l1key="security::whiteips" />
						</div>
					</div>
					
				</fieldset>
			</form>
			<form class="form-horizontal">
				<fieldset>
					<legend>{{IPs bannies}} <a class="btn btn-warning btn-xs pull-right" id="bt_removeBanIp"><i class="fas fa-trash"></i> {{Supprimer}}</a></legend>
					<table class="table table-condensed table-bordered">
						<thead>
							<tr>
								<th>{{IP}}</th><th>{{Date}}</th><th>{{Date de fin}}</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$cache = cache::byKey('security::banip');
							$values = json_decode($cache->getValue('[]'), true);
							if (!is_array($values)) {
								$values = array();
							}
							if (count($values) != 0) {
								foreach ($values as $value) {
									echo '<tr>';
									echo '<td>' . $value['ip'] . '</td>';
									echo '<td>' . date('Y-m-d H:i:s', $value['datetime']) . '</td>';
									if (config::byKey('security::bantime') < 0) {
										echo '<td>{{Jamais}}</td>';
									} else {
										echo '<td>' . date('Y-m-d H:i:s', $value['datetime'] + config::byKey('security::bantime')) . '</td>';
									}
									echo '</tr>';
								}
							}
							?>
						</tbody>
					</table>
					
				</fieldset>
			</form>
		</div>
		<div role="tabpanel" class="tab-pane" id="networktab">
			<br/>
			<div class="alert alert-warning">{{Attention : cette configuration n'est là que pour informer}} <?php echo config::byKey('product_name'); ?> {{de sa configuration réseau et n'a aucun impact sur les ports ou l'IP réellement utilisés pour joindre}} <?php echo config::byKey('product_name'); ?></div>
			<form class="form-horizontal">
				<fieldset>
					<legend>{{Accès interne}}</legend>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Protocole}}</label>
						<div class="col-lg-8 col-md-9 col-sm-8 col-xs-6">
							<div class="input-group">
								<select class="configKey form-control" data-l1key="internalProtocol">
									<option value="">{{Aucun}}</option>
									<option value="http://">HTTP</option>
									<option value="https://">HTTPS</option>
								</select>
								<span class="input-group-addon">://</span>
								<input type="text" class="configKey form-control" data-l1key="internalAddr" />
								<span class="input-group-addon">:</span>
								<input type="number" class="configKey form-control" data-l1key="internalPort" />
								<span class="input-group-addon">/</span>
								<input type="text" class="configKey form-control" data-l1key="internalComplement" />
							</div>
						</div>
					</div>
				</fieldset>
			</form>
			<form class="form-horizontal">
				<fieldset>
					<legend>{{Accès externe}}</legend>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Protocole}}</label>
						<div class="col-lg-8 col-md-9 col-sm-8 col-xs-6">
							<div class="input-group">
								<select class="configKey form-control" data-l1key="externalProtocol">
									<option value="">{{Aucun}}</option>
									<option value="http://">HTTP</option>
									<option value="https://">HTTPS</option>
								</select>
								<span class="input-group-addon">://</span>
								<input type="text" class="configKey form-control" data-l1key="externalAddr" />
								<span class="input-group-addon">:</span>
								<input type="number" class="configKey form-control" data-l1key="externalPort" />
								<span class="input-group-addon">/</span>
								<input type="text" class="configKey form-control" data-l1key="externalComplement" />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<legend>{{Gestion avancée}}</legend>
							<table class="table table-condensed table-bordered">
								<thead>
									<tr>
										<th>{{Interface}}</th>
										<th>{{IP}}</th>
										<th>{{Mac}}</th>
									</tr>
								</thead>
								<tbody>
									<?php
									foreach (network::getInterfaces() as $interface) {
										$mac = network::getInterfaceMac($interface);
										echo '<tr>';
										echo '<td>';
										echo $interface;
										echo '</td>';
										echo '<td>';
										echo network::getInterfaceIp($interface);
										echo '</td>';
										echo '<td>';
										echo network::getInterfaceMac($interface);
										echo '</td>';
										echo '</tr>';
									}
									?>
								</tbody>
							</table>
							<div class="form-group has-error">
								<label class="col-xs-6 control-label">{{Désactiver la gestion du réseau par}} <?php echo config::byKey('product_name'); ?></label>
								<div class="col-xs-4">
									<input type="checkbox" class="configKey" data-l1key="network::disableMangement" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-xs-6 control-label">{{Masque IP local (utile que pour les installations type docker, sous la forme 192.168.1.*)}}</label>
								<div class="col-xs-6">
									<input type="text"  class="configKey form-control" data-l1key="network::localip" />
								</div>
							</div>
							<div class="form-group col-xs-12">
								<label class="col-xs-6 control-label">{{MTU spécifique pour le DNS (expert)}}</label>
								<div class="col-xs-6">
									<input class="configKey form-control" data-l1key="market::dns::mtu" />
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<?php
							foreach ($repos as $key => $value) {
								if (!isset($value['scope']['proxy']) || $value['scope']['proxy'] === false) {
									continue;
								}
								if ($configs[$key . '::enable'] == 0) {
									continue;
								}
								echo '<legend>{{DNS (proxy)}} ' . $value['name'] . '</legend>';
								if ($configs['dns::token'] == '') {
									echo '<div class="alert alert-warning">{{Attention : cette fonctionnalité n\'est pas disponible dans le service pack community (voir votre service pack sur votre page profil sur le market)}}</div>';
									continue;
								}
								echo '<div class="form-group">';
								echo '<label class="col-xs-4 control-label">{{Utiliser les DNS}} ' . config::byKey('product_name') . '</label>';
								echo '<div class="col-xs-8">';
								echo '<input type="checkbox" class="configKey" data-l1key="' . $key . '::allowDNS" />';
								echo '</div>';
								echo '</div>';
								echo '<div class="form-group">';
								echo '<label class="col-xs-4 control-label">{{Statut DNS}}</label>';
								echo '<div class="col-xs-8">';
								if ($configs['market::allowDNS'] == 1 && network::dns_run()) {
									echo '<span class="label label-success" style="font-size : 1em;">{{Démarré : }} <a href="' . network::getNetworkAccess('external') . '" target="_blank" style="color:white;text-decoration: underline;">' . network::getNetworkAccess('external') . '</a></span>';
								} else {
									echo '<span class="label label-warning" title="{{Normal si vous n\'avez pas coché la case : Utiliser les DNS}} ' . config::byKey('product_name') . '">{{Arrêté}}</span>';
								}
								echo '</div>';
								echo '</div>';
								echo '<div class="form-group">';
								echo '<label class="col-xs-4 control-label">{{Gestion}}</label>';
								echo '<div class="col-xs-8">';
								echo '<a class="btn btn-success" id="bt_restartDns"><i class=\'fa fa-play\'></i> {{(Re)démarrer}}</a> ';
								echo '<a class="btn btn-danger" id="bt_haltDns"><i class=\'fa fa-stop\'></i> {{Arrêter}}</a>';
								echo '</div>';
								echo '</div>';
							}
							
							?>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
		
		<div role="tabpanel" class="tab-pane" id="widgettab">
			<br/>
			<form class="form-horizontal">
				<fieldset>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Opacité par défaut des widgets}}</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<input type="numeric" class="configKey form-control" data-l1key="widget::background-opacity" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Pas horizontal}}</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<input type="numeric" class="configKey form-control" data-l1key="widget::step::width" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Pas vertical}}</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<input type="numeric" class="configKey form-control" data-l1key="widget::step::height" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Marge}}</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<input type="numeric" class="configKey form-control" data-l1key="widget::margin" />
						</div>
					</div>
					<div  style="margin-left:10px;">
						<?php
						foreach (jeedom::getConfiguration('eqLogic:category') as $key => $category) {
							echo '<legend>' . $category['name'] . '</legend>';
							echo '<div class="form-group">';
							echo '<label class="col-sm-3 control-label">{{Dashboard couleur de fond}}</label>';
							echo '<div class="col-sm-2">';
							echo '<input type="color" class="configKey form-control cursor noSet input-sm" data-l1key="eqLogic:category:' . $key . ':color" value="' . $category['color'] . '" />';
							echo '</div>';
							echo '<div class="col-sm-1">';
							echo '<a class="btn btn-default btn-sm bt_resetColor" data-l1key="eqLogic:category:' . $key . ':color" title="{{Remettre par défaut}}"><i class="fas fa-times"></i></a>';
							echo '</div>';
							echo '<label class="col-sm-3 control-label">{{Dashboard couleur commande}}</label>';
							echo '<div class="col-sm-2">';
							echo '<input type="color" class="configKey form-control cursor noSet input-sm" data-l1key="eqLogic:category:' . $key . ':cmdColor" value="' . $category['cmdColor'] . '" />';
							echo '</div>';
							echo '<div class="col-sm-1">';
							echo '<a class="btn btn-default btn-sm bt_resetColor" data-l1key="eqLogic:category:' . $key . ':cmdColor" title="{{Remettre par défaut}}"><i class="fas fa-times"></i></a>';
							echo '</div>';
							echo '</div>';
							echo '<div class="form-group">';
							echo '<label class="col-sm-3 control-label">{{Mobile couleur de fond}}</label>';
							echo '<div class="col-sm-2">';
							echo '<input type="color" class="configKey form-control cursor noSet input-sm" data-l1key="eqLogic:category:' . $key . ':mcolor" value="' . $category['mcolor'] . '"/>';
							echo '</div>';
							echo '<div class="col-sm-1">';
							echo '<a class="btn btn-default btn-sm bt_resetColor" data-l1key="eqLogic:category:' . $key . ':mcolor" title="{{Remettre par défaut}}"><i class="fas fa-times"></i></a>';
							echo '</div>';
							echo '<label class="col-sm-3 control-label">{{Mobile couleur commande}}</label>';
							echo '<div class="col-sm-2">';
							echo '<input type="color" class="configKey form-control cursor noSet input-sm" data-l1key="eqLogic:category:' . $key . ':mcmdColor" value="' . $category['mcmdColor'] . '" />';
							echo '</div>';
							echo '<div class="col-sm-1">';
							echo '<a class="btn btn-default btn-sm bt_resetColor" data-l1key="eqLogic:category:' . $key . ':mcmdColor" title="{{Remettre par défaut}}"><i class="fas fa-times"></i></a>';
							echo '</div>';
							echo '</div>';
						}
						?>
					</div>
				</fieldset>
			</form>
		</div>
		
		<div role="tabpanel" class="tab-pane" id="commandtab">
			<br/>
			<legend>{{Historique}}</legend>
			<form class="form-horizontal">
				<fieldset>
					<div class="form-group">
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Afficher les statistiques sur les widgets}}</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<input type="checkbox"  class="configKey" data-l1key="displayStatsWidget" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Période de calcul pour min, max, moyenne (en heures)}}</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<input type="text"  class="configKey form-control" data-l1key="historyCalculPeriod" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Période de calcul pour la tendance (en heures)}}</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<input type="text"  class="configKey form-control" data-l1key="historyCalculTendance" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Délai avant archivage (en heures)}}</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<input type="text"  class="configKey form-control" data-l1key="historyArchiveTime" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Archiver par paquet de (en heures)}}</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<input type="text"  class="configKey form-control" data-l1key="historyArchivePackage" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Seuil de calcul de tendance bas}}</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<input type="text"  class="configKey form-control" data-l1key="historyCalculTendanceThresholddMin" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Seuil de calcul de tendance haut}}</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<input type="text"  class="configKey form-control" data-l1key="historyCalculTendanceThresholddMax" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Période d'affichage des graphiques par défaut}}</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<select  class="configKey form-control" data-l1key="history::defautShowPeriod" >
								<option value="-6 month">{{6 mois}}</option>
								<option value="-3 month">{{3 mois}}</option>
								<option value="-1 month">{{1 mois}}</option>
								<option value="-1 week">{{1 semaine}}</option>
								<option value="-1 day">{{1 jour}}</option>
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
							<input type="text"  class="configKey form-control" data-l1key="cmdPushUrl" title="{{Mettez ici l'URL à appeler lors d'une mise à jour de la valeur des commandes. Vous pouvez utiliser les tags suivants : #value# (valeur de la commande), #cmd_id# (id de la commande) et #cmd_name# (nom de la commande)}}"/>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
		
		<div role="tabpanel" class="tab-pane" id="cachetab">
			<br/>
			<form class="form-horizontal">
				<fieldset>
					<div class="alert alert-info">
						{{Attention : toute modification du moteur de cache nécessite un redémarrage de}} <?php echo config::byKey('product_name'); ?>
					</div>
					
					<?php
					$stats = cache::stats();
					?>
					<div class="form-group">
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Statistiques}}</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<?php
							echo '<span class="label label-primary" style="font-size:1em;"><span id="span_cacheObject">' . $stats['count'] . '</span> ' . __('objets', __FILE__) . '</span>';
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Moteur de cache}}</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<select type="text"  class="configKey form-control" data-l1key="cache::engine" >
								<option value="FilesystemCache">{{Système de fichiers (<?php echo cache::getFolder(); ?>)}}</option>
								<?php if (class_exists('memcached')) {?>
									<option value="MemcachedCache">{{Memcached}}</option>
								<?php }?>
								<?php if (class_exists('redis')) {?>
									<option value="RedisCache">{{Redis}}</option>
								<?php }?>
							</select>
						</div>
					</div>
					<div class="cacheEngine MemcachedCache">
						<div class="form-group">
							<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Adresse Memcache}}</label>
							<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
								<input type="text"  class="configKey form-control" data-l1key="cache::memcacheaddr" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Port Memcache}}</label>
							<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
								<input type="text"  class="configKey form-control" data-l1key="cache::memcacheport" />
							</div>
						</div>
					</div>
					<div class="cacheEngine RedisCache">
						<div class="form-group">
							<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Adresse Redis}}</label>
							<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
								<input type="text"  class="configKey form-control" data-l1key="cache::redisaddr" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Port redis}}</label>
							<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
								<input type="text"  class="configKey form-control" data-l1key="cache::redisport" />
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Nettoyer le cache}}</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<a class="btn btn-warning" id="bt_cleanCache"><i class="fas fa-magic"></i> {{Nettoyer}}</a>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Vider toutes les données en cache}}</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<a class="btn btn-danger" id="bt_flushCache"><i class="fas fa-trash"></i> {{Vider}}</a>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Temps de pause pour le long polling}}</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<input class="configKey form-control" data-l1key="event::waitPollingTime"/>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
		
		<div role="tabpanel" class="tab-pane" id="interacttab">
			<br/>
			<form class="form-horizontal">
				<fieldset>
					<legend>{{Général}}</legend>
					<div class="alert alert-info">
						{{Plus la sensibilité est basse (proche de 1), plus la correspondance doit être exacte.}}
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Sensibilité}}</label>
						<div class="col-lg-6 col-md-8 col-sm-8 col-xs-6">
							<div class="input-group">
								<span class="input-group-addon">{{1 mot}}</span>
								<input type="text" class="configKey form-control" data-l1key="interact::confidence1"/>
								<span class="input-group-addon">{{2 mots}}</span>
								<input type="text" class="configKey form-control" data-l1key="interact::confidence2"/>
								<span class="input-group-addon">{{3 mots}}</span>
								<input type="text" class="configKey form-control" data-l1key="interact::confidence3"/>
								<span class="input-group-addon">> {{3 mots}}</span>
								<input type="text" class="configKey form-control" data-l1key="interact::confidence"/>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Réduire le poids de}}</label>
						<div class="col-lg-6 col-md-8 col-sm-8 col-xs-6">
							<div class="input-group">
								<span class="input-group-addon">{{1 mot}}</span>
								<input type="text" class="configKey form-control" data-l1key="interact::weigh1"/>
								<span class="input-group-addon">{{2 mots}}</span>
								<input type="text" class="configKey form-control" data-l1key="interact::weigh2"/>
								<span class="input-group-addon">{{3 mots}}</span>
								<input type="text" class="configKey form-control" data-l1key="interact::weigh3"/>
								<span class="input-group-addon">{{4 mots}}</span>
								<input type="text" class="configKey form-control" data-l1key="interact::weigh4"/>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Ne pas répondre si l'interaction n'est pas comprise}}</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<input type="checkbox" class="configKey" data-l1key="interact::noResponseIfEmpty"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Regex générale d'exclusion pour les interactions}}</label>
						<div class="col-lg-10 col-md-9 col-sm-8 col-xs-6">
							<textarea type="text" class="configKey form-control" data-l1key="interact::regexpExcludGlobal"></textarea>
						</div>
					</div>
					<legend>{{Interaction automatique, contextuelle & avertissement}}</legend>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Activer les interactions automatiques}}</label>
						<div class="col-lg-10 col-md-9 col-sm-8 col-xs-6">
							<input type="checkbox" class="configKey" data-l1key="interact::autoreply::enable" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Activer les réponses contextuelles}}</label>
						<div class="col-lg-10 col-md-9 col-sm-8 col-xs-6">
							<input type="checkbox" class="configKey" data-l1key="interact::contextual::enable" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Réponse contextuelle prioritaire si la phrase commence par}}</label>
						<div class="col-lg-10 col-md-9 col-sm-8 col-xs-6">
							<input class="configKey form-control" data-l1key="interact::contextual::startpriority" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Découper une interaction en 2 si elle contient}}</label>
						<div class="col-lg-10 col-md-9 col-sm-8 col-xs-6">
							<input class="configKey form-control" data-l1key="interact::contextual::splitword" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Activer les interactions "préviens moi"}}</label>
						<div class="col-lg-10 col-md-9 col-sm-8 col-xs-6">
							<input type="checkbox" class="configKey" data-l1key="interact::warnme::enable" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Réponse de type "préviens moi" si la phrase commence par}}</label>
						<div class="col-lg-10 col-md-9 col-sm-8 col-xs-6">
							<input class="configKey form-control" data-l1key="interact::warnme::start" />
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Commande de retour par défaut}}</label>
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
							<div class="input-group">
								<input type="text"  class="configKey form-control" data-l1key="interact::warnme::defaultreturncmd" />
								<span class="input-group-btn">
									<a class="btn btn-default cursor bt_selectWarnMeCmd" title="Rechercher une commande"><i class="fas fa-list-alt"></i></a>
								</span>
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Synonymes pour les objets}}</label>
						<div class="col-lg-10 col-md-9 col-sm-8 col-xs-6">
							<input class="configKey form-control" data-l1key="interact::autoreply::jeeObject::synonym" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Synonymes pour les équipements}}</label>
						<div class="col-lg-10 col-md-9 col-sm-8 col-xs-6">
							<input class="configKey form-control" data-l1key="interact::autoreply::eqLogic::synonym" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Synonymes pour les commandes}}</label>
						<div class="col-lg-10 col-md-9 col-sm-8 col-xs-6">
							<input class="configKey form-control" data-l1key="interact::autoreply::cmd::synonym" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Synonymes pour les résumés}}</label>
						<div class="col-lg-10 col-md-9 col-sm-8 col-xs-6">
							<input class="configKey form-control" data-l1key="interact::autoreply::summary::synonym" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Synonyme commande slider maximum}}</label>
						<div class="col-lg-10 col-md-9 col-sm-8 col-xs-6">
							<input class="configKey form-control" data-l1key="interact::autoreply::cmd::slider::max" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Synonyme commande slider minimum}}</label>
						<div class="col-lg-10 col-md-9 col-sm-8 col-xs-6">
							<input class="configKey form-control" data-l1key="interact::autoreply::cmd::slider::min" />
						</div>
					</div>
					
					<legend>{{Couleurs}}</legend>
					<i class="fas fa-plus-circle pull-right cursor" id="bt_addColorConvert" style="font-size: 1.8em;"></i>
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
		
		<div role="tabpanel" class="tab-pane" id="repporttab">
			<br/>
			<form class="form-horizontal">
				<fieldset>
					<div class="form-group">
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Delai d'attente après génération de la page (en ms)}}</label>
						<div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
							<input class="configKey form-control" data-l1key="report::delay" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Nettoyer les rapports plus anciens de (jours)}}</label>
						<div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
							<input class="configKey form-control" data-l1key="report::maxdays" />
						</div>
					</div>
				</fieldset>
			</form>
		</div>
		
		
		<div role="tabpanel" class="tab-pane" id="graphlinktab">
			<br/>
			<form class="form-horizontal">
				<fieldset>
					<div class="form-group">
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Profondeur pour les scénarios}}</label>
						<div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
							<input class="configKey form-control" data-l1key="graphlink::scenario::drill" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Profondeur pour les objets}}</label>
						<div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
							<input class="configKey form-control" data-l1key="graphlink::jeeObject::drill" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Profondeur pour les équipements}}</label>
						<div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
							<input class="configKey form-control" data-l1key="graphlink::eqLogic::drill" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Profondeur pour les commandes}}</label>
						<div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
							<input class="configKey form-control" data-l1key="graphlink::cmd::drill" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Profondeur pour les variables}}</label>
						<div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
							<input class="configKey form-control" data-l1key="graphlink::dataStore::drill" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Paramètre de prerender}}</label>
						<div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
							<input class="configKey form-control" data-l1key="graphlink::prerender" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Paramètre de render}}</label>
						<div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
							<input class="configKey form-control" data-l1key="graphlink::render" />
						</div>
					</div>
				</fieldset>
			</form>
		</div>
		
		<div role="tabpanel" class="tab-pane" id="summarytab">
			<br/>
			<form class="form-horizontal">
				<fieldset>
					<i class="fas fa-plus-circle pull-right cursor" id="bt_addObjectSummary" style="font-size: 1.8em;"></i>
					<table class="table table-condensed table-bordered" id="table_objectSummary" >
						<thead>
							<tr>
								<th>{{Clef}}</th>
								<th>{{Nom}}</th>
								<th>{{Calcul}}</th>
								<th>{{Icone}}</th>
								<th>{{Unité}}</th>
								<th>{{Méthode de comptage}}</th>
								<th>{{Affiché si valeur égale 0}}</th>
								<th>{{Liée à un virtuel}}</th>
								<th></th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</fieldset>
			</form>
		</div>
		
		<div role="tabpanel" class="tab-pane" id="logtab">
			<br/>
			<form class="form-horizontal">
				<fieldset>
					<legend>{{Timeline}}</legend>
					<div class="form-group">
						<label class="col-lg-4 col-md-4 col-sm-4 col-xs-3 control-label">{{Nombre maximum d'évènements}}</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<div class="input-group">
								<input type="text" class="configKey form-control" data-l1key="timeline::maxevent"/>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-4 col-sm-4 col-xs-3 control-label">{{Supprimer tous les évènements}}</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<div class="input-group">
								<a type="text" class="btn btn-danger" id="bt_removeTimelineEvent" ><i class="fas fa-trash"></i> {{Supprimer}}</a>
							</div>
						</div>
					</div>
					<legend>{{Messages}}</legend>
					<div class="form-group">
						<label class="col-lg-4 col-md-4 col-sm-4 col-xs-3 control-label">{{Ajouter un message à chaque erreur dans les logs}}</label>
						<div class="col-sm-1">
							<div class="input-group">
								<input type="checkbox" class="configKey" data-l1key="addMessageForErrorLog" checked/>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-4 col-sm-4 col-xs-3 control-label">{{Action sur message}}</label>
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
							<div class="input-group">
								<a class="btn btn-success" id="bt_addActionOnMessage"><i class="fas fa-plus-circle"></i> {{Ajouter}}</a>
							</div>
						</div>
					</div>
					<div id="div_actionOnMessage"></div>
				</fieldset>
			</form>
			
			<ul class="nav nav-tabs" role="tablist">
				<li role="presentation" class="active"><a href="#log_alertes" role="tab" data-toggle="tab"><i class="fas fa-bell"></i> {{Alertes}}</a></li>
				<li role="presentation"><a href="#log_log" role="tab" data-toggle="tab"><i class="fas fa-file"></i> {{Log}}</a></li>
			</ul>
			
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane active" id="log_alertes">
					<form class="form-horizontal">
						<fieldset>
							<br/>
							<?php
							foreach ($JEEDOM_INTERNAL_CONFIG['alerts'] as $level => $value) {
								echo '<div class="form-group">';
								echo '<label class="col-lg-4 col-md-4 col-sm-4 col-xs-3 control-label">{{Ajouter un message à chaque}} ' . $value['name'] . '</label>';
								echo '<div class="col-sm-1">';
								echo '<div class="input-group">';
								echo '<input type="checkbox" class="configKey" data-l1key="alert::addMessageOn' . ucfirst($level) . '"/>';
								echo '</div>';
								echo '</div>';
								echo '</div>';
								echo '<div class="form-group">';
								echo '<label class="col-lg-4 col-md-4 col-sm-4 col-xs-3 control-label">{{Commande sur}} ' . $value['name'] . '</label>';
								echo '<div class="col-lg-4 col-md-6 col-sm-6 col-xs-8">';
								echo '<div class="input-group">';
								echo '<input type="text"  class="configKey form-control" data-l1key="alert::' . $level . 'Cmd" />';
								echo '<span class="input-group-btn">';
								echo '<a class="btn btn-default cursor bt_selectAlertCmd" title="Rechercher une commande" data-type="' . $level . '"><i class="fas fa-list-alt"></i></a>';
								echo '</span>';
								echo '</div>';
								echo '</div>';
								echo '</div>';
								echo '<hr/>';
							}
							?>
						</fieldset>
					</form>
				</div>
				
				<div role="tabpanel" class="tab-pane" id="log_log">
					<form class="form-horizontal">
						<fieldset>
							<br/>
							<div class="form-group">
								<label class="col-lg-4 col-md-4 col-sm-4 col-xs-3 control-label">{{Moteur de log}}</label>
								<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
									<div class="input-group form-control">
										<select class="configKey form-control" data-l1key="log::engine">
											<option value="StreamHandler">{{Defaut}}</option>
											<option value="SyslogHandler">{{Syslog}}</option>
											<option value="SyslogUdp">{{SyslogUdp}}</option>
										</select>
									</div>
								</div>
							</div>
							<div class="logEngine SyslogUdp">
								<div class="form-group">
									<label class="col-lg-4 col-md-4 col-sm-4 col-xs-3 control-label">{{Adresse syslog UDP}}</label>
									<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
										<div class="input-group form-control">
											<input type="text"  class="configKey form-control" data-l1key="log::syslogudphost" />
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-4 col-md-4 col-sm-4 col-xs-3 control-label">{{Port syslog UDP}}</label>
									<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
										<div class="input-group form-control">
											<input type="text"  class="configKey form-control" data-l1key="log::syslogudpport" />
										</div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-4 col-md-4 col-sm-4 col-xs-3 control-label">{{Format des logs}}</label>
								<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
									<div class="input-group form-control">
										<input type="text" class="configKey form-control" data-l1key="log::formatter" />
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-4 col-md-4 col-sm-4 col-xs-3 control-label">{{Nombre de lignes maximum dans un fichier de log}}</label>
								<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
									<div class="input-group form-control">
										<input type="text" class="configKey form-control" data-l1key="maxLineLog"/>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-4 col-md-4 col-sm-4 col-xs-3 control-label">{{Niveau de log par défaut}}</label>
								<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
									<div class="input-group form-control">
										<select class="configKey form-control" data-l1key="log::level">
											<option value="100">{{Debug}}</option>
											<option value="200">{{Info}}</option>
											<option value="300">{{Warning}}</option>
											<option value="400">{{Erreur}}</option>
										</select>
									</div>
								</div>
							</div>
							<?php
							
							$other_log = array('scenario', 'plugin', 'market', 'api', 'connection', 'interact', 'tts', 'report', 'event');
							foreach ($other_log as $name) {
								echo '<form class="form-horizontal">';
								echo '<div class="form-group">';
								echo '<label class="col-lg-4 col-md-4 col-sm-4 col-xs-3 control-label">' . ucfirst($name) . '</label>';
								echo '<div class="col-sm-8">';
								echo '<label class="radio-inline"><input type="radio" name="rd_logupdate' . $name . '" class="configKey" data-l1key="log::level::' . $name . '" data-l2key="1000" /> {{Aucun}}</label>';
								echo '<label class="radio-inline"><input type="radio" name="rd_logupdate' . $name . '" class="configKey" data-l1key="log::level::' . $name . '" data-l2key="default" /> {{Défaut}}</label>';
								echo '<label class="radio-inline"><input type="radio" name="rd_logupdate' . $name . '" class="configKey" data-l1key="log::level::' . $name . '" data-l2key="100" /> {{Debug}}</label>';
								echo '<label class="radio-inline"><input type="radio" name="rd_logupdate' . $name . '" class="configKey" data-l1key="log::level::' . $name . '" data-l2key="200" /> {{Info}}</label>';
								echo '<label class="radio-inline"><input type="radio" name="rd_logupdate' . $name . '" class="configKey" data-l1key="log::level::' . $name . '" data-l2key="300" /> {{Warning}}</label>';
								echo '<label class="radio-inline"><input type="radio" name="rd_logupdate' . $name . '" class="configKey" data-l1key="log::level::' . $name . '" data-l2key="400" /> {{Erreur}}</label>';
								echo '</div>';
								echo '</div>';
							}
							if (init('rescue', 0) == 0) {
								foreach (plugin::listPlugin(true) as $plugin) {
									echo '<form class="form-horizontal">';
									echo '<div class="form-group">';
									echo '<label class="col-lg-4 col-md-4 col-sm-4 col-xs-3 control-label">' . $plugin->getName() . '</label>';
									echo '<div class="col-sm-8">';
									echo '<label class="radio-inline"><input type="radio" name="rd_logupdate' . $plugin->getId() . '" class="configKey" data-l1key="log::level::' . $plugin->getId() . '" data-l2key="1000" /> {{Aucun}}</label>';
									echo '<label class="radio-inline"><input type="radio" name="rd_logupdate' . $plugin->getId() . '" class="configKey" data-l1key="log::level::' . $plugin->getId() . '" data-l2key="default" /> {{Défaut}}</label>';
									echo '<label class="radio-inline"><input type="radio" name="rd_logupdate' . $plugin->getId() . '" class="configKey" data-l1key="log::level::' . $plugin->getId() . '" data-l2key="100" /> {{Debug}}</label>';
									echo '<label class="radio-inline"><input type="radio" name="rd_logupdate' . $plugin->getId() . '" class="configKey" data-l1key="log::level::' . $plugin->getId() . '" data-l2key="200" /> {{Info}}</label>';
									echo '<label class="radio-inline"><input type="radio" name="rd_logupdate' . $plugin->getId() . '" class="configKey" data-l1key="log::level::' . $plugin->getId() . '" data-l2key="300" /> {{Warning}}</label>';
									echo '<label class="radio-inline"><input type="radio" name="rd_logupdate' . $plugin->getId() . '" class="configKey" data-l1key="log::level::' . $plugin->getId() . '" data-l2key="400" /> {{Erreur}}</label>';
									echo '</div>';
									echo '</div>';
								}
							}
							?>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane" id="eqlogictab">
			<br/>
			<form class="form-horizontal">
				<fieldset>
					<div class="col-lg-12 form-group">
						<label class="col-lg-4 col-md-3 col-sm-4 col-xs-4 control-label">{{Nombre d'échecs avant désactivation de l'équipement}}</label>
						<div class="col-lg-2 col-md-4 col-sm-5 col-xs-6">
							<input type="text"  class="configKey form-control" data-l1key="numberOfTryBeforeEqLogicDisable" />
						</div>
					</div>
					<div class="col-lg-12 form-group">
						<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label">{{Seuil des piles}}</label>
						<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
							<label class="col-lg-2 col-md-2 col-sm-2 col-xs-2 eqLogicAttr label label-danger" style="font-size : 1.4em">{{Danger}}</label>
							<div class="col-lg-1 col-md-2 col-sm-2 col-xs-2">
								<input class="configKey form-control" data-l1key="battery::danger" />
							</div>
							<label class="col-lg-2 col-md-2 col-sm-3 col-xs-3 label label-warning" style="font-size : 1.4em">{{Warning}}</label>
							<div class="col-lg-1 col-md-2 col-sm-2 col-xs-2">
								<input class="configKey form-control" data-l1key="battery::warning" />
							</div>
							<label class="col-lg-2 col-md-2 col-sm-2 col-xs-2 label label-success" style="font-size : 1.4em">{{Ok}}</label>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
		
		<div role="tabpanel" class="tab-pane" id="updatetab">
			<br/>
			<div class="row">
				<div class="col-sm-6">
					<form class="form-horizontal">
						<fieldset>
							<legend>{{Mise à jour de}} <?php echo config::byKey('product_name'); ?></legend>
							<div class="form-group">
								<label class="col-lg-4 col-md-6 col-sm-6 col-xs-6 control-label">{{Source de mise à jour}}</label>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
									<select class="configKey form-control" data-l1key="core::repo::provider">
										<option value="default">{{Défaut}}</option>
										<?php
										foreach ($repos as $key => $value) {
											if (!isset($value['scope']['core']) || $value['scope']['core'] === false) {
												continue;
											}
											if ($configs[$key . '::enable'] == 0) {
												continue;
											}
											echo '<option value="' . $key . '">' . $value['name'] . '</option>';
										}
										?>
									</select>
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-lg-4 col-md-6 col-sm-6 col-xs-6 control-label">{{Version du core}}</label>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
									<select class="configKey form-control" id="versionCore" data-l1key="core::branch">
										<option value="alpha">{{Alpha (Plus d'accès au support)}}</option>
										<option value="beta">{{Beta (Plus d'accès au support)}}</option>
										<option value="release">{{Release (Plus d'accès au support)}}</option>
										<option value="master">{{Stable}}</option>
										<option value="V4-stable">{{Release Candidate V4}}</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-4 col-md-6 col-sm-6 col-xs-6 control-label">{{Vérifier automatiquement si il y a des mises à jour}}</label>
								<div class="col-sm-1">
									<input type="checkbox" class="configKey" data-l1key="update::autocheck"/>
								</div>
							</div>
						</fieldset>
					</form>
				</div>
				<div class="col-sm-6">
					<form class="form-horizontal">
						<fieldset>
							
							<ul class="nav nav-tabs" role="tablist">
								<?php
								foreach ($repos as $key => $value) {
									$active = ($key == 'market') ? 'active' : '';
									echo '<li role="presentation" class="' . $active . '"><a href="#tab' . $key . '" aria-controls="tab' . $key . '" role="tab" data-toggle="tab">' . $value['name'] . '</a></li>';
								}
								?>
							</ul>
							<div class="tab-content">
								<?php
								foreach ($repos as $key => $value) {
									$active = ($key == 'market') ? 'active' : '';
									echo '<div role="tabpanel" class="tab-pane ' . $active . '" id="tab' . $key . '">';
									echo '<br/>';
									echo '<div class="form-group">';
									echo '<label class="col-lg-4 col-md-6 col-sm-6 col-xs-6 control-label">{{Activer}} ' . $value['name'] . '</label>';
									echo '<div class="col-sm-1">';
									echo '<input type="checkbox" class="configKey enableRepository" data-repo="' . $key . '" data-l1key="' . $key . '::enable"/>';
									echo '</div>';
									echo '</div>';
									if ($value['scope']['hasConfiguration'] === false) {
										echo '</div>';
										continue;
									}
									echo '<div class="repositoryConfiguration' . $key . '" style="display:none;">';
									foreach ($value['configuration']['configuration'] as $pKey => $parameter) {
										echo '<div class="form-group">';
										echo '<label class="col-lg-4 col-md-6 col-sm-6 col-xs-6 control-label">';
										echo $parameter['name'];
										echo '</label>';
										echo '<div class="col-sm-6">';
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
											case 'select':
											echo '<select class="configKey form-control" data-l1key="' . $key . '::' . $pKey . '">';
											foreach ($parameter['values'] as $optkey => $optval) {
												echo '<option value="' . $optkey . '">' . $optval . '</option>';
											}
											echo '</select>';
											break;
										}
										echo '</div>';
										echo '</div>';
									}
									if (isset($value['scope']['test']) && $value['scope']['test']) {
										echo '<div class="form-group">';
										echo '<label class="col-lg-4 col-md-6 col-sm-6 col-xs-6 control-label">{{Tester/Synchroniser}}</label>';
										echo '<div class="col-sm-4">';
										echo '<a class="btn btn-default testRepoConnection" data-repo="' . $key . '"><i class="fas fa-check"></i> {{Tester}}</a>';
										echo '</div>';
										echo '</div>';
									}
									echo '</div>';
									echo '</div>';
								}
								?>
							</fieldset>
						</form>
					</div>
				</div>
			</div>
			<?php include_file("desktop", "administration", "js");?>
			
