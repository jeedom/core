<?php
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
$marketConnection = marketClient::status();
$connected = $marketConnection['connected'];
?>
<div id="div_marketConnection" data-connected="<?= $connected ? '1' : '0' ?>">
  <div class="marketConnectionStatus" role="status" aria-live="polite">
    <span data-market-state class="label <?= $connected ? 'label-success' : 'label-default' ?>"><?= $connected ? '{{Connecté}}' : '{{Déconnecté}}' ?></span>
    <span data-market-login><?= htmlspecialchars($marketConnection['login'], ENT_QUOTES, 'UTF-8') ?></span>
  </div>
  <div class="marketConnectionControls">
    <button type="button" class="btn btn-success<?= $connected ? ' hidden' : '' ?>" data-market-action="connect"><i class="fas fa-link"></i> {{Se connecter au Market}}</button>
    <button type="button" class="btn btn-default<?= $connected ? '' : ' hidden' ?>" data-market-action="reconnect"><i class="fas fa-sync"></i> {{Reconnecter}}</button>
    <button type="button" class="btn btn-danger<?= $connected ? '' : ' hidden' ?>" data-market-action="disconnect"><i class="fas fa-sign-out-alt"></i> {{Déconnecter}}</button>
  </div>
</div>
