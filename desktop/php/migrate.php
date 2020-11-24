<?php
if (!isConnect('admin')) {
	throw new Exception(__('401 - Accès non autorisé', __FILE__));
}
	include_file('3rdparty', 'animate/animate', 'css');
	include_file('3rdparty', 'animate/animate', 'js');
?>
<div id="contenu">
	<div id="step1">
		<span class="titleStep animated slideInLeft"><i class="fas fa-hdd"></i> {{Etape 1}}</span>
		<div id="contenuWithStepOne" class="animated zoomIn contenuWith">
			<div id="contenuImage">
				<img id="contenuImageSrc" src="/core/img/migrate/imageMaj_stepUn.jpg" />
			</div>
			<div id="contenuText" class="debut">
				<span id="contenuTextSpan">{{Insérer une clé USB de plus de 8Go}}<br /> {{dans votre Jeedom et cliquer sur}} <i class="fas fa-arrow-circle-right"></i>.</span>
				<span>Attention vous devez etre en local pour lancer cette procedure (ne pas lancer via les dns ou lien exterieur)</span>
				<div id="nextDiv">
					<i class="next fas fa-arrow-circle-right" id="bt_next"></i>
				</div>
			</div>
			<div id="contenuText" class="usb" style="display:none;"></div>
		</div>
	</div>
	<div id="step2">
		<span class="titleStep"><i class="fas fa-hdd"></i> {{Etape 2}}</span>
		<div id="contenuWithStepTwo" class="zoomIn contenuWith">
			<div id="contenuImage">
				<img id="contenuImageSrc" src="/core/img/migrate/imageMaj_stepDeux.jpg" />
			</div>
			<div id="contenuText" class="backup">
				<span id="contenuTextSpan" class="TextBackup">{{Backup lancé merci de patienter...}}</span>
				<div id="contenuTextSpan" class="progress">
					<div class="progress-bar progress-bar-striped progress-bar-animated active" role="progressbar" style="width: 0;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="step3">
		<span class="titleStep"><i class="fas fa-hdd"></i> {{Etape 3}}</span>
		<div id="contenuWithStepTree" class="zoomIn contenuWith">
			<div id="contenuImage">
				<img id="contenuImageSrc" src="/core/img/migrate/imageMaj_stepTrois.jpg" />
			</div>
			<div id="contenuText" class="imageUp">
				<span id="contenuTextSpan" class="TextImage">{{Téléchargement de l'image Jeedom.}}</span>
				<div id="contenuTextSpan" class="progress">
					<div class="progress-bar progress-bar-striped progress-bar-animated active" role="progressbar" style="width: 0;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
				</div>
			</div>
		</div>
	</div>
	<div id="step4">
		<span class="titleStep"><i class="fas fa-hdd"></i> {{Etape 4}}</span>
		<div id="contenuWithStepFor" class="zoomIn contenuWith">
			<div id="contenuImage">
				<img id="contenuImageSrc" src="/core/img/migrate/imageMaj_stepQuatre.jpg" />
			</div>
			<div id="contenuText" class="imageUp">
				<span id="contenuTextSpan" class="TextMigrate">{{Migration de votre Jeedom}}</span>
				<div id="contenuTextSpan" class="progress">
					<div class="progress-bar progress-bar-striped progress-bar-animated active" role="progressbar" style="width: 0;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
</div>
				</div>
			</div>
		</div>
	</div>
	<div id="step5">
		<span class="titleStep"><i class="fas fa-hdd"></i> {{Etape 5}}</span>
		<div id="contenuWithStepFive" class="zoomIn contenuWith">
			<div id="contenuImage">
				<img id="contenuImageSrc" src="/core/img/migrate/imageMaj_stepTrois.jpg" />
			</div>
			<div id="contenuText" class="imageUp">
				<span id="contenuTextSpan" class="TextFinalisation">{{Finalisation}}</span>
				<div id="contenuTextSpan" class="progress">
					<div class="progress-bar progress-bar-striped progress-bar-animated active" role="progressbar" style="width: 0;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modalReloadStep">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">{{Reprendre la restauration}}</h4>
      </div>
      <div class="modal-body">
        <p>{{Pour reprendre votre restauration cliquez sur "Reprendre".}}</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="bt_close" data-dismiss="modal">{{Fermer}}</button>
        <button type="button" class="btn btn-primary" id="bt_reprendre">{{Reprendre}}</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" tabindex="-1" role="dialog" id="modalFinalStep">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">{{Réinstallation de votre Backup}}</h4>
      </div>
      <div class="modal-body">
        <p>{{Voulez-vous installer votre Backup sur cette Jeedom ou bien repartir de zéro ?}}</p>
        <p>{{Votre backup est disponible sur votre Jeedom dans tous les cas.}}</p>
        <p>{{Si vous choisissez "zéro" n'oubliez pas que vos identifiants sont admin/admin}}</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="bt_zero">{{Zéro}}</button>
        <button type="button" class="btn btn-primary" id="bt_backup">{{Installer mon Backup}}</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" tabindex="-1" role="dialog" id="modalFirstStep">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">{{Informations Importantes}}</h4>
      </div>
      <div class="modal-body">
        <p>{{- Il vous faut une clé USB de plus de 8Go.}}</p>
	<p>{{- Il vous faut être sur le meme réseau Local que votre Jeedom.}}</p>
	<p>{{- Accéder à votre Jeedom depuis son adresse interne.}}</p>
	<p>{{- Pouvoir laisser cette page ouverte au minimum 1h30.}}</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">{{C'est parti}}</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="div_reboot_jeedom" style="display:none;">
	<script type="text/javascript" id="reboot_jeedom"></script>
</div>

<?php
include_file('desktop', 'migrate', 'css');
include_file('desktop', 'migrate', 'js');
?>
