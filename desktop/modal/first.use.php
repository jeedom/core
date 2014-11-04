<?php
if (!isConnect()) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<legend>
    Mes premiers pas dans Jeedom
    <a class='btn btn-default btn-xs pull-right' id='bt_doNotDisplayFirstUse'><i class="fa fa-eye-slash"></i> Ne plus afficher</a>
</legend>
<div id="div_alertFirstUse"></div>

Bienvenue dans Jeedom, et merci d'avoir choisi cet outil pour votre habitat connecté. Cet assitant va vous guider pour faire la configuration de base de votre Jeedom.
<br/><br/>
<legend>Etape 1 : créer un compte sur le market</legend>
<p>
    Le market permet à Jeedom d'avoir accès à une grande collection de plugins, widgets, configurations de module zwave...<br/> 
    Il peut aussi vous permettre de sauvegarder votre Jeedom. 
    Nous allons voir ici comment vous inscrire sur celui-ci.
    Pour cela rien de plus simple cliquez <a target="_blank" href='https://market.jeedom.fr/index.php?v=d&p=register'>ici</a> 
    (si vous avez déja un compte vous pouvez sauter cette étape) et suivez les instructions, n'oubliez pas de mémoriser vos identifiants...vous allez en avoir besoin dans la prochaine étape. 
</p>

<br/>
<legend>Etape 2 : associer votre Jeedom à votre compte sur le market</legend>
<p>
    Allez <a target="_blank" href='index.php?v=d&p=administration&noFirstUse=1'>ici</a>, puis cherchez le "volet" market et mettez vos identifiants précédemment créés.
    Renseignez ensuite les champs "Nom d'utilisateur" et "Mot de passe",sauvegardez et cliquez sur le bouton "Tester", si un message vert vous indiquant que tout va bien apparaît, c'est bon !!!
</p>

<legend>Etape 3 : créer vos objets</legend>
<p>
    Allez <a target="_blank" href='index.php?v=d&p=object&noFirstUse=1'>ici</a>, puis cliquez sur "Ajouter un objet", donnez lui un nom et voilà votre premier objet est créé. 
    Ici vous pouvez aussi créer toute une hiérarchie d'objets qui peuvent représenter votre habitat.
</p>

<legend>Etape 4 : créer vos utilisateurs</legend>
<p>
    Allez sur <a target="_blank" href='index.php?v=d&p=user&noFirstUse=1'>ici</a>, et créez vos utilisateurs et surtout changez le mot de passe de votre compte actuel.
</p>



<script>
    $('#bt_doNotDisplayFirstUse').on('click', function () {
        jeedom.config.save({
            configuration: {'jeedom::firstUse': 0},
            error: function (error) {
                $('#div_alertFirstUse').showAlert({message: error.message, level: 'danger'});
            },
            success: function () {
                $('#div_alertFirstUse').showAlert({message: '{{Sauvegarde réussie}}', level: 'success'});
            }
        });
    });
</script>
