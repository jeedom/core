<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
switch (system::getArch()) {
	case 'arm64':
		if (!recovery::isInstalled() && !recovery::install()) {
			throw new Exception('{{Impossible de mettre à jour le script de démarrage, vérifier les logs}}');
		}
		break;
	default:
		throw new Exception('{{Cette fonctionnalité est uniquement disponible sur les systèmes à base ARM64}}');
}

$product = config::byKey('product_name');
$hardware = strtolower(jeedom::getHardwareName());
$image = config::byKey('product_connection_image');
$mbState = config::byKey('mbState');
sendVarToJS('jeephp2js.hardware', $hardware);
?>

<div class="text-center" id="div_recovery">
	<h3>{{Restauration système}}</h3>
	<img src="<?= $image ?>" alt="Product Image">
	<div class="bold" id="recovery-step" style="min-height:50px">
		<?= $product . ' ' . ucfirst($hardware) ?> {{intègre une fonctionnalité de restauration système automatique au démarrage, avec deux modes d'exécution}} :
	</div>
	<div class="progress" style="display:none">
		<div id="recovery-progress" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
	</div>
	<div id="recovery-details" style="flex-grow:1">
		<ul class="text-left">
			<li>
				<div class="bold">{{Restauration automatique}} :</div>
				{{La dernière image système est téléchargée sur le support de stockage interne.}}
				<br>
				{{La restauration du système est effectuée au prochain démarrage.}}
			</li>
			<br>
			<li>
				<div class="bold">{{Restauration USB}} :</div>
				{{La dernière image système est téléchargée sur une clé USB.}}
				<br>
				{{La restauration du système aura lieu au démarrage si la clé est branchée dans le port USB situé en haut à droite.}}
			</li>
		</ul>
		<?php if ($mbState == 0) { ?>
			<br>
			<div class="alert alert-info">
				{{Consulter la documentation dédiée pour plus de détails}} :
				<a href="https://doc.jeedom.com/" target="_blank" class="btn btn-default btn-xs" role="button"><i class="fas fa-book"></i> {{Documentation}}</a>
			</div>
		<?php } ?>
	</div>
	<div class="alert alert-warning" id="recovery-warning">
		<i class="fas fa-exclamation-triangle"></i> {{Une sauvegarde récente doit impérativement être téléchargée avant de démarrer la restauration du système}}
	</div>
	<div id="recovery-buttons" style="min-height:35px">
		<a class="btn btn-success" id="bt_auto"><i class="fas fa-hdd"></i> {{Restauration automatique}}</a>
		<a class="btn btn-primary" id="bt_usb"><i class="fab fa-usb"></i> {{Restauration USB}}</a>
		<a class="btn btn-danger" id="bt_cancel" style="display:none"><i class="fas fa-times"></i> {{Annuler}}</a>
		<a class="btn btn-success" id="bt_reboot" style="display:none"><i class="fas fa-redo"></i> {{Redémarrer}}</a>
		<a class="btn btn-warning" id="bt_halt" style="display:none"><i class="fas fa-stop"></i> {{Arrêter}}</a>
		<a class="btn btn-sm btn-info" id="bt_refresh" style="display:none"><i class="fas fa-sync"></i> {{Rafraîchir la page}}</a>
	</div>
</div>

<style>
	#div_recovery {
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: space-between;
		max-width: 640px;
		min-height: 820px;
		margin: 15px auto 0 auto;
		padding: 0 20px 15px 20px;
		background-color: rgba(var(--eq-bg-color), var(--opacity));
		border-radius: var(--border-radius);
	}

	#div_recovery>img {
		max-width: 300px;
		max-height: 220px;
	}

	#recovery-details,
	#div_recovery>.progress {
		width: 95%;
		margin: 10px auto;
	}

	#recovery-details {
		height: 300px;
		overflow: hidden;
	}

	.bold {
		font-weight: bold;
	}
</style>

<?php include_file("desktop", "recovery", "js"); ?>
