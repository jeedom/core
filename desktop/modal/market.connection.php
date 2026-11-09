<?php
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
$login = (string) config::byKey('market::username');
?>
<form id="form_marketConnection" class="marketConnectionForm">
  <div class="alert alert-danger hidden" role="alert" data-market-error></div>
  <div data-market-known-account class="<?= $login === '' ? 'hidden' : '' ?>">
    <label>{{Compte Market}}</label>
    <div class="marketConnectionControls">
      <strong><?= htmlspecialchars($login, ENT_QUOTES, 'UTF-8') ?></strong>
      <button type="button" class="btn btn-default btn-xs" data-market-change-login><i class="fas fa-user-edit"></i> {{Changer de compte}}</button>
    </div>
  </div>
  <div data-market-login-field class="<?= $login === '' ? '' : 'hidden' ?>">
    <label for="in_marketConnectionLogin">{{Identifiant}}</label>
    <input id="in_marketConnectionLogin" name="login" type="text" class="form-control" autocomplete="username" required value="<?= htmlspecialchars($login, ENT_QUOTES, 'UTF-8') ?>">
  </div>
  <div>
    <label for="in_marketConnectionPassword">{{Mot de passe}}</label>
    <input id="in_marketConnectionPassword" name="password" type="password" class="form-control" autocomplete="current-password" required>
  </div>
  <div>
    <label for="in_marketConnectionTwoFactor">{{Code de double authentification (si activée)}}</label>
    <input id="in_marketConnectionTwoFactor" name="twoFactorCode" type="text" class="form-control" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]{6}" maxlength="6">
  </div>
  <div class="marketConnectionFormActions">
    <button type="button" class="btn btn-default" data-market-cancel><i class="fas fa-times"></i> {{Annuler}}</button>
    <button type="submit" class="btn btn-success"><i class="fas fa-link"></i> {{Se connecter}}</button>
  </div>
</form>
<script>
(function() {
  const form = document.getElementById('form_marketConnection')
  const login = form.elements.login
  const password = form.elements.password
  const twoFactor = form.elements.twoFactorCode
  const close = () => jeeDialog.get('#md_marketConnection').destroy()
  form.querySelector('[data-market-cancel]').addEventListener('click', close)
  form.querySelector('[data-market-change-login]').addEventListener('click', () => {
    form.querySelector('[data-market-known-account]').unseen()
    form.querySelector('[data-market-login-field]').seen()
    login.focus()
    login.select()
  })
  form.addEventListener('submit', event => {
    event.preventDefault()
    if (!form.reportValidity() || form.dataset.busy == '1') return
    const errorBox = form.querySelector('[data-market-error]')
    errorBox.unseen()
    const credentials = { login: login.jeeValue().trim(), password: password.jeeValue(), twoFactorCode: twoFactor.jeeValue().trim() }
    form.dataset.busy = '1'
    form.querySelectorAll('button, input').forEach(input => { input.disabled = true })
    jeedom.repo.connectMarket({
      ...credentials,
      error: function(error) {
        form.dataset.busy = '0'
        form.querySelectorAll('button, input').forEach(input => { input.disabled = false })
        errorBox.textContent = error.message
        errorBox.seen()
        if (error.code == 401 && twoFactor.jeeValue() !== '') twoFactor.focus()
      },
      success: function(state) {
        password.jeeValue('')
        twoFactor.jeeValue('')
        document.getElementById('div_marketConnection')?.triggerEvent('marketConnectionChanged', { detail: state })
        close()
        jeedomUtils.showAlert({ message: state.warning || '{{Connexion au Market réussie}}', level: state.warning ? 'warning' : 'success' })
      }
    })
  })
  setTimeout(() => (login.jeeValue() === '' ? login : password).focus(), 0)
})()
</script>
