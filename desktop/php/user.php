<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
sendVarToJS('ldapEnable', config::byKey('ldap::enable'));
?>
<div id="div_administration">


  <!--********************Onglet utilisateur********************************-->
  <div class="tab-pane" id="user">
    <legend><i class="icon personne-toilet1"></i>  {{Liste des utilisateurs :}}</legend>
    <a class="btn btn-success pull-right" id="bt_saveUser"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
    <?php if (config::byKey('ldap::enable') != '1') {?>
      <a class="btn btn-warning pull-right" id="bt_addUser"><i class="fa fa-plus-circle"></i> {{Ajouter un utilisateur}}</a>
      <br/><br/>
      <?php }
?>
      <table class="table table-condensed table-bordered" id="table_user">
        <thead>
          <th>{{Utilisateur}}</th>
          <th style="width: 100px;">{{Actif}}</th>
          <th>{{Profil}}</th>
          <th>{{Clef API}}</th>
          <th>{{Double authentification}}</th>
          <th>{{Dernière connexion}}</th>
          <th>{{Actions}}</th>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>

  <form class="form-horizontal">
    <fieldset>
      <legend>{{Sessions actives}}</legend>
      <table class="table table-condensed table-bordered">
        <thead>
          <tr>
            <th>{{ID}}</th><th>{{Login}}</th><th>{{IP}}</th><th>{{Date}}</th><th>{{Actions}}</th>
          </tr>
        </thead>
        <tbody>
          <?php
cleanSession();
$cache = cache::byKey('current_sessions');
$sessions = $cache->getValue(array());
foreach ($sessions as $id => $session) {
	echo '<tr data-id="' . $id . '">';
	echo '<td>' . $id . '</td>';
	echo '<td>' . $session['login'] . '</td>';
	echo '<td>' . $session['ip'] . '</td>';
	echo '<td>' . $session['datetime'] . '</td>';
	echo '<td><a class="btn btn-xs btn-warning bt_deleteSession"><i class="fa fa-sign-out"></i> {{Déconnecter}}</a></td>';
	echo '</tr>';
}
?>
       </tbody>
     </table>
   </fieldset>
 </form>
 <form class="form-horizontal">
  <fieldset>
    <legend>{{Péripherique enregistrés}} <a class="btn btn-xs btn-warning pull-right" id="bt_removeAllRegisterDevice"><i class="fa fa-trash"></i> {{Supprimer tout}}</a></legend>
    <table class="table table-bordered table-condensed">
      <thead>
        <tr>
          <th>{{Identification}}</th>
          <th>{{Utilisateur}}</th>
          <th>{{IP}}</th>
          <th>{{Date dernière utilisation}}</th>
          <th>{{Action}}</th>
        </tr>
      </thead>
      <tbody>
        <?php
foreach (user::all() as $user) {
	foreach ($user->getOptions('registerDevice') as $key => $value) {
		echo '<tr data-key="' . $key . '" data-user_id="' . $user->getId() . '">';
		echo '<td>';
		echo substr($key, 0, 10) . '...';
		echo '</td>';
		echo '<td>';
		echo $user->getLogin();
		echo '</td>';
		echo '<td>';
		echo $value['ip'];
		echo '</td>';
		echo '<td>';
		echo $value['datetime'];
		echo '</td>';
		echo '<td>';
		echo '<a class="btn btn-warning btn-xs bt_removeRegisterDevice"><i class="fa fa-trash"></i> {{Supprimer}}</a>';
		echo '</td>';
		echo '</tr>';
	}
}
?>
    </tbody>
  </table>
</fieldset>
</form>

<div class="modal fade" id="md_newUser">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button class="close" data-dismiss="modal">×</button>
        <h3>{{Ajouter un utilisateur}}</h3>
      </div>
      <div class="modal-body">
        <div style="display: none;" id="div_newUserAlert"></div>
        <center>
          <input class="form-control" type="text"  id="in_newUserLogin" placeholder="{{Identifiant}}"/><br/><br/>
          <input class="form-control" type="password"  id="in_newUserMdp" placeholder="{{Mot de passe}}"/>
        </center>
      </div>
      <div class="modal-footer">
        <a class="btn btn-default" data-dismiss="modal">{{Annuler}}</a>
        <a class="btn btn-primary" id="bt_newUserSave"><i class="fa fa-check-circle"></i> {{Enregistrer}}</a>
      </div>
    </div>
  </div>
</div>

<?php include_file("desktop", "user", "js");?>
