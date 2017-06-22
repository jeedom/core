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
                    <th>{{Nom d'utilisateur}}</th>
                    <th>{{Actif}}</th>
                    <th>{{Profils}}</th>
                    <th>{{Clef API}}</th>
                    <th>{{Double authentification}}</th>
                    <th>{{Date de dernière connexion}}</th>
                    <th>{{Actions}}</th>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

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
                        <input class="form-control" type="text"  id="in_newUserLogin" placeholder="{{Login}}"/><br/><br/>
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
