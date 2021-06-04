<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$report_path = __DIR__ . '/../../data/report/';
?>

<div class="row row-overflow">
	<div class="col-lg-2 col-md-3 col-sm-3" style="margin-top : 4px;">
		<div class="bs-sidebar" style="margin-top : 4px;">
			<ul id="ul_region" class="nav nav-list bs-sidenav">
				<label><i class="fas fa-bars"></i> {{Type}}</label>
				<li class="cursor li_type active" data-type="view"><a>{{Vues}}</a></li>
				<li class="cursor li_type" data-type="plan"><a>{{Design}}</a></li>
				<li class="cursor li_type" data-type="plugin"><a>{{Plugin}}</a></li>
				<li class="cursor li_type" data-type="other"><a>{{Autre}}</a></li>
			</ul>
		</div>
		<hr class="hrPrimary">
		<div class="bs-sidebar reportType view">
			<ul id="ul_view" class="nav nav-list bs-sidenav">
				<label><i class="far fa-image"></i> {{Vues}}</label>
				<li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
				<?php
				$echo = '';
				foreach (view::all() as $view) {
					$number = count(ls($report_path . '/view/' . $view->getId(), '*'));
					$echo .= '<li class="cursor li_reportType" data-type="view" data-id="' . $view->getId() . '"><a>' . $view->getName() . ' (<span class="number">' . $number . '</span>)</a></li>';
				}
				if ($echo != '') echo $echo;
				?>
			</ul>
		</div>

		<div class="bs-sidebar reportType plan" style="display:none">
			<ul id="ul_plan" class="nav nav-list bs-sidenav">
				<label><i class="fas fa-paint-brush"></i> {{Design}}</label>
				<li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
				<?php
				$echo = '';
				foreach (planHeader::all() as $plan) {
					$number = count(ls($report_path . '/plan/' . $plan->getId(), '*'));
					$echo .= '<li class="cursor li_reportType" data-type="plan" data-id="' . $plan->getId() . '"><a>' . $plan->getName() . ' (<span class="number">' . $number . '</span>)</a></li>';
				}
				if ($echo != '') echo $echo;
				?>
			</ul>
		</div>

		<div class="bs-sidebar reportType plugin" style="display:none">
			<ul id="ul_plan" class="nav nav-list bs-sidenav">
				<label><i class="fas fa-tags"></i> {{Plugin}}</label>
				<li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
				<?php
				$echo = '';
				foreach (plugin::listPlugin(true) as $plugin) {
					if ($plugin->getDisplay() == '') {
						continue;
					}
					$number = count(ls($report_path . '/plugin/' . $plugin->getId(), '*'));
					$echo .= '<li class="cursor li_reportType" data-type="plugin" data-id="' . $plugin->getId() . '"><a>' . $plugin->getName() . ' (<span class="number">' . $number . '</span>)</a></li>';
				}
				if ($echo != '') echo $echo;
				?>
			</ul>
		</div>

		<div class="bs-sidebar reportType other" style="display:none">
			<ul id="ul_plan" class="nav nav-list bs-sidenav">
				<label><i class="fas fa-network-wired"></i> {{Autre}}</label>
				<li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
				<li class="cursor li_reportType" data-type="other" data-id="eqAnalyse"><a>{{Analyse Equipements}}</a></li>
				<li class="cursor li_reportType" data-type="other" data-id="timeline"><a>{{Timeline}}</a></li>
				<li class="cursor li_reportType" data-type="other" data-id="url"><a>{{URL}}</a></li>
			</ul>
		</div>
	</div>

	<div class="col-lg-2 col-md-3 col-sm-3" style="margin-top : 4px;">
		<div class="bs-sidebar" style="margin-top : 4px;">
			<ul id="ul_report" class="nav nav-list bs-sidenav">
				<label><i class="fas fa-clipboard-list"></i> {{Rapport(s)}}</label>
				<li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
			</ul>
		</div>
	</div>

	<div class="col-lg-8 col-md-6 col-sm-6" style="margin-top : 4px;display:none;" id="div_reportForm">
		<form class="form-horizontal">
			<fieldset>
				<div id="currentReport" style="top: 10px;position: relative;"></div>
				<br/>
				<div class="input-group floatingbar" style="display:inline-flex;">
					<div class="input-group-btn">
						<a class="btn btn-success btn-sm roundedLeftt" id="bt_download"><i class="fas fa-download"></i> {{Télécharger}}
						</a><a class="btn btn-warning btn-sm" id="bt_remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}
						</a><a class="btn btn-danger btn-sm roundedRight" id="bt_removeAll"><i class="fas fa-trash"></i> {{Tout supprimer}}</a>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 col-xs-2 control-label">{{Nom}}</label>
					<div class="col-sm-4 col-xs-4">
						<input class="form-control reportAttr" type="text" data-l1key="filename" disabled />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 col-xs-2 control-label">{{Chemin}}</label>
					<div class="col-sm-8 col-xs-8">
						<input class="form-control reportAttr" type="text" data-l1key="path" disabled />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 col-xs-2 control-label">{{Type}}</label>
					<div class="col-sm-4 col-xs-4">
						<input class="form-control reportAttr" type="text" data-l1key="type" disabled />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 col-xs-2 control-label">{{Id rapport}}</label>
					<div class="col-sm-4 col-xs-4">
						<input class="form-control reportAttr" type="text" data-l1key="id" disabled />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 col-xs-2 control-label">{{Extension}}</label>
					<div class="col-sm-4 col-xs-4">
						<input class="form-control reportAttr" type="text" data-l1key="extension" disabled />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 col-xs-2 control-label">{{Aperçu}}</label>
					<div class="col-sm-10 col-xs-10" id="div_imgreport">
					</div>
				</div>
			</fieldset>
		</form>
	</div>
</div>

<?php include_file("desktop", "report", "js");?>