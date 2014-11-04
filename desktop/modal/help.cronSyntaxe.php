<?php
if (!isConnect()) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
include_file('3rdparty', 'jquery.cron/jquery.cron.min', 'js');
include_file('3rdparty', 'jquery.cron/jquery.cron', 'css');
?>

<div class="panel-group" id="accordion">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                    {{Générateur}}
                </a>
            </h4>
        </div>
        <div id="collapseOne" class="panel-collapse collapse in">
            <div class="panel-body">
                <script>
                    $('#div_helpCronGenerate').cron({
                        initial: "* * * * *",
                        onChange: function() {
                            $('#span_helpCronGenerate').text($(this).cron("value"));
                        }
                    });
                </script>
                <pre>
<div id='div_helpCronGenerate'></div>
<p>Resultat : <span id='span_helpCronGenerate'></span></p>
                </pre>
                <center><span style='font-weight: bold;'>N'oubliez pas de copier/coller le resultat</span></center>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                    {{Exemples / Programmation prédéfinie}}
                </a>
            </h4>
        </div>
        <div id="collapseTwo" class="panel-collapse collapse">
            <div class="panel-body">

                <table class="table">
                    <tbody><tr>
                            <th>{{Entrée}}</th>
                            <th>{{Description}}</th>
                        </tr>
                        <tr>
                            <td><code>1 0 * * *</code></td>
                            <td>{{Tous les jours 00:01}}</td>
                        </tr>
                        <tr>
                            <td><code>0 */2 * * *</code></td>
                            <td>{{toutes les 2 heures (à minuit, 2, 4, 6, 8, 10, 12, 14, 16, 18, 20, 22)}}</td>
                        </tr>
                        <tr>
                            <td><code>10 5 18 2 *</code></td>
                            <td>{{Une fois par an à 5h10 le 18 Février}}</td>
                        </tr>
                        <tr>
                            <td><code>14 18 2 * *</code></td>
                            <td>{{Une fois par mois à 18:14 le second jour du mois}}</td>
                        </tr>
                        <tr>
                            <td><code>0 12 * * 2</code></td>
                            <td>{{Une fois par semaine le mardi à 12:00}}</td>
                        </tr>
                        <tr>
                            <td><code>0 15 * * 1#1</code></td>
                            <td>{{Le premier mardi du mois à 15h00}}</td>
                        </tr>
                    </tbody></table>
                <pre>
