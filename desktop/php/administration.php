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
	<div class="input-group" style="margin-bottom:5px;">
		<input class="form-control roundedLeft" placeholder="{{Rechercher}}" id="in_searchConfig"/>
		<div class="input-group-btn">
			<a id="bt_resetConfigSearch" class="btn" style="width:30px"><i class="fas fa-times"></i> </a>
		</div>
		<div class="input-group-btn">
			<a class="btn btn-success pull-right roundedRight" id="bt_saveGeneraleConfig"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a>
		</div>
	</div>
	<div>
		<form class="form-horizontal">
			<div id="searchResult"></div>
		</form>
	</div>

	<ul class="nav nav-tabs nav-primary" role="tablist" style="max-width:calc(100% - 150px);">
		<li role="presentation" class="active"><a href="#generaltab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-wrench"></i> {{Général}}</a></li>
		<li role="presentation"><a href="#interfacetab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-laptop"></i> {{Interface}}</a></li>
		<li role="presentation"><a href="#networktab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-rss"></i> {{Réseaux}}</a></li>
		<li role="presentation"><a href="#logtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="far fa-file"></i> {{Logs}}</a></li>
		<li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="icon divers-table29"></i> {{Commandes}}</a></li>
		<li role="presentation"><a href="#summarytab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-table"></i> {{Résumés}}</a></li>
		<li role="presentation"><a href="#eqlogictab" aria-controls="profile" role="tab" data-toggle="tab"><i class="icon divers-svg"></i> {{Equipements}}</a></li>
		<li role="presentation"><a href="#repporttab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-newspaper"></i> {{Rapports}}</a></li>
		<li role="presentation"><a href="#graphlinktab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-sitemap"></i> {{Liens}}</a></li>
		<li role="presentation"><a href="#interacttab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-microphone"></i> {{Interactions}}</a></li>
		<li role="presentation"><a href="#securitytab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-shield-alt"></i> {{Securité}}</a></li>
		<li role="presentation"><a href="#updatetab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-credit-card"></i> {{Mises à jour}}</a></li>
		<li role="presentation"><a href="#cachetab" aria-controls="profile" role="tab" data-toggle="tab"><i class="far fa-hdd"></i> {{Cache}}</a></li>
		<li role="presentation"><a href="#apitab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-key"></i> {{API}}</a></li>
		<li role="presentation"><a href="#ostab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-terminal"></i> {{OS/DB}}</a></li>
	</ul>
	<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
		<div role="tabpanel" class="tab-pane active" id="generaltab">
			<br/>
			<form class="form-horizontal">
				<fieldset>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-3 col-xs-6 control-label help">{{Version}}</label>
						<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
							<span class="label label-info">v<?php echo jeedom::version(); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-3 col-xs-6 control-label">{{Nom de votre}} <?php echo config::byKey('product_name'); ?>
							<sup><i class="fas fa-question-circle tooltips" title="{{Nom de votre <?php echo config::byKey('product_name'); ?> (utilisé notamment par le market)}}"></i></sup>
						</label>
						<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
							<input type="text" class="configKey form-control" data-l1key="name" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-3 col-xs-6 control-label">{{Langue}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Langue de votre}} <?php echo config::byKey('product_name'); ?>"></i></sup>
						</label>
						<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
							<div class="dropdown dynDropdown">
								<button class="btn btn-default dropdown-toggle configKey" type="button" data-toggle="dropdown" data-l1key="language">
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu dropdown-menu-right">
									<li><a href="#" data-value="fr_FR">French</a></li>
									<li><a href="#" data-value="en_US">English</a></li>
									<li><a href="#" data-value="de_DE">German</a></li>
									<li><a href="#" data-value="es_ES">Spanish</a></li>
									<li><a href="#" data-value="ru_RU">Russian</a></li>
									<li><a href="#" data-value="id_ID">Indonesian</a></li>
									<li><a href="#" data-value="it_IT">Italian</a></li>
									<li><a href="#" data-value="ja_JP">Japanese</a></li>
									<li><a href="#" data-value="pt_PT">Portuguese</a></li>
									<li><a href="#" data-value="tr">Turkish</a></li>
								</ul>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-3 col-xs-6 control-label">{{Générer les traductions}}</label>
						<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
							<input type="checkbox" class="configKey" data-l1key="generateTranslation" title="{{Option pour les développeurs permettant à}} <?php echo config::byKey('product_name'); ?> {{de générer les phrases à traduire}}" />
						</div>
					</div>
					<hr class="hrPrimary">
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-3 col-xs-6 control-label">{{Date et heure}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Fuseau horaire de votre}} <?php echo config::byKey('product_name'); ?>"></i></sup>
						</label>
						<div class="col-lg-5 col-md-5 col-sm-6 col-xs-6">
							<div class="dropdown dynDropdown">
								<button class="btn btn-default dropdown-toggle configKey" type="button" data-toggle="dropdown" data-l1key="timezone">
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu dropdown-menu-right scrollable-menu">
									<li><a href="#" data-value="Pacific/Midway">(GMT-11:00) Midway Island, Samoa</a></li>
									<li><a href="#" data-value="Pacific/Tahiti">(GMT-10:00) Pacific/Tahiti</a></li>
									<li><a href="#" data-value="America/Adak">(GMT-10:00) Hawaii-Aleutian</a></li>
									<li><a href="#" data-value="Etc/GMT+10">(GMT-10:00) Hawaii</a></li>
									<li><a href="#" data-value="Pacific/Marquesas">(GMT-09:30) Marquesas Islands</a></li>
									<li><a href="#" data-value="Pacific/Gambier">(GMT-09:00) Gambier Islands</a></li>
									<li><a href="#" data-value="America/Anchorage">(GMT-09:00) Alaska</a></li>
									<li><a href="#" data-value="America/Ensenada">(GMT-08:00) Tijuana, Baja California</a></li>
									<li><a href="#" data-value="Etc/GMT+8">(GMT-08:00) Pitcairn Islands</a></li>
									<li><a href="#" data-value="America/Los_Angeles">(GMT-08:00) Pacific Time (US & Canada)</a></li>
									<li><a href="#" data-value="America/Denver">(GMT-07:00) Mountain Time (US & Canada)</a></li>
									<li><a href="#" data-value="America/Chihuahua">(GMT-07:00) Chihuahua, La Paz, Mazatlan</a></li>
									<li><a href="#" data-value="America/Dawson_Creek">(GMT-07:00) Arizona</a></li>
									<li><a href="#" data-value="America/Belize">(GMT-06:00) Saskatchewan, Central America</a></li>
									<li><a href="#" data-value="America/Cancun">(GMT-06:00) Guadalajara, Mexico City, Monterrey</a></li>
									<li><a href="#" data-value="Chile/EasterIsland">(GMT-06:00) Easter Island</a></li>
									<li><a href="#" data-value="America/Chicago">(GMT-06:00) Central Time (US & Canada)</a></li>
									<li><a href="#" data-value="America/New_York">(GMT-05:00) Eastern Time (US & Canada)</a></li>
									<li><a href="#" data-value="America/Havana">(GMT-05:00) Cuba</a></li>
									<li><a href="#" data-value="America/Bogota">(GMT-05:00) Bogota, Lima, Quito, Rio Branco</a></li>
									<li><a href="#" data-value="America/Caracas">(GMT-04:30) Caracas</a></li>
									<li><a href="#" data-value="America/Santiago">(GMT-04:00) Santiago</a></li>
									<li><a href="#" data-value="America/La_Paz">(GMT-04:00) La Paz</a></li>
									<li><a href="#" data-value="Atlantic/Stanley">(GMT-04:00) Faukland Islands</a></li>
									<li><a href="#" data-value="America/Campo_Grande">(GMT-04:00) Brazil</a></li>
									<li><a href="#" data-value="America/Goose_Bay">(GMT-04:00) Atlantic Time (Goose Bay)</a></li>
									<li><a href="#" data-value="America/Glace_Bay">(GMT-04:00) Atlantic Time (Canada)</a></li>
									<li><a href="#" data-value="America/Guadeloupe">(GMT-04:00) Guadeloupe</a></li>
									<li><a href="#" data-value="America/St_Johns">(GMT-03:30) Newfoundland</a></li>
									<li><a href="#" data-value="America/Araguaina">(GMT-03:00) UTC-3</a></li>
									<li><a href="#" data-value="America/Montevideo">(GMT-03:00) Montevideo</a></li>
									<li><a href="#" data-value="America/Miquelon">(GMT-03:00) Miquelon, St. Pierre</a></li>
									<li><a href="#" data-value="America/Godthab">(GMT-03:00) Greenland</a></li>
									<li><a href="#" data-value="America/Argentina/Buenos_Aires">(GMT-03:00) Buenos Aires</a></li>
									<li><a href="#" data-value="America/Sao_Paulo">(GMT-03:00) Brasilia</a></li>
									<li><a href="#" data-value="America/Noronha">(GMT-02:00) Mid-Atlantic</a></li>
									<li><a href="#" data-value="Atlantic/Cape_Verde">(GMT-01:00) Cape Verde Is.</a></li>
									<li><a href="#" data-value="Atlantic/Azores">(GMT-01:00) Azores</a></li>
									<li><a href="#" data-value="Europe/Belfast">(GMT) Greenwich Mean Time : Belfast</a></li>
									<li><a href="#" data-value="Europe/Dublin">(GMT) Greenwich Mean Time : Dublin</a></li>
									<li><a href="#" data-value="Europe/Lisbon">(GMT) Greenwich Mean Time : Lisbon</a></li>
									<li><a href="#" data-value="Europe/London">(GMT) Greenwich Mean Time : London</a></li>
									<li><a href="#" data-value="Africa/Abidjan">(GMT) Monrovia, Reykjavik</a></li>
									<li><a href="#" data-value="Africa/Casablanca">(GMT) Greenwich Mean Time : Casablanca</a></li>
									<li><a href="#" data-value="Europe/Amsterdam">(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna</a></li>
									<li><a href="#" data-value="Europe/Belgrade">(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague</a></li>
									<li><a href="#" data-value="Europe/Brussels">(GMT+01:00) Brussels, Copenhagen, Madrid, Paris</a></li>
									<li><a href="#" data-value="Africa/Algiers">(GMT+01:00) West Central Africa</a></li>
									<li><a href="#" data-value="Africa/Windhoek">(GMT+01:00) Windhoek</a></li>
									<li><a href="#" data-value="Asia/Beirut">(GMT+02:00) Beirut</a></li>
									<li><a href="#" data-value="Africa/Cairo">(GMT+02:00) Cairo</a></li>
									<li><a href="#" data-value="Asia/Gaza">(GMT+02:00) Gaza</a></li>
									<li><a href="#" data-value="Africa/Blantyre">(GMT+02:00) Harare, Pretoria</a></li>
									<li><a href="#" data-value="Asia/Jerusalem">(GMT+02:00) Jerusalem</a></li>
									<li><a href="#" data-value="Europe/Minsk">(GMT+02:00) Minsk</a></li>
									<li><a href="#" data-value="Asia/Damascus">(GMT+02:00) Syria</a></li>
									<li><a href="#" data-value="Europe/Moscow">(GMT+03:00) Moscow, St. Petersburg, Volgograd</a></li>
									<li><a href="#" data-value="Africa/Addis_Ababa">(GMT+03:00) Nairobi</a></li>
									<li><a href="#" data-value="Asia/Tehran">(GMT+03:30) Tehran</a></li>
									<li><a href="#" data-value="Asia/Dubai">(GMT+04:00) Abu Dhabi, Muscat</a></li>
									<li><a href="#" data-value="Asia/Yerevan">(GMT+04:00) Yerevan</a></li>
									<li><a href="#" data-value="Asia/Kabul">(GMT+04:30) Kabul</a></li>
									<li><a href="#" data-value="Asia/Yekaterinburg">(GMT+05:00) Ekaterinburg</a></li>
									<li><a href="#" data-value="Asia/Tashkent">(GMT+05:00) Tashkent</a></li>
									<li><a href="#" data-value="Asia/Kolkata">(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi</a></li>
									<li><a href="#" data-value="Asia/Katmandu">(GMT+05:45) Kathmandu</a></li>
									<li><a href="#" data-value="Asia/Dhaka">(GMT+06:00) Astana, Dhaka</a></li>
									<li><a href="#" data-value="Asia/Novosibirsk">(GMT+06:00) Novosibirsk</a></li>
									<li><a href="#" data-value="Asia/Rangoon">(GMT+06:30) Yangon (Rangoon)</a></li>
									<li><a href="#" data-value="Asia/Bangkok">(GMT+07:00) Bangkok, Hanoi, Jakarta</a></li>
									<li><a href="#" data-value="Asia/Krasnoyarsk">(GMT+07:00) Krasnoyarsk</a></li>
									<li><a href="#" data-value="Asia/Hong_Kong">(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi</a></li>
									<li><a href="#" data-value="Asia/Irkutsk">(GMT+08:00) Irkutsk, Ulaan Bataar</a></li>
									<li><a href="#" data-value="Australia/Perth">(GMT+08:00) Perth</a></li>
									<li><a href="#" data-value="Australia/Eucla">(GMT+08:45) Eucla</a></li>
									<li><a href="#" data-value="Asia/Tokyo">(GMT+09:00) Osaka, Sapporo, Tokyo</a></li>
									<li><a href="#" data-value="Asia/Seoul">(GMT+09:00) Seoul</a></li>
									<li><a href="#" data-value="Asia/Yakutsk">(GMT+09:00) Yakutsk</a></li>
									<li><a href="#" data-value="Australia/Adelaide">(GMT+09:30) Adelaide</a></li>
									<li><a href="#" data-value="Australia/Darwin">(GMT+09:30) Darwin</a></li>
									<li><a href="#" data-value="Australia/Brisbane">(GMT+10:00) Brisbane</a></li>
									<li><a href="#" data-value="Australia/Hobart">(GMT+10:00) Hobart</a></li>
									<li><a href="#" data-value="Asia/Vladivostok">(GMT+10:00) Vladivostok</a></li>
									<li><a href="#" data-value="Australia/Lord_Howe">(GMT+10:30) Lord Howe Island</a></li>
									<li><a href="#" data-value="Etc/GMT-11">(GMT+11:00) Solomon Is., New Caledonia</a></li>
									<li><a href="#" data-value="Asia/Magadan">(GMT+11:00) Magadan</a></li>
									<li><a href="#" data-value="Pacific/Norfolk">(GMT+11:30) Norfolk Island</a></li>
									<li><a href="#" data-value="Asia/Anadyr">(GMT+12:00) Anadyr, Kamchatka</a></li>
									<li><a href="#" data-value="Pacific/Auckland">(GMT+12:00) Auckland, Wellington</a></li>
									<li><a href="#" data-value="Etc/GMT-12">(GMT+12:00) Fiji, Kamchatka, Marshall Is.</a></li>
									<li><a href="#" data-value="Pacific/Chatham">(GMT+12:45) Chatham Islands</a></li>
									<li><a href="#" data-value="Pacific/Tongatapu">(GMT+13:00) Nuku'alofa</a></li>
									<li><a href="#" data-value="Pacific/Kiritimati">(GMT+14:00) Kiritimati</a></li>
								</ul>
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3">
							<a class="btn btn-primary form-control" id="bt_forceSyncHour"><i class="far fa-clock"></i> {{Forcer la synchronisation de l'heure}}</a>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-3 col-xs-6 control-label">{{Serveur de temps optionnel}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Permet d'ajouter un serveur de temps à}} <?php echo config::byKey('product_name'); ?> {{utilisé lorsque}} <?php echo config::byKey('product_name'); ?> {{force la synchronisation de l'heure}}"></i></sup>
						</label>
						<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
							<input type="text"  class="configKey form-control" data-l1key="ntp::optionalServer" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-3 col-xs-6 control-label">{{Ignorer la vérification de l'heure}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Indique à}} <?php echo config::byKey('product_name'); ?> {{de ne pas prendre en compte l'heure du système}}"></i></sup>
						</label>
						<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
							<input type="checkbox" class="configKey" data-l1key="ignoreHourCheck" />
						</div>
					</div>
					<hr class="hrPrimary">
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-3 col-xs-6 control-label">{{Système}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Indique votre type de matériel}}"></i></sup>
						</label>
						<div class="col-lg-5 col-md-5 col-sm-6 col-xs-6">
							<span class="label label-info"><?php echo jeedom::getHardwareName() ?></span>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3">
							<a class="btn btn-default form-control" id="bt_resetHardwareType"><i class="fas fa-sync"></i> {{Rafraîchir}}</a>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-3 col-xs-6 control-label">{{Clé d'installation}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Clé d'installation qui permet d'identifier votre}} <?php echo config::byKey('product_name'); ?> {{quand il communique avec le market}}"></i></sup>
						</label>
						<div class="col-lg-5 col-md-5 col-sm-6 col-xs-6">
							<span class="label label-info"><?php echo jeedom::getHardwareKey() ?></span>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3">
							<a class="btn btn-default form-control" id="bt_resetHwKey"><i class="fas fa-sync"></i> {{Remise à zéro}}</a>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-3 col-xs-6 control-label help" >{{Dernière date connue}}</label>
						<div class="col-lg-5 col-md-5 col-sm-6 col-xs-6">
							<?php
							$cache = cache::byKey('hour');
							$lastKnowDate = $cache->getValue();
							?>
							<span class="label label-info"><?php echo $lastKnowDate ?></span>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3">
							<a class="btn btn-default form-control" id="bt_resetHour"><i class="fas fa-sync"></i> {{Remise à zéro}}</a>
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
							<div class="dropdown dynDropdown">
								<button class="btn btn-default dropdown-toggle configKey" type="button" data-toggle="dropdown" data-l1key="api::core::http::mode">
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu dropdown-menu-right">
									<li><a href="#" data-value="enable">{{Activé}}</a></li>
									<li><a href="#" data-value="whiteip">{{IP blanche}}</a></li>
									<li><a href="#" data-value="localhost">{{Localhost}}</a></li>
									<li><a href="#" data-value="disable">{{Désactivé}}</a></li>
								</ul>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">{{Accès API JSONRPC}}</label>
						<div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
							<div class="dropdown dynDropdown">
								<button class="btn btn-default dropdown-toggle configKey" type="button" data-toggle="dropdown" data-l1key="api::core::jsonrpc::mode">
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu dropdown-menu-right">
									<li><a href="#" data-value="enable">{{Activé}}</a></li>
									<li><a href="#" data-value="whiteip">{{IP blanche}}</a></li>
									<li><a href="#" data-value="localhost">{{Localhost}}</a></li>
									<li><a href="#" data-value="disable">{{Désactivé}}</a></li>
								</ul>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">{{Accès API TTS}}</label>
						<div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
							<div class="dropdown dynDropdown">
								<button class="btn btn-default dropdown-toggle configKey" type="button" data-toggle="dropdown" data-l1key="api::core::tts::mode">
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu dropdown-menu-right">
									<li><a href="#" data-value="enable">{{Activé}}</a></li>
									<li><a href="#" data-value="whiteip">{{IP blanche}}</a></li>
									<li><a href="#" data-value="localhost">{{Localhost}}</a></li>
									<li><a href="#" data-value="disable">{{Désactivé}}</a></li>
								</ul>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">{{Clé API}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Clé API globale de}} <?php echo config::byKey('product_name'); ?>"></i></sup>
						</label>
						<div class="col-lg-5 col-md-5 col-sm-7 col-xs-12">
							<div class="input-group">
								<input class="span_apikey roundedLeft form-control" disabled value="<?php echo $configs['api']; ?>" />
								<span class="input-group-btn">
									<a class="btn btn-default form-control bt_regenerate_api roundedRight" data-plugin="core"><i class="fas fa-sync"></i></a>
								</span>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">{{Clé API Pro}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Clé API Pro de}} <?php echo config::byKey('product_name'); ?>"></i></sup>
						</label>
						<div class="col-lg-5 col-md-5 col-sm-7 col-xs-12">
							<div class="input-group">
								<input class="span_apikey roundedLeft form-control" disabled value="<?php echo $configs['apipro']; ?>" />
								<span class="input-group-btn">
									<a class="btn btn-default form-control bt_regenerate_api roundedRight" data-plugin="pro"><i class="fas fa-sync"></i></a>
								</span>
							</div>
						</div>
						<label class="col-lg-2 col-md-2 col-sm-4 col-xs-12 control-label">{{Accès API}}</label>
						<div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
							<div class="dropdown dropup dynDropdown">
								<button class="btn btn-default dropdown-toggle configKey" type="button" data-toggle="dropdown" data-l1key="api::core::pro::mode">
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu dropdown-menu-right">
									<li><a href="#" data-value="enable">{{Activé}}</a></li>
									<li><a href="#" data-value="disable">{{Désactivé}}</a></li>
								</ul>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">{{Clé Market}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Clé Market de}} <?php echo config::byKey('product_name'); ?>"></i></sup>
						</label>
						<div class="col-lg-5 col-md-5 col-sm-7 col-xs-12">
							<div class="input-group">
								<input class="span_apikey roundedLeft form-control" disabled value="<?php echo $configs['apimarket']; ?>" />
								<span class="input-group-btn">
									<a class="btn btn-default form-control bt_regenerate_api roundedRight" data-plugin="apimarket"><i class="fas fa-sync"></i></a>
								</span>
							</div>
						</div>
						<label class="col-lg-2 col-md-2 col-sm-4 col-xs-12 control-label">{{Accès API}}</label>
						<div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
							<div class="dropdown dropup dynDropdown">
								<button class="btn btn-default dropdown-toggle configKey" type="button" data-toggle="dropdown" data-l1key="api::core::market::mode">
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu dropdown-menu-right">
									<li><a href="#" data-value="enable">{{Activé}}</a></li>
									<li><a href="#" data-value="disable">{{Désactivé}}</a></li>
								</ul>
							</div>
						</div>
					</div>
					<hr class="hrPrimary">
					<?php
					if (init('rescue', 0) == 0) {
						foreach (plugin::listPlugin(true) as $plugin) {
							if (config::byKey('api', $plugin->getId()) == '') {
								continue;
							}
							echo '<div class="form-group">';
							echo '<label class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">{{Clé API}} '.$plugin->getName().' ';
							echo '<sup><i class="fas fa-question-circle tooltips" title="{{Clé API pour le plugin}} '.$plugin->getName().'"></i></sup>';
							echo '</label>';
							echo '<div class="col-lg-5 col-md-5 col-sm-7 col-xs-12">';
							echo '<div class="input-group">';
							echo '<input class="span_apikey roundedLeft form-control" disabled value="' . config::byKey('api', $plugin->getId()) . '" />';
							echo '<span class="input-group-btn">';
							echo '<a class="btn btn-default form-control bt_regenerate_api roundedRight" data-plugin="' . $plugin->getId() . '"><i class="fas fa-sync"></i></a>';
							echo '</span>';
							echo '</div>';
							echo '</div>';
							echo '<label class="col-lg-2 col-md-2 col-sm-4 col-xs-12 control-label">{{Accès API}}</label>';
							echo '<div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">';
							echo '<div class="dropdown dropup dynDropdown">';
							echo '<button class="btn btn-default dropdown-toggle configKey" type="button" data-toggle="dropdown" data-l1key="api::' . $plugin->getId() . '::mode">';
							echo '<span class="caret"></span>';
							echo '</button>';
							echo '<ul class="dropdown-menu dropdown-menu-right">';
							echo '<li><a href="#" data-value="enable">{{Activé}}</a></li>';
							echo '<li><a href="#" data-value="whiteip">{{IP blanche}}</a></li>';
							echo '<li><a href="#" data-value="localhost">{{Localhost}}</a></li>';
							echo '<li><a href="#" data-value="disable">{{Désactivé}}</a></li>';
							echo '</ul>';
							echo '</div>';
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
						<label class="col-lg-4 col-md-4 col-sm-5 col-xs-6 control-label">{{Vérification général}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Permet de lancer le test de consistence de Jeedom}}"></i></sup>
						</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<a class="btn btn-warning" id="bt_consitency"><i class="fas fa-check"></i> {{Lancer}}</a>
						</div>
					</div>
					<legend><i class="fas fa-terminal"></i> {{Système}}</legend>
					<div class="form-group">
						<label class="col-lg-4 col-md-4 col-sm-5 col-xs-6 control-label">{{Administration}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Interface d’administration système.}}"></i></sup>
						</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<a class="btn btn-danger" href="index.php?v=d&p=system"><i class="fas fa-exclamation-triangle"></i> {{Lancer}}</a>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-4 col-sm-5 col-xs-6 control-label">{{Rétablissement des droits des dossiers et fichiers}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Permet de réappliquer les bons droits sur les fichiers}}"></i></sup>
						</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<a class="btn btn-warning" id="bt_cleanFileSystemRight"><i class="fas fa-check"></i> {{Lancer}}</a>
						</div>
					</div>
					<legend><i class="fas fa-indent"></i> {{Editeur de fichiers}}</legend>
					<div class="form-group">
						<label class="col-lg-4 col-md-4 col-sm-5 col-xs-6 control-label">{{Editeur}}</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<a class="btn btn-danger" href="index.php?v=d&p=editor"><i class="fas fa-exclamation-triangle"></i> {{Lancer}}</a>
						</div>
					</div>
					<legend><i class="fas fa-database"></i> {{Base de données}}</legend>
					<div class="form-group">
						<label class="col-lg-4 col-md-4 col-sm-5 col-xs-6 control-label">{{Administration}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Interface d’administration de la base de données.}}"></i></sup>
						</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<a class="btn btn-danger" href="index.php?v=d&p=database"><i class="fas fa-exclamation-triangle"></i> {{Lancer}}</a>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-4 col-sm-5 col-xs-6 control-label">{{Vérification}}</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<a class="btn btn-warning" id="bt_checkDatabase"><i class="fas fa-check"></i> {{Lancer}}</a>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-4 col-sm-5 col-xs-6 control-label">{{Utilisateur}}</label>
						<div class="col-sm-1">
							<?php
							global $CONFIG;
							echo $CONFIG['db']['username'];
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-4 col-sm-5 col-xs-6 control-label">{{Mot de passe}}</label>
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
							<label class="col-md-3 col-sm-4 col-xs-12 control-label">{{Activer l'authentification LDAP}}</label>
							<div class="col-sm-1">
								<input type="checkbox" class="configKey" data-l1key="ldap:enable"/>
							</div>
						</div>
						<div id="div_config_ldap">
							<div class="form-group">
								<label class="col-md-3 col-sm-4 col-xs-12 control-label">{{Hôte}}</label>
								<div class="col-md-3 col-sm-4 col-xs-12">
									<input type="text"  class="configKey form-control" data-l1key="ldap:host" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 col-sm-4 col-xs-12 control-label">{{Port}}</label>
								<div class="col-md-3 col-sm-4 col-xs-12">
									<input type="text"  class="configKey form-control" data-l1key="ldap:port" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 col-sm-4 col-xs-12 control-label">{{Domaine}}</label>
								<div class="col-md-3 col-sm-4 col-xs-12">
									<input type="text"  class="configKey form-control" data-l1key="ldap:domain" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 col-sm-4 col-xs-12 control-label">{{Base DN}}</label>
								<div class="col-md-3 col-sm-4 col-xs-12">
									<input type="text"  class="configKey form-control" data-l1key="ldap:basedn" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 col-sm-4 col-xs-12 control-label">{{Nom d'utilisateur}}</label>
								<div class="col-md-3 col-sm-4 col-xs-12">
									<input type="text"  class="configKey form-control" data-l1key="ldap:username" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 col-sm-4 col-xs-12 control-label">{{Mot de passe}}</label>
								<div class="col-md-3 col-sm-4 col-xs-12">
									<input type="password"  class="configKey form-control" data-l1key="ldap:password" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 col-sm-4 col-xs-12 control-label">{{Champs recherche utilisateur}}</label>
								<div class="col-md-3 col-sm-4 col-xs-12">
									<input type="text" class="configKey form-control" data-l1key="ldap::usersearch" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 col-sm-4 col-xs-12 control-label">{{Filtre (optionnel)}}</label>
								<div class="col-md-3 col-sm-4 col-xs-12">
									<input type="text" class="configKey form-control" data-l1key="ldap:filter" />
								</div>
							</div>
							<div class="form-group has-error">
								<label class="col-md-3 col-sm-4 col-xs-12 control-label">{{Autoriser REMOTE_USER}}</label>
								<div class="col-md-3 col-sm-4 col-xs-12">
									<input type="checkbox"  class="configKey" data-l1key="sso:allowRemoteUser" />
								</div>
							</div>
							<div class="form-group">
								<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6"></div>
								<div class="col-md-3 col-sm-4 col-xs-12">
									<a class="btn btn-default" id="bt_testLdapConnection"><i class="fas fa-cube"></i> Tester</a>
								</div>
							</div>
						</div>
					<?php } else {
						echo '<div class="alert alert-info">{{Librairie LDAP non trouvée. Merci de l\'installer avant de pouvoir utiliser la connexion LDAP}}</div>';
					}?>
					<legend>{{Connexion}}</legend>
					<div class="form-group">
						<label class="col-md-3 col-sm-4 col-xs-12 control-label">{{Durée de vie des sessions}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Durée de vie de votre connexion, en heure, à}} <?php echo config::byKey('product_name'); ?> {{si vous n'avez pas coché la case enregistrer cet ordinateur}}"></i></sup>
							<sub>h</sub>
						</label>
						<div class="col-md-3 col-sm-4 col-xs-12">
							<input type="text"  class="configKey form-control" data-l1key="session_lifetime" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 col-sm-4 col-xs-12 control-label">{{Nombre d'échecs tolérés}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Passé ce nombre, l'IP sera bannie.}}"></i></sup>
						</label>
						<div class="col-md-3 col-sm-4 col-xs-12">
							<input type="text" class="configKey form-control" data-l1key="security::maxFailedLogin" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 col-sm-4 col-xs-12 control-label">{{Temps maximum entre les échecs}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Temps en secondes}}"></i></sup>
							<sub>s</sub>
						</label>

						<div class="col-md-3 col-sm-4 col-xs-12">
							<input type="text" class="configKey form-control" data-l1key="security::timeLoginFailed" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 col-sm-4 col-xs-12 control-label">{{Durée du bannissement}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Durée en secondes, -1 pour un bannissement infini}}"></i></sup>
							<sub>s</sub>
						</label>
						<div class="col-md-3 col-sm-4 col-xs-12">
							<input type="text" class="configKey form-control" data-l1key="security::bantime" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 col-sm-4 col-xs-12 control-label">{{Liste blanche}}
							<sup><i class="fas fa-question-circle tooltips" title="{{IPs ou masques séparés par ; ex: 127.0.0.1;192.168.*.*}}"></i></sup>
						</label>
						<div class="col-md-3 col-sm-4 col-xs-12">
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
								<div class="dropdown dynDropdown">
									<button class="btn btn-default dropdown-toggle configKey roundedLeft" type="button" data-toggle="dropdown" data-l1key="internalProtocol">
										<span class="caret"></span>
									</button>
									<ul class="dropdown-menu dropdown-menu-right">
										<li><a href="#" data-value="">{{Aucun}}</a></li>
										<li><a href="#" data-value="http://">{{HTTP}}</a></li>
										<li><a href="#" data-value="https://">{{HTTPS}}</a></li>
									</ul>
								</div>
								<span class="input-group-addon">://</span>
								<input type="text" class="configKey form-control" data-l1key="internalAddr" />
								<span class="input-group-addon">:</span>
								<input type="text" class="configKey form-control" data-l1key="internalPort" />
								<span class="input-group-addon">/</span>
								<input type="text" class="configKey form-control roundedRight" data-l1key="internalComplement" />
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
								<div class="dropdown dynDropdown">
									<button class="btn btn-default dropdown-toggle configKey roundedLeft" type="button" data-toggle="dropdown" data-l1key="externalProtocol">
										<span class="caret"></span>
									</button>
									<ul class="dropdown-menu dropdown-menu-right">
										<li><a href="#" data-value="">{{Aucun}}</a></li>
										<li><a href="#" data-value="http://">{{HTTP}}</a></li>
										<li><a href="#" data-value="https://">{{HTTPS}}</a></li>
									</ul>
								</div>
								<span class="input-group-addon">://</span>
								<input type="text" class="configKey form-control" data-l1key="externalAddr" />
								<span class="input-group-addon">:</span>
								<input type="text" class="configKey form-control" data-l1key="externalPort" />
								<span class="input-group-addon">/</span>
								<input type="text" class="configKey form-control roundedRight" data-l1key="externalComplement" />
							</div>
						</div>
					</div>
				</fieldset>
			</form>
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
					<form class="form-horizontal">
						<fieldset>
							<div class="form-group has-error">
								<label class="col-xs-6 control-label">{{Désactiver la gestion du réseau par}} <?php echo config::byKey('product_name'); ?></label>
								<div class="col-xs-4">
									<input type="checkbox" class="configKey" data-l1key="network::disableMangement" />
								</div>
							</div>
							<div class="form-group col-xs-12">
								<label class="col-xs-6 control-label">{{Masque IP local (utile que pour les installations type docker, sous la forme 192.168.1.*)}}</label>
								<div class="col-xs-6">
									<input type="text"  class="configKey form-control" data-l1key="network::localip" />
								</div>
							</div>
						</fieldset>
					</form>
				</div>
				<div class="col-sm-6">
					<form class="form-horizontal">
						<fieldset>
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
									echo '<div class="alert alert-warning">{{Attention : cette fonctionnalité n\'est pas disponible dans le service pack community (voir votre service pack sur votre page profil sur le }}<a href="https://www.jeedom.com/market/index.php?v=d&p=connection" target="_blanck"> market</a>)</div>';
									continue;
								}
								echo '<div class="form-group col-xs-12">';
								echo '<label class="col-xs-4 control-label">{{Utiliser les DNS}} ' . config::byKey('product_name') . '</label>';
								echo '<div class="col-xs-8">';
								echo '<input type="checkbox" class="configKey" data-l1key="' . $key . '::allowDNS" />';
								echo '</div>';
								echo '</div>';
								echo '<div class="form-group col-xs-12">';
								echo '<label class="col-xs-4 control-label">{{Statut DNS}}</label>';
								echo '<div class="col-xs-8">';
								if ($configs['market::allowDNS'] == 1 && network::dns_run()) {
									echo '<span class="label label-success">{{Démarré : }} <a href="' . network::getNetworkAccess('external') . '" target="_blank" style="color:white;text-decoration: underline;">' . network::getNetworkAccess('external') . '</a></span>';
								} else {
									echo '<span class="label label-warning" title="{{Normal si vous n\'avez pas coché la case : Utiliser les DNS}} ' . config::byKey('product_name') . '">{{Arrêté}}</span>';
								}
								echo '</div>';
								echo '</div>';
								echo '<div class="form-group col-xs-12">';
								echo '<label class="col-xs-4 control-label">{{Gestion}}</label>';
								echo '<div class="col-xs-8">';
								echo '<a class="btn btn-success" id="bt_restartDns"><i class=\'fa fa-play\'></i> {{(Re)démarrer}}</a> ';
								echo '<a class="btn btn-danger" id="bt_haltDns"><i class=\'fa fa-stop\'></i> {{Arrêter}}</a>';
								echo '</div>';
								echo '</div>';
							}
							?>
						</fieldset>
					</form>
					<form class="form-horizontal">
						<fieldset>
							<legend>{{Utiliser un proxy pour le market}}</legend>
							<div class="form-group">
								<label class="col-xs-4 control-label">{{Activer le proxy}}</label>
								<div class="col-xs-1">
									<input type="checkbox" data-l1key="proxyEnabled" class="configKey">
								</div>
							</div>
							<div class="form-group">
								<label class="col-xs-4 control-label">{{Addresse proxy}}</label>
								<div class="col-xs-4">
									<input class="configKey form-control" type="text" data-l1key="proxyAddress">
								</div>
							</div>
							<div class="form-group">
								<label class="col-xs-4 control-label">{{Port du proxy}}</label>
								<div class="col-xs-4">
									<input class="configKey form-control" data-l1key="proxyPort" type="text">
								</div>
							</div>
							<div class="form-group">
								<label class="col-xs-4 control-label">{{Nom d'utilisateur}}</label>
								<div class="col-xs-4">
									<input class="configKey form-control" type="text" data-l1key="proxyLogins" autocomplete="new-password">
								</div>
							</div>
							<div class="form-group">
								<label class="col-xs-4 control-label">{{Mot de passe}}</label>
								<div class="col-xs-4">
									<input class="configKey form-control" type="password" data-l1key="proxyPassword" autocomplete="new-password">
								</div>
							</div>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane" id="interfacetab">
			<br/>
			<form class="form-horizontal">
				<fieldset>
					<legend>{{Thèmes}}</legend>
					<div class="form-group">
						<label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">{{Thème Desktop principal}}</label>
						<div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
							<div class="dropdown dynDropdown">
								<button class="btn btn-default dropdown-toggle configKey" type="button" data-toggle="dropdown" data-l1key="default_bootstrap_theme">
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu dropdown-menu-right">
									<?php
									foreach (ls(__DIR__ . '/../../core/themes') as $dir) {
										if (is_dir(__DIR__ . '/../../core/themes/' . $dir . '/desktop')) {
											echo '<li><a href="#" data-value="' . trim($dir, '/') . '">' . ucfirst(str_replace('_', ' ', trim($dir, '/'))) . '</a></li>';
										}
									}
									?>
								</ul>
							</div>
						</div>
						<label class="col-lg-2 col-md-3 col-sm-3 col-xs-6 control-label">{{Thème Desktop alternatif}}</label>
						<div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
							<div class="dropdown dynDropdown">
								<button class="btn btn-default dropdown-toggle configKey" type="button" data-toggle="dropdown" data-l1key="default_bootstrap_theme_night">
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu dropdown-menu-right">
									<?php
									foreach (ls(__DIR__ . '/../../core/themes') as $dir) {
										if (is_dir(__DIR__ . '/../../core/themes/' . $dir . '/desktop')) {
											echo '<li><a href="#" data-value="' . trim($dir, '/') . '">' . ucfirst(str_replace('_', ' ', trim($dir, '/'))) . '</a></li>';
										}
									}
									?>
								</ul>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">{{Thème Mobile principal}}</label>
						<div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
							<div class="dropdown dynDropdown">
								<button class="btn btn-default dropdown-toggle configKey" type="button" data-toggle="dropdown" data-l1key="mobile_theme_color">
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu dropdown-menu-right">
									<?php
									foreach (ls(__DIR__ . '/../../core/themes') as $dir) {
										if (is_dir(__DIR__ . '/../../core/themes/' . $dir . '/mobile')) {
											echo '<li><a href="#" data-value="' . trim($dir, '/') . '">' . ucfirst(str_replace('_', ' ', trim($dir, '/'))) . '</a></li>';
										}
									}
									?>
								</ul>
							</div>
						</div>
						<label class="col-lg-2 col-md-3 col-sm-3 col-xs-6 control-label">{{Thème Mobile alternatif}}</label>
						<div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
							<div class="dropdown dynDropdown">
								<button class="btn btn-default dropdown-toggle configKey" type="button" data-toggle="dropdown" data-l1key="mobile_theme_color_night">
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu dropdown-menu-right">
									<?php
									foreach (ls(__DIR__ . '/../../core/themes') as $dir) {
										if (is_dir(__DIR__ . '/../../core/themes/' . $dir . '/mobile')) {
											echo '<li><a href="#" data-value="' . trim($dir, '/') . '">' . ucfirst(str_replace('_', ' ', trim($dir, '/'))) . '</a></li>';
										}
									}
									?>
								</ul>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">{{Thème principal de}}</label>
						<div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
							<input type="text" class="configKey form-control" data-l1key="theme_start_day_hour"/>
						</div>
						<label class="col-lg-2 col-md-3 col-sm-3 col-xs-6 control-label">{{à}}</label>
						<div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
							<input type="text" class="configKey form-control" data-l1key="theme_end_day_hour"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 col-md-4 col-sm-3 col-xs-6 control-label">{{Basculer le thème en fonction de l'heure}}</label>
						<div class="col-lg-1 col-md-1 col-sm-3 col-xs-6">
							<input type="checkbox" class="configKey" data-l1key="theme_changeAccordingTime"/>
						</div>
						<label class="col-lg-3 col-md-4 col-sm-3 col-xs-6 control-label">{{Capteur de luminosité (mobile)}}</label>
						<div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
							<input type="checkbox" class="configKey" data-l1key="mobile_theme_useAmbientLight"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 col-md-4 col-sm-3 col-xs-6 control-label">{{Masquer les images de fond}}</label>
						<div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
							<input type="checkbox" class="configKey" data-l1key="hideBackgroundImg"/>
						</div>
					</div>
					<legend>{{Tuiles}}</legend>
					<div class="form-group">
						<label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">{{Pas horizontal}}
							<sup><i class="fas fa-question-circle tooltips" title="Contraint la largeur des tuiles tous les x pixels"></i></sup>
						</label>
						<div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
							<input type="number" class="configKey form-control" data-l1key="widget::step::width" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">{{Pas vertical}}
							<sup><i class="fas fa-question-circle tooltips" title="Contraint la hauteur des tuiles tous les x pixels"></i></sup>
						</label>
						<div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
							<input type="number" class="configKey form-control" data-l1key="widget::step::height" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">{{Marge}}
							<sup><i class="fas fa-question-circle tooltips" title="Espace vertical et horizontal entre les tuiles, en pixel"></i></sup>
						</label>
						<div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
							<input type="number" class="configKey form-control" data-l1key="widget::margin" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">{{Centrage vertical des tuiles}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Centre verticalement le contenu des tuiles}}"></i></sup>
						</label>
						<div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
							<input type="checkbox" class="configKey form-control" data-l1key="interface::advance::vertCentering" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">{{icônes widgets colorées}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Coloration des icônes de widgets en fonction de leur état. Modifiable par scénario, setColoredIcon ('Coloration des icônes').}}"></i></sup>
						</label>
						<div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
							<input type="checkbox" class="configKey form-control" data-l1key="interface::advance::coloredIcons" />
						</div>
					</div>

					<legend>{{Personnalisation}}</legend>
					<div class="form-group">
						<label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">{{Activer}}
							<sup><i class="fas fa-question-circle tooltips" title="Activer la personnalisation pour écraser les paramètres par défaut des thèmes"></i></sup>
						</label>
						<div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
							<input type="checkbox" class="configKey form-control" data-l1key="interface::advance::enable" />
						</div>
						<span class="col-lg-2 col-md-3 col-sm-3 col-xs-6"></span>
						<div class="col-lg-2 col-md-3 col-sm-3 col-xs-6">
							<a class="btn btn-primary form-control" href="index.php?v=d&p=custom"><i class="fas fa-pencil-alt"></i>&nbsp;&nbsp;Personnalisation avancée</a>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">{{Transparence}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Transparence des tuiles}}"></i></sup>
						</label>
						<div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
							<input type="number" min="0" max="1" step="0.1" class="configKey form-control" data-l1key="css::background-opacity" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">{{Arrondi}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Arrondi des éléments de l'interface (Tuiles, boutons etc). 0 pour pas d'arrondi}}"></i></sup>
						</label>
						<div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
							<input type="number" min="0" max="1" step="0.1" class="configKey form-control" data-l1key="css::border-radius" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">{{Désactiver les ombres}}</label>
						<div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
							<input type="checkbox" class="configKey form-control" data-l1key="widget::shadow" />
						</div>
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
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Période de calcul pour min, max, moyenne}}
							<sub>h</sub>
						</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<input type="text"  class="configKey form-control" data-l1key="historyCalculPeriod" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Période de calcul pour la tendance}}
							<sub>h</sub>
						</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<input type="text"  class="configKey form-control" data-l1key="historyCalculTendance" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Délai avant archivage}}
							<sub>h</sub>
						</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<input type="text"  class="configKey form-control" data-l1key="historyArchiveTime" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Archiver par paquet de}}
							<sub>h</sub>
						</label>
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
							<div class="dropdown dropup dynDropdown">
								<button class="btn btn-default dropdown-toggle configKey" type="button" data-toggle="dropdown" data-l1key="history::defautShowPeriod">
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu dropdown-menu-right">
									<li><a href="#" data-value="-6 month">{{6 mois}}</a></li>
									<li><a href="#" data-value="-3 month">{{3 mois}}</a></li>
									<li><a href="#" data-value="-1 month">{{1 mois}}</a></li>
									<li><a href="#" data-value="-1 week">{{1 semaine}}</a></li>
									<li><a href="#" data-value="-1 day">{{1 jour}}</a></li>
								</ul>
							</div>
						</div>
					</div>
				</fieldset>
			</form>
			<legend>{{Push}}</legend>
			<form class="form-horizontal">
				<fieldset>
					<div class="form-group">
						<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{URL de push globale}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Mettez ici l'URL à appeler lors d'une mise à jour de la valeur des commandes. Vous pouvez utiliser les tags suivants : #value# (valeur de la commande), #cmd_id# (id de la commande) et #cmd_name# (nom de la commande)}}"></i></sup>
						</label>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
							<input type="text"  class="configKey form-control" data-l1key="cmdPushUrl">
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
							echo '<span class="label label-primary"><span id="span_cacheObject">' . $stats['count'] . '</span> ' . __('objets', __FILE__) . '</span>';
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Moteur de cache}}</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<div class="dropdown dynDropdown">
								<button class="btn btn-default dropdown-toggle configKey" type="button" data-toggle="dropdown" data-l1key="cache::engine">
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu dropdown-menu-right">
									<li><a href="#" data-value="FilesystemCache">{{Système de fichiers (<?php echo cache::getFolder(); ?>)}}</a></li>
									<?php if (class_exists('memcached')) {?>
										<li><a href="#" data-value="MemcachedCache">{{Memcached}}</a></li>
									<?php }?>
									<?php if (class_exists('redis')) {?>
										<li><a href="#" data-value="RedisCache">{{Redis}}</a></li>
									<?php }?>
								</ul>
							</div>
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
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Nettoyer le cache}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Force la suppression des objets qui ne sont plus utiles. Jeedom le fait automatiquement toutes les nuits.}}"></i></sup>
						</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<a class="btn btn-warning" id="bt_cleanCache"><i class="fas fa-magic"></i> {{Nettoyer}}</a>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Vider toutes les données en cache}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Vide complètement le cache. Attention cela peut faire perdre des données.}}"></i></sup>
						</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<a class="btn btn-danger" id="bt_flushCache"><i class="fas fa-trash"></i> {{Vider}}</a>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Vider le cache des widgets}}</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<a class="btn btn-warning" id="bt_flushWidgetCache"><i class="fas fa-trash"></i> {{Vider}}</a>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Désactiver le cache des widgets}}</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<input type="checkbox"  class="configKey form-control" data-l1key="widget::disableCache" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Temps de pause pour le long polling}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Fréquence à laquelle Jeedom vérifie si il y a des événements en attente.}}"></i></sup>
							<sub>s</sub>
						</label>
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
					<div class="form-group">
						<label class="col-lg-3 col-md-4 col-sm-4 col-xs-6 control-label">{{Sensibilité}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Plus la sensibilité est basse (de 1 à 99), plus la correspondance doit être exacte.}}"></i></sup>
						</label>
						<div class="col-lg-6 col-md-8 col-sm-8 col-xs-6">
							<div class="input-group">
								<span class="input-group-addon roundedLeft" style="width:90px">{{1 mot}}</span>
								<input type="text" class="configKey form-control" data-l1key="interact::confidence1"/>
								<span class="input-group-addon" style="width:90px">{{2 mots}}</span>
								<input type="text" class="configKey form-control" data-l1key="interact::confidence2"/>
								<span class="input-group-addon" style="width:90px">{{3 mots}}</span>
								<input type="text" class="configKey form-control" data-l1key="interact::confidence3"/>
								<span class="input-group-addon" style="width:90px">> {{3 mots}}</span>
								<input type="text" class="configKey form-control roundedRight" data-l1key="interact::confidence"/>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 col-md-4 col-sm-4 col-xs-6 control-label">{{Réduire le poids de}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Distance de Levenshtein pour le calcul de correspondance (Nombre de différences entre les deux chaines) en fonction du nombre de mots.}}"></i></sup>
						</label>
						<div class="col-lg-6 col-md-8 col-sm-8 col-xs-6">
							<div class="input-group">
								<span class="input-group-addon roundedLeft" style="width:90px">{{1 mot}}</span>
								<input type="text" class="configKey form-control" data-l1key="interact::weigh1"/>
								<span class="input-group-addon" style="width:90px">{{2 mots}}</span>
								<input type="text" class="configKey form-control" data-l1key="interact::weigh2"/>
								<span class="input-group-addon" style="width:90px">{{3 mots}}</span>
								<input type="text" class="configKey form-control" data-l1key="interact::weigh3"/>
								<span class="input-group-addon" style="width:90px">{{4 mots}}</span>
								<input type="text" class="configKey form-control roundedRight" data-l1key="interact::weigh4"/>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 col-md-4 col-sm-4 col-xs-6 control-label">{{Ne pas répondre si l'interaction n'est pas comprise}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Par défaut Jeedom répond “je n’ai pas compris” si aucune interaction ne correspond.}}"></i></sup>
						</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<input type="checkbox" class="configKey" data-l1key="interact::noResponseIfEmpty"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 col-md-4 col-sm-4 col-xs-6 control-label">{{Regex générale d'exclusion pour les interactions}}</label>
						<div class="col-lg-9 col-md-8 col-sm-8 col-xs-6">
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
								<input type="text"  class="configKey form-control roundedLeft" data-l1key="interact::warnme::defaultreturncmd" />
								<span class="input-group-btn">
									<a class="btn btn-default cursor bt_selectWarnMeCmd roundedRight" title="Rechercher une commande"><i class="fas fa-list-alt"></i></a>
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

					<legend>{{Couleurs}}<i class="fas fa-plus-circle pull-right cursor" id="bt_addColorConvert"></i></legend>

					<table class="table table-condensed table-bordered" id="table_convertColor" >
						<thead>
							<tr>
								<th>{{Nom}}</th><th>{{Code HTML}}</th><th></th>
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
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Delai d'attente après génération de la page}}
							<sub>ms</sub>
						</label>
						<div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
							<input class="configKey form-control" data-l1key="report::delay" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Nettoyer les rapports plus anciens de}}
							<sub>j</sub>
						</label>
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
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Profondeur pour les scénarios}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Nombre de niveaux maximal d’éléments à afficher dans les graphiques de liens de scénario}}"></i></sup>
						</label>
						<div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
							<input class="configKey form-control" data-l1key="graphlink::scenario::drill" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Profondeur pour les objets}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Nombre de niveaux maximal d’éléments à afficher dans les graphiques de liens d'objet}}"></i></sup>
						</label>
						<div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
							<input class="configKey form-control" data-l1key="graphlink::jeeObject::drill" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Profondeur pour les équipements}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Nombre de niveaux maximal d’éléments à afficher dans les graphiques de liens d'équipement'}}"></i></sup>
						</label>
						<div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
							<input class="configKey form-control" data-l1key="graphlink::eqLogic::drill" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Profondeur pour les commandes}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Nombre de niveaux maximal d’éléments à afficher dans les graphiques de liens de commande}}"></i></sup>
						</label>
						<div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
							<input class="configKey form-control" data-l1key="graphlink::cmd::drill" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Profondeur pour les variables}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Nombre de niveaux maximal d’éléments à afficher dans les graphiques de liens de variable}}"></i></sup>
						</label>
						<div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
							<input class="configKey form-control" data-l1key="graphlink::dataStore::drill" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Paramètre de prerender}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Permet d’agir sur la disposition du graphique (défaut 3)}}"></i></sup>
						</label>
						<div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
							<input class="configKey form-control" data-l1key="graphlink::prerender" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-5 col-sm-6 col-xs-6 control-label">{{Paramètre de render}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Permet d’agir sur la disposition du graphique selon les relations entre éléments (défaut 3000)}}"></i></sup>
						</label>
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
								<th>{{Clé}}</th>
								<th>{{Nom}}</th>
								<th>{{Calcul}}</th>
								<th>{{Icône}}</th>
								<th>{{Unité}}</th>
								<th>{{Méthode de comptage}}</th>
								<th>{{Affiché même si nulle}}</th>
								<th>{{Lier à un virtuel}}</th>
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
						<label class="col-lg-4 col-md-4 col-sm-4 col-xs-3 control-label">{{Nombre maximum d'évènements sur la Timeline}}</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<input type="text" class="configKey form-control" data-l1key="timeline::maxevent"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-4 col-sm-4 col-xs-3 control-label">{{Supprimer tous les évènements de la Timeline}}</label>
						<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
							<a type="text" class="btn btn-danger" id="bt_removeTimelineEvent" ><i class="fas fa-trash"></i> {{Supprimer}}</a>
						</div>
					</div>
					<legend>{{Messages}}</legend>
					<div class="form-group">
						<label class="col-lg-4 col-md-4 col-sm-4 col-xs-3 control-label">{{Ajouter un message à chaque erreur dans les logs}}</label>
						<div class="col-sm-1">
							<input type="checkbox" class="configKey" data-l1key="addMessageForErrorLog" checked/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-4 col-md-4 col-sm-4 col-xs-3 control-label">{{Action sur message}}</label>
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
							<a class="btn btn-success" id="bt_addActionOnMessage"><i class="fas fa-plus-circle"></i> {{Ajouter}}</a>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-2 hidden-xs"></div>
						<div class="col-sm-10 col-xs-12">

						</div>
					</div>
				</fieldset>
			</form>
			<form class="form-horizontal">
				<div id="div_actionOnMessage"></div>
			</form>
			<ul class="nav nav-tabs" role="tablist">
				<li role="presentation" class="active"><a href="#log_alertes" role="tab" data-toggle="tab"><i class="fas fa-bell"></i> {{Alertes}}</a></li>
				<li role="presentation"><a href="#log_log" role="tab" data-toggle="tab"><i class="fas fa-file"></i> {{Logs}}</a></li>
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
								echo '<input type="checkbox" class="configKey" data-l1key="alert::addMessageOn' . ucfirst($level) . '"/>';
								echo '</div>';
								echo '</div>';
								echo '<div class="form-group">';
								echo '<label class="col-lg-4 col-md-4 col-sm-4 col-xs-3 control-label">{{Commande sur}} ' . $value['name'] . '</label>';
								echo '<div class="col-lg-4 col-md-6 col-sm-6 col-xs-8">';
								echo '<div class="input-group">';
								echo '<input type="text"  class="configKey form-control roundedLeft" data-l1key="alert::' . $level . 'Cmd" />';
								echo '<span class="input-group-btn">';
								echo '<a class="btn btn-default cursor bt_selectAlertCmd roundedRight" title="Rechercher une commande" data-type="' . $level . '"><i class="fas fa-list-alt"></i></a>';
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
									<div class="dropdown dynDropdown">
										<button class="btn btn-default dropdown-toggle configKey" type="button" data-toggle="dropdown" data-l1key="log::engine">
											<span class="caret"></span>
										</button>
										<ul class="dropdown-menu dropdown-menu-right">
											<li><a href="#" data-value="StreamHandler">{{Defaut}}</a></li>
											<li><a href="#" data-value="SyslogHandler">{{Syslog}}</a></li>
											<li><a href="#" data-value="SyslogUdp">{{SyslogUdp}}</a></li>
										</ul>
									</div>
								</div>
							</div>
							<div class="logEngine SyslogUdp">
								<div class="form-group">
									<label class="col-lg-4 col-md-4 col-sm-4 col-xs-3 control-label">{{Adresse syslog UDP}}</label>
									<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
										<input type="text"  class="configKey form-control" data-l1key="log::syslogudphost" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-4 col-md-4 col-sm-4 col-xs-3 control-label">{{Port syslog UDP}}</label>
									<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
										<input type="text"  class="configKey form-control" data-l1key="log::syslogudpport" />
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-4 col-md-4 col-sm-4 col-xs-3 control-label">{{Format des logs}}</label>
								<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
									<input type="text" class="configKey form-control" data-l1key="log::formatter" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-4 col-md-4 col-sm-4 col-xs-3 control-label">{{Nombre de lignes maximum dans un fichier de log}}</label>
								<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
									<input type="text" class="configKey form-control" data-l1key="maxLineLog"/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-4 col-md-4 col-sm-4 col-xs-3 control-label">{{Niveau de log par défaut}}</label>
								<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
									<div class="dropdown dynDropdown">
										<button class="btn btn-default dropdown-toggle configKey" type="button" data-toggle="dropdown" data-l1key="log::level">
											<span class="caret"></span>
										</button>
										<ul class="dropdown-menu dropdown-menu-right">
											<li><a href="#" data-value="100">{{Debug}}</a></li>
											<li><a href="#" data-value="200">{{Info}}</a></li>
											<li><a href="#" data-value="300">{{Warning}}</a></li>
											<li><a href="#" data-value="400">{{Erreur}}</a></li>
										</ul>
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
						<label class="col-lg-4 col-sm-4 col-xs-12 control-label">{{Échecs avant désactivation}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Nombre d'échecs avant désactivation de l'équipement.}}"></i></sup>
						</label>
						<div class="col-lg-1 col-sm-1 col-xs-4">
							<input type="text"  class="configKey form-control" data-l1key="numberOfTryBeforeEqLogicDisable" />
						</div>
						<br/><br/>
					</div>
					<div class="col-lg-12 form-group">
						<label class="col-lg-4 col-sm-4 col-xs-12 control-label">{{Seuil des piles}}</label>
					</div>
					<div class="col-lg-12 form-group">
						<label class="col-lg-4 col-sm-4 col-xs-12 control-label"><i class="warning jeedom-batterie1" style="font-size:36px;vertical-align: middle;"></i> {{Inférieur à}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Si la charge passe en dessous de}}"></i></sup>
						</label>
						<div class="col-lg-1 col-sm-1 col-xs-4">
							<input class="configKey form-control" data-l1key="battery::warning" />
						</div>
						<label class="col-lg-1 col-sm-4 col-xs-12 eqLogicAttr label label-warning">{{Warning}}</label>
					</div>
					<div class="col-lg-12 form-group">
						<label class="col-lg-4 col-sm-4 col-xs-12 control-label"><i class="danger jeedom-batterie0" style="font-size:36px;vertical-align: middle;"></i> {{Inférieur à}}
							<sup><i class="fas fa-question-circle tooltips" title="{{Si la charge passe en dessous de}}"></i></sup>
						</label>
						<div class="col-lg-1 col-sm-1 col-xs-4">
							<input class="configKey form-control" data-l1key="battery::danger" />
						</div>
						<label class="col-lg-1 col-sm-4 col-xs-12 eqLogicAttr label label-danger">{{Danger}}</label>
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
								<label class="col-lg-6 col-xs-6 control-label">{{Source de mise à jour du core}}</label>
								<div class="col-lg-4 col-xs-6">
									<div class="dropdown dynDropdown">
										<button class="btn btn-default dropdown-toggle configKey" type="button" data-toggle="dropdown" data-l1key="core::repo::provider">
											<span class="caret"></span>
										</button>
										<ul class="dropdown-menu dropdown-menu-right">
											<li><a href="#" data-value="default">{{Défaut}}</a></li>
											<?php
											foreach ($repos as $key => $value) {
												if (!isset($value['scope']['core']) || $value['scope']['core'] === false) {
													continue;
												}
												if ($configs[$key . '::enable'] == 0) {
													continue;
												}
												echo '<li><a href="#" data-value="' . $key . '">' . $value['name'] . '</a></li>';
											}
											?>
										</ul>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-6 col-xs-6 control-label">{{Version du core}}
									<sup><i class="fas fa-question-circle tooltips" title="{{Version installée du core, pour la vérification de mise à jour disponible.}}"></i></sup>
								</label>
								<div class="col-lg-4 col-xs-6">
									<div class="dropdown dynDropdown">
										<button class="btn btn-default dropdown-toggle configKey" type="button" data-toggle="dropdown" data-l1key="core::branch">
											<span class="caret"></span>
										</button>
										<ul class="dropdown-menu dropdown-menu-right">
											<li><a href="#" data-value="master">{{Stable}}</a></li>
											<li><a href="#" data-value="release">{{Release (Pas de support)}}</a></li>
											<li><a href="#" data-value="beta">{{Beta (Pas de support)}}</a></li>
											<li><a href="#" data-value="alpha">{{Alpha (Pas de support)}}</a></li>
										</ul>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-6 col-xs-6 control-label">{{Vérification automatique des mises à jour}}</label>
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
											echo '<input type="password" autocomplete="new-password" class="configKey form-control" data-l1key="' . $key . '::' . $pKey . '" value="' . $default . '" />';
											break;
											case 'select':
											echo '<div class="dropdown dropup dynDropdown">';
											echo '<button class="btn btn-default dropdown-toggle configKey" type="button" data-toggle="dropdown" data-l1key="' . $key . '::' . $pKey . '">';
											echo '<span class="caret"></span>';
											echo '</button>';
											echo '<ul class="dropdown-menu dropdown-menu-right">';
											foreach ($parameter['values'] as $optkey => $optval) {
												echo '<li><a href="#" data-value="' . $optkey . '">' . $optval . '</a></li>';
											}
											echo '</ul>';
											echo '</div>';
											break;
										}
										echo '</div>';
										echo '</div>';
									}
									if (isset($value['scope']['test']) && $value['scope']['test']) {
										echo '<div class="form-group">';
										echo '<label class="col-lg-4 col-md-6 col-sm-6 col-xs-6 control-label">{{Tester}}</label>';
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
		</div>
	</div>
</div>

<?php include_file("desktop", "administration", "js");?>
