<?php
if (!isConnect()) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
?>

<div class="alert alert-danger">
    <legend>{{Principe}}</legend>
    {{Le principe est le suivant : une phrase de commande déclenche une unique commande. Pour simplifier la création des phrases il est possible d'utiliser des mots clef afin que jeedom génère automatiquement une liste de phrases.}}
</div>

<div class="alert alert-success">
    <legend>{{Demande}}</legend>
    {{Vous pouvez utiliser #commande# et #objet# (les 2 doivent absolument être utilisés ensemble) pour générer une liste de commandes (il est possible de filtrer la génération pour réduire la liste). Il est aussi possible d'utiliser #equipement# (utile si plusieurs commandes appartenant au même objet ont le même nom)}}
    <br/>{{Exemple :}} <em>{{Quelle est la #commande# [du |de la |de l']#objet#}}</em>

    <br/><br/>
    {{Lors de la génération des commandes vous pouvez utiliser le champ synonyme (syn1=syn2,syn3|syn4=syn5) pour remplacer le nom des objets, des équipements et/ou des commandes }}
    <br/><br/>
    {{Pour les actions vous pouvez utiliser #color# (obligatoire si la commande est de type couleur) pour la valeur d'une couleur ou #slider# (obligatoire si la commande est de type slider) pour la valeur d'un slider.}}
</div>

<div class="alert alert-warning">
    <legend>{{Réponse}}</legend>
    {{Vous pouvez utiliser #valeur# et #unite# dans le retour (ils seront remplacés par la valeur et l'unité de la commande). Toutes les valeurs passées dans demande (#mavaleur#) sont accessibles. Vous avez aussi #heure#, #date#, #jour# et #datetime#."}}
    <br/>{{Exemple :}} <em>{{#valeur# #unite#}}</em>

    <br/><br/>
    {{Vous pouvez utiliser le champ conversion binaire pour convertir les valeurs binaires (0 et 1) : }}
    <br/>{{Exemple :}} <em>{{non|oui}}</em>
</div>

<div class="alert alert-info">
    <legend>{{Personne}}</legend>
    {{Le champ personne permet de n'autoriser que certaines personnes \u00e0 exécuter la commande, vous pouvez mettre plusieurs profils en les séparant par |.}}
    <br/>{{Exemple :}} <em>{{personne1|personne2}}</em>
</div>