*  *  *  *  *  *
|  |  |  |  |  |
|  |  |  |  |  + {{année [optionnel]}}
|  |  |  |  +----- {{jour de la semaine (0 - 7) (Dimanche=0 ou 7)}}
|  |  |  +---------- {{mois (1 - 12)}}
|  |  +--------------- {{jour du mois (1 - 31)}}
|  +-------------------- {{heure (0 - 23)}}
+------------------------- {{minute (0 - 59)}}
                </pre>

                <table class="table">
                    <tbody><tr>
                            <th>{{Nom du champ}}</th>
                            <th>{{Obligatoire}}</th>
                            <th>{{Valeurs autorisées}}</th>
                            <th>{{Caractères spéciaux autorisés}}</th>
                        </tr>
                        <tr>
                            <td>{{Minutes}}</td>
                            <td>{{Oui}}</td>
                            <td>0-59}}</td>
                            <td><code>* / , -</code></td>
                        </tr>
                        <tr>
                            <td>{{Heure}}</td>
                            <td>{{Oui}}</td>
                            <td>0-23</td>
                            <td><code>* / , -</code></td>
                        </tr>
                        <tr>
                            <td>{{Jour du mois}}</td>
                            <td>{{Oui}}</td>
                            <td>1-31</td>
                            <td><code>* / , -&nbsp;? L W</code></td>
                        </tr>
                        <tr>
                            <td>{{Mois}}</td>
                            <td>{{Oui}}</td>
                            <td>{{1-12 ou JAN-DEC}}</td>
                            <td><code>* / , -</code></td>
                        </tr>
                        <tr>
                            <td>{{Jour de la semaine}}</td>
                            <td>{{Oui}}</td>
                            <td>{{0-6 ou SUN-SAT}}</td>
                            <td><code>* / , -&nbsp;? L #</code></td>
                        </tr>
                        <tr>
                            <td>{{Année}}</td>
                            <td>{{Non}}</td>
                            <td>1970–2099</td>
                            <td><code>* / , -</code></td>
                        </tr>
                    </tbody>
                </table>
                <a href="http://en.wikipedia.org/wiki/Cron#Special_characters" target="_blank">{{Source}}</a>

                <h3><span class="mw-headline" id="Special_characters">{{Caractères spéciaux}}</span></h3>
                <p>{{Support pour chaque caractère spécial dépend des distributions et versions de cron spécifiques}}</p>
                <dl>
                    <dt>{{Astérisque ( * )}}</dt>
                    <dd>{{L'astérisque indique que l'expression cron correspond à toutes les valeurs du champ. Par exemple, en utilisant un astérisque dans le 4ème domaine (mois) indique chaque mois.}}</dd>
                </dl>
                <dl>
                    <dt>{{Slash ( / )}}</dt>
                    <dd>{{Les barres obliques décrivent l'incréments de gamme. Par exemple 3-59/15 dans le 1er champ (minutes) indique la troisième minute de l'heure et toutes les 15 minutes par la suite. La forme "* / ..." est équivalent à la forme «premier-dernier / ...", c'est-à une augmentation au cours de la plus large possible du champ.}}</dd>
                </dl>
                <dl>
                    <dt>{{Pourcent (&nbsp;% )}}</dt>
                    <dd>{{Pourcent (%) dans la commande, à moins échappé à barre oblique inverse (\), sont transformés en caractères de nouvelle ligne, et toutes les données après la première% sont envoyés à la commande comme entrée standard.}}</dd>
                </dl>
                <dl>
                    <dt>{{Virgule ( , )}}</dt>
                    <dd>{{Les virgules sont utilisées pour séparer les éléments d'une liste. Par exemple, en utilisant "MON,WED,FRI" dans le 5ème champ (jour de la semaine) signifie les lundis, mercredis et vendredis.}}</dd>
                </dl>
                <dl>
                    <dt>{{Trait d'union ( - )}}</dt>
                    <dd>{{Tirets définissent les plages. Par exemple, 2000-2010 indique chaque année entre 2000 et 2010 inclus.}}</dd>
                </dl>
                <dl>
                    <dt>L</dt>
                    <dd>{{'L' signifie "dernier". Lorsqu'il est utilisé dans le domaine du jour de la semaine, il vous permet de spécifier des constructions telles que "le dernier vendredi" ("5L1") d'un mois donné. Dans le domaine de jour de mois, il spécifie le dernier jour du mois.}}</dd>
                </dl>
                <dl>
                    <dt>W</dt>
                    <dd>{{Le caractère «W» est autorisé pour le champ jour du mois. Ce caractère est utilisé pour spécifier le jour de la semaine (lundi-vendredi) le plus proche du jour donné. Par exemple, si vous étiez à préciser "15W" comme valeur pour le champ jour de mois, le sens est: «la semaine la plus proche du 15 du mois." Donc, si le 15 est un samedi, les feux de déclenchement sur ​​le vendredi 14. Si le 15 est un dimanche, les feux de déclenchement lundi le 16. Si le 15 est un mardi, il déclenche le mardi 15. Toutefois, si vous spécifiez "1W" comme valeur pour le jour de mois, et le 1er est un samedi, les feux de déclenchement lundi la 3e, comme il le fait pas «sauter» sur la limite des jours d'un mois. Le caractère «W» peut être défini que lorsque le jour de mois est un jour, pas une plage ou liste des jours.}}</dd>
                </dl>
                <dl>
                    <dt>{{Dièse ( # )}}</dt>
                    <dd>{{«#» Est autorisé pour le champ du jour de la semaine, et doit être suivi par un nombre compris entre un et cinq. Il vous permet de spécifier des constructions telles que "le deuxième vendredi" d'un mois donné.}}</dd>
                </dl>
                <dl>
                    <dt>{{Point d'interrogation (&nbsp;? )}}</dt>
                    <dd>{{Remarque: Point d'interrogation est un caractère atypique et n'existe que dans certaines implémentations de cron. Il est utilisé à la place de '*' pour quitter soit vierge jour de mois ou le jour de la semaine.}}</dd>
                </dl>

            </div>
        </div>
    </div>
</div>
